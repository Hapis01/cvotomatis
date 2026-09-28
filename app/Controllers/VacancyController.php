<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\VacancyService;
use App\Middleware\AuthMiddleware;

class VacancyController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $userId = \App\Helpers\AuthHelper::id();
        $vacancies = VacancyService::listAll();
        $trackings = VacancyService::getTrackingsWithSync($userId);

        // Hitung statistik tracking
        $stats = [
            'total' => count($trackings),
            'dilamar' => 0,
            'progress' => 0,
            'berhasil' => 0,
            'gagal' => 0
        ];
        foreach ($trackings as $t) {
            if ($t['status'] === 'Berhasil Dilamar') $stats['dilamar']++;
            elseif ($t['status'] === 'Tahap Progress') $stats['progress']++;
            elseif ($t['status'] === 'Berhasil') $stats['berhasil']++;
            elseif ($t['status'] === 'Gagal') $stats['gagal']++;
        }

        ob_start();
        require_once __DIR__ . '/../../resources/views/vacancies/index.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Input & Ekstraksi Lowongan Kerja',
            'content' => $content
        ]);
    }

    public function create()
    {
        ob_start();
        require_once __DIR__ . '/../../resources/views/vacancies/create.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Tambah Lowongan Kerja Baru',
            'content' => $content
        ]);
    }

    public function store()
    {
        $title = trim($_POST['title'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $pastedText = trim($_POST['pasted_text'] ?? '');
        $uploadedFile = $_FILES['file'] ?? null;

        if (empty($title)) {
            $_SESSION['error'] = "Judul lowongan kerja wajib diisi.";
            $this->redirect('/vacancies/create');
            return;
        }

        // Cek apakah ada konten (file atau teks)
        $hasFile = $uploadedFile && isset($uploadedFile['tmp_name']) && is_uploaded_file($uploadedFile['tmp_name']);
        if (!$hasFile && empty($pastedText)) {
            $_SESSION['error'] = "Silakan unggah dokumen PDF/Gambar lowongan ATAU tempelkan teks deskripsi pekerjaan.";
            $this->redirect('/vacancies/create');
            return;
        }

        try {
            $userId = \App\Helpers\AuthHelper::id();
            $result = VacancyService::processVacancy($title, $company, $pastedText, $uploadedFile, $userId);
            $_SESSION['success'] = "Lowongan berhasil diekstrak dan otomatis tercatat di Tabel Tracking Lamaran!";
            $this->redirect('/vacancies/show?folder=' . urlencode($result['folder']));
        } catch (\Exception $e) {
            $_SESSION['error'] = "Terjadi kesalahan saat memproses lowongan: " . $e->getMessage();
            $this->redirect('/vacancies/create');
        }
    }

    public function show()
    {
        $folder = $_GET['folder'] ?? '';
        if (empty($folder)) {
            $_SESSION['error'] = "Folder lowongan tidak ditemukan.";
            $this->redirect('/vacancies');
            return;
        }

        $vacancy = VacancyService::getVacancy($folder);
        if (!$vacancy) {
            $_SESSION['error'] = "Data lowongan tidak ditemukan.";
            $this->redirect('/vacancies');
            return;
        }

        ob_start();
        require_once __DIR__ . '/../../resources/views/vacancies/show.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Detail Lowongan: ' . $vacancy['title'],
            'content' => $content
        ]);
    }

    public function download()
    {
        $folder = $_GET['folder'] ?? '';
        $vacancy = VacancyService::getVacancy($folder);

        if (!$vacancy) {
            $_SESSION['error'] = "File tidak ditemukan.";
            $this->redirect('/vacancies');
            return;
        }

        $filePath = $vacancy['path'] . DIRECTORY_SEPARATOR . $vacancy['md_file'];
        if (!file_exists($filePath)) {
            $_SESSION['error'] = "File Markdown tidak ditemukan.";
            $this->redirect('/vacancies');
            return;
        }

        header('Content-Type: text/markdown; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $vacancy['md_file'] . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    public function downloadSource()
    {
        $folder = $_GET['folder'] ?? '';
        $vacancy = VacancyService::getVacancy($folder);

        if (!$vacancy || !$vacancy['source_file']) {
            $_SESSION['error'] = "File lampiran asli tidak ditemukan.";
            $this->redirect('/vacancies');
            return;
        }

        $filePath = $vacancy['path'] . DIRECTORY_SEPARATOR . $vacancy['source_file'];
        if (!file_exists($filePath)) {
            $_SESSION['error'] = "File lampiran fisik tidak ditemukan.";
            $this->redirect('/vacancies');
            return;
        }

        $mime = mime_content_type($filePath) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . $vacancy['source_file'] . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    public function delete()
    {
        $folder = $_POST['folder'] ?? '';
        $userId = \App\Helpers\AuthHelper::id();
        
        // Hapus tracking terkait
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM vacancy_trackings WHERE folder_name = ? AND user_id = ?");
        $stmt->execute([$folder, $userId]);

        if (VacancyService::deleteVacancy($folder)) {
            $_SESSION['success'] = "Folder lowongan dan tracking berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus folder lowongan.";
        }

        $this->redirect('/vacancies');
    }

    public function updateTracking()
    {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? 'Berhasil Dilamar';
        $stage = trim($_POST['stage'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $appliedDate = !empty($_POST['applied_date']) ? $_POST['applied_date'] : null;
        $userId = \App\Helpers\AuthHelper::id();

        if ($id) {
            VacancyService::updateTrackingStatus($id, $userId, $status, $stage, $notes, $appliedDate);
            $_SESSION['success'] = "Status tracking lamaran berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "ID tracking tidak valid.";
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/vacancies');
    }

    public function deleteTracking()
    {
        $id = $_POST['id'] ?? null;
        $deleteFolder = isset($_POST['delete_folder']) && $_POST['delete_folder'] == '1';
        $userId = \App\Helpers\AuthHelper::id();

        if ($id) {
            VacancyService::deleteTracking($id, $userId, $deleteFolder);
            $_SESSION['success'] = "Data tracking lamaran berhasil dihapus.";
        } else {
            $_SESSION['error'] = "ID tracking tidak valid.";
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/vacancies');
    }
}
