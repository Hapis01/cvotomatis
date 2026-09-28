<?php

namespace App\Services;

use Smalot\PdfParser\Parser as PdfParser;

class VacancyService
{
    protected static $baseDir = __DIR__ . '/../../vacancies';

    public static function getBaseDir()
    {
        $dir = realpath(self::$baseDir);
        if (!$dir) {
            mkdir(self::$baseDir, 0755, true);
            $dir = realpath(self::$baseDir);
        }
        return $dir;
    }

    public static function sanitizeFolderName($title)
    {
        // Ganti spasi dan karakter khusus dengan underscore
        $name = preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $title);
        $name = preg_replace('/\s+/', '_', trim($name));
        $name = preg_replace('/_+/', '_', $name);
        $name = trim($name, '_');
        
        return $name ?: 'Lowongan_' . date('Ymd_His');
    }

    public static function processVacancy($title, $company = '', $pastedText = '', $uploadedFile = null, $userId = null)
    {
        $baseDir = self::getBaseDir();
        $folderName = self::sanitizeFolderName($title);

        $targetDir = $baseDir . DIRECTORY_SEPARATOR . $folderName;
        if (is_dir($targetDir)) {
            // Jika folder sudah ada, tambahkan penanda waktu unik
            $folderName .= '_' . date('His');
            $targetDir = $baseDir . DIRECTORY_SEPARATOR . $folderName;
        }

        if (!mkdir($targetDir, 0755, true)) {
            throw new \Exception("Gagal membuat direktori: {$folderName}");
        }

        $sourceType = 'Teks Manual';
        $sourceFile = null;
        $extractedText = '';

        // 1. Tangani Upload File jika ada
        if ($uploadedFile && isset($uploadedFile['tmp_name']) && is_uploaded_file($uploadedFile['tmp_name'])) {
            $origName = basename($uploadedFile['name']);
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $safeFileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $origName);
            $destPath = $targetDir . DIRECTORY_SEPARATOR . $safeFileName;

            if (move_uploaded_file($uploadedFile['tmp_name'], $destPath)) {
                $sourceFile = $safeFileName;

                // PDF Extraction
                if ($ext === 'pdf') {
                    $sourceType = 'Dokumen PDF';
                    try {
                        $parser = new PdfParser();
                        $pdf = $parser->parseFile($destPath);
                        $extractedText = trim($pdf->getText());
                        
                        // Fallback jika PDF berupa gambar scan (teks kosong)
                        if (empty($extractedText)) {
                            $extractedText = "[Catatan: Dokumen PDF ini berupa hasil scan/gambar tanpa text layer langsung. Teks bawaan tidak terdeteksi. Silakan unggah versi gambar (screenshot) untuk OCR otomatis.]";
                        }
                    } catch (\Exception $e) {
                        $extractedText = "[Gagal membaca konten PDF: " . $e->getMessage() . "]";
                    }
                }
                // Image Extraction (OCR)
                elseif (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'bmp'])) {
                    $sourceType = "Gambar / Screenshot (OCR)";
                    $ocrResult = OcrService::extractFromImage($destPath);
                    if ($ocrResult && trim($ocrResult) !== '') {
                        $extractedText = trim($ocrResult);
                    } else {
                        $extractedText = "[OCR selesai, namun tidak ada teks yang terbaca jelas dari gambar ini.]";
                    }
                }
            }
        }

        // 2. Gabungkan dengan Pasted Text jika ada
        $finalContent = '';
        if (!empty($extractedText)) {
            $finalContent .= $extractedText;
        }

        if (!empty(trim($pastedText))) {
            if (!empty($finalContent)) {
                $finalContent .= "\n\n### Catatan Tambahan / Input Teks:\n" . trim($pastedText);
                $sourceType .= " + Teks Manual";
            } else {
                $finalContent = trim($pastedText);
                $sourceType = "Teks Manual";
            }
        }

        if (empty(trim($finalContent))) {
            $finalContent = "Tidak ada konten deskripsi yang dimasukkan.";
        }

        // 3. Bangun Dokumen Markdown (.md)
        $dateStr = date('Y-m-d H:i:s');
        $md = "# " . trim($title) . "\n\n";
        $md .= "- **Perusahaan:** " . ($company ?: 'N/A') . "\n";
        $md .= "- **Tanggal Input:** " . $dateStr . "\n";
        $md .= "- **Metode Sumber:** " . $sourceType . "\n";
        if ($sourceFile) {
            $md .= "- **File Lampiran:** [" . $sourceFile . "](" . $sourceFile . ")\n";
        }
        $md .= "\n---\n\n";
        $md .= "## Deskripsi & Informasi Lowongan\n\n";
        $md .= $finalContent . "\n\n";
        $md .= "---\n\n";
        $md .= "## Analisis Rekomendasi Profil & Tindak Lanjut\n\n";
        $md .= "- Gunakan deskripsi di atas untuk mencocokkan kata kunci (*keywords*) pada CV ATS kamu.\n";
        $md .= "- Cek kualifikasi skill teknis, bahasa pemrograman, dan pengalaman yang disyaratkan sebelum mengirimkan lamaran.\n";
        $md .= "- Buat lamaran tertaut melalui menu **Job Applications** dengan klik tombol buat lamaran dari lowongan ini.\n";

        // 4. Tulis File Markdown
        $mdFileName = $folderName . '.md';
        $mdFilePath = $targetDir . DIRECTORY_SEPARATOR . $mdFileName;
        file_put_contents($mdFilePath, $md);
        
        // Simpan juga sebagai README.md agar mudah dilihat jika dibuka di file explorer / editor
        file_put_contents($targetDir . DIRECTORY_SEPARATOR . 'README.md', $md);

        // 5. Otomatis buat entri di tabel tracking lowongan (Default: Berhasil Dilamar)
        if ($userId) {
            try {
                $db = \App\Core\Database::getInstance()->getConnection();
                $stmt = $db->prepare("
                    INSERT INTO vacancy_trackings (user_id, folder_name, job_title, company_name, applied_date, status, stage, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, 'Berhasil Dilamar', 'Administrasi / Screening', NOW(), NOW())
                ");
                $stmt->execute([
                    $userId,
                    $folderName,
                    $title,
                    $company ?: null,
                    date('Y-m-d')
                ]);
            } catch (\Exception $e) {
                // Jangan gagalkan proses ekstraksi jika tracking insert error
            }
        }

        return [
            'folder' => $folderName,
            'title' => $title,
            'company' => $company,
            'source_type' => $sourceType,
            'source_file' => $sourceFile,
            'md_file' => $mdFileName,
            'md_path' => $mdFilePath,
            'content' => $md
        ];
    }

    public static function listAll()
    {
        $baseDir = self::getBaseDir();
        $items = [];

        if (!is_dir($baseDir)) {
            return $items;
        }

        $dirs = scandir($baseDir);
        foreach ($dirs as $d) {
            if ($d === '.' || $d === '..') continue;
            $fullPath = $baseDir . DIRECTORY_SEPARATOR . $d;
            if (is_dir($fullPath)) {
                $mdFile = $fullPath . DIRECTORY_SEPARATOR . $d . '.md';
                $readmeFile = $fullPath . DIRECTORY_SEPARATOR . 'README.md';
                
                $activeMd = file_exists($mdFile) ? $mdFile : (file_exists($readmeFile) ? $readmeFile : null);
                
                $title = str_replace('_', ' ', $d);
                $createdAt = date('Y-m-d H:i:s', filemtime($fullPath));
                $sourceFile = null;

                // Cari file lampiran non-md
                $files = scandir($fullPath);
                foreach ($files as $f) {
                    if ($f !== '.' && $f !== '..' && !str_ends_with($f, '.md')) {
                        $sourceFile = $f;
                        break;
                    }
                }

                $items[] = [
                    'folder' => $d,
                    'title' => $title,
                    'has_md' => $activeMd !== null,
                    'md_file' => $activeMd ? basename($activeMd) : null,
                    'source_file' => $sourceFile,
                    'created_at' => $createdAt,
                    'size' => $activeMd ? filesize($activeMd) : 0
                ];
            }
        }

        // Urutkan terbaru di atas
        usort($items, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));

        return $items;
    }

    public static function getVacancy($folder)
    {
        $baseDir = self::getBaseDir();
        // Cegah path traversal
        $folder = basename($folder);
        $targetDir = $baseDir . DIRECTORY_SEPARATOR . $folder;

        if (!is_dir($targetDir)) {
            return null;
        }

        $mdFile = $targetDir . DIRECTORY_SEPARATOR . $folder . '.md';
        if (!file_exists($mdFile)) {
            $mdFile = $targetDir . DIRECTORY_SEPARATOR . 'README.md';
        }

        $content = file_exists($mdFile) ? file_get_contents($mdFile) : '';

        // Cari source file
        $sourceFile = null;
        $files = scandir($targetDir);
        foreach ($files as $f) {
            if ($f !== '.' && $f !== '..' && !str_ends_with($f, '.md')) {
                $sourceFile = $f;
                break;
            }
        }

        return [
            'folder' => $folder,
            'title' => str_replace('_', ' ', $folder),
            'md_file' => basename($mdFile),
            'source_file' => $sourceFile,
            'content' => $content,
            'path' => $targetDir
        ];
    }

    public static function deleteVacancy($folder)
    {
        $baseDir = self::getBaseDir();
        $folder = basename($folder);
        $targetDir = $baseDir . DIRECTORY_SEPARATOR . $folder;

        if (!is_dir($targetDir)) {
            return false;
        }

        $files = scandir($targetDir);
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            @unlink($targetDir . DIRECTORY_SEPARATOR . $f);
        }

        return @rmdir($targetDir);
    }

    public static function getTrackingsWithSync($userId)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $baseDir = self::getBaseDir();

        if (is_dir($baseDir)) {
            $dirs = scandir($baseDir);
            foreach ($dirs as $d) {
                if ($d === '.' || $d === '..') continue;
                $fullPath = $baseDir . DIRECTORY_SEPARATOR . $d;
                if (!is_dir($fullPath)) continue;

                // Cek apakah sudah ada di database untuk user ini
                $stmtCheck = $db->prepare("SELECT id FROM vacancy_trackings WHERE user_id = ? AND folder_name = ?");
                $stmtCheck->execute([$userId, $d]);
                $existing = $stmtCheck->fetch();

                if (!$existing) {
                    // Ekstrak title & company dari markdown jika ada
                    $mdFile = $fullPath . DIRECTORY_SEPARATOR . $d . '.md';
                    if (!file_exists($mdFile)) {
                        $mdFile = $fullPath . DIRECTORY_SEPARATOR . 'README.md';
                    }
                    $content = file_exists($mdFile) ? file_get_contents($mdFile) : '';
                    
                    preg_match('/^#\s+(.+)$/m', $content, $mTitle);
                    preg_match('/-\s+\*\*Perusahaan:\*\*\s*(.+)$/m', $content, $mComp);
                    
                    $title = !empty($mTitle[1]) ? trim($mTitle[1]) : str_replace('_', ' ', $d);
                    $comp = !empty($mComp[1]) ? trim($mComp[1]) : null;
                    $appliedDate = date('Y-m-d', filemtime($fullPath));

                    $stmtIns = $db->prepare("
                        INSERT INTO vacancy_trackings (user_id, folder_name, job_title, company_name, applied_date, status, stage, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, 'Berhasil Dilamar', 'Administrasi / Screening', NOW(), NOW())
                    ");
                    $stmtIns->execute([$userId, $d, $title, $comp, $appliedDate]);
                }
            }
        }

        // Ambil seluruh data tracking beserta relasi sub-profil jika ada
        $stmt = $db->prepare("
            SELECT vt.*, cp.id as sub_profile_id, cp.professional_title as sub_profile_title 
            FROM vacancy_trackings vt
            LEFT JOIN candidate_profiles cp ON vt.folder_name = cp.target_vacancy AND cp.user_id = vt.user_id
            WHERE vt.user_id = ?
            ORDER BY vt.applied_date DESC, vt.id DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function updateTrackingStatus($id, $userId, $status, $stage = '', $notes = '', $appliedDate = null)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $validStatuses = ['Berhasil Dilamar', 'Tahap Progress', 'Gagal', 'Berhasil'];
        if (!in_array($status, $validStatuses)) {
            $status = 'Berhasil Dilamar';
        }

        $sql = "UPDATE vacancy_trackings SET status = ?, stage = ?, notes = ?, updated_at = NOW()";
        $params = [$status, $stage ?: 'Administrasi / Screening', $notes];

        if ($appliedDate) {
            $sql .= ", applied_date = ?";
            $params[] = $appliedDate;
        }

        $sql .= " WHERE id = ? AND user_id = ?";
        $params[] = $id;
        $params[] = $userId;

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function deleteTracking($id, $userId, $deleteFolder = false)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT folder_name FROM vacancy_trackings WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        $tracking = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($tracking) {
            $stmtDel = $db->prepare("DELETE FROM vacancy_trackings WHERE id = ? AND user_id = ?");
            $stmtDel->execute([$id, $userId]);

            if ($deleteFolder && !empty($tracking['folder_name'])) {
                self::deleteVacancy($tracking['folder_name']);
            }
            return true;
        }
        return false;
    }
}
