<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CandidateProfile;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class ProfileController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $profileModel = new CandidateProfile();
        $profileId = AuthHelper::activeProfileId();
        $profile = $profileId ? $profileModel->find($profileId) : null;

        ob_start();
        require_once __DIR__ . '/../../resources/views/profile/index.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Candidate Profile',
            'content' => $content
        ]);
    }

    public function update()
    {
        $profileModel = new CandidateProfile();
        $profileId = AuthHelper::activeProfileId();
        
        $data = [
            'user_id' => AuthHelper::id(),
            'full_name' => $_POST['full_name'],
            'professional_title' => $_POST['professional_title'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'province' => $_POST['province'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'portfolio_url' => $_POST['portfolio_url'],
            'linkedin_url' => $_POST['linkedin_url'],
            'github_url' => $_POST['github_url'],
            'professional_summary' => $_POST['professional_summary']
        ];

        // Handle Photo Upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../storage/uploads/photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileInfo = pathinfo($_FILES['photo']['name']);
            $extension = strtolower($fileInfo['extension']);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($extension, $allowedExtensions)) {
                $newFilename = 'photo_' . AuthHelper::id() . '_' . time() . '.' . $extension;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $newFilename)) {
                    $data['photo_path'] = 'storage/uploads/photos/' . $newFilename;
                }
            }
        }

        if ($profileId) {
            $profileModel->update($profileId, $data);
            $_SESSION['success'] = "Profile updated successfully!";
        } else {
            $id = $profileModel->create($data);
            $_SESSION['active_profile_id'] = $id;
            $_SESSION['success'] = "Profile created successfully!";
        }

        $this->redirect('/profile');
    }

    public function manage()
    {
        $profileModel = new CandidateProfile();
        $profiles = $profileModel->where('user_id', AuthHelper::id());

        ob_start();
        require_once __DIR__ . '/../../resources/views/profile/manage.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Manage Profiles',
            'content' => $content
        ]);
    }

    public function switch_profile($id)
    {
        $profileModel = new CandidateProfile();
        $profile = $profileModel->find($id);
        
        if ($profile && $profile->user_id == AuthHelper::id()) {
            $_SESSION['active_profile_id'] = $profile->id;
            $_SESSION['success'] = "Switched to profile: " . $profile->full_name;
        } else {
            $_SESSION['error'] = "Profile not found or unauthorized.";
        }
        
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/dashboard');
    }

    public function store_new()
    {
        $profileModel = new CandidateProfile();
        $data = [
            'user_id' => AuthHelper::id(),
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'] ?? AuthHelper::user()->email
        ];
        
        $id = $profileModel->create($data);
        $_SESSION['active_profile_id'] = $id;
        $_SESSION['success'] = "New profile created and activated!";
        
        $this->redirect('/profile');
    }

    public function clone_profile()
    {
        $sourceId = $_POST['source_profile_id'] ?? null;
        $newTitle = trim($_POST['professional_title'] ?? '');
        $targetVacancy = trim($_POST['target_vacancy'] ?? '');
        $userId = AuthHelper::id();

        if (!$sourceId) {
            $_SESSION['error'] = "Pilih profil induk yang ingin diduplikasi.";
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/profiles/manage');
            return;
        }

        try {
            $newId = \App\Services\ProfileClonerService::cloneProfile($sourceId, $userId, $newTitle, $targetVacancy);
            $_SESSION['active_profile_id'] = $newId;
            $_SESSION['success'] = "Sub-Profil berhasil dibuat! Kamu sekarang dapat mengubah data khusus untuk lowongan ini tanpa mengubah profil induk asli.";
            
            $this->redirect('/profile');
        } catch (\Exception $e) {
            $_SESSION['error'] = "Gagal membuat sub-profil: " . $e->getMessage();
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/profiles/manage');
        }
    }

    public function delete_profile($id)
    {
        $profileModel = new CandidateProfile();
        $profile = $profileModel->find($id);
        
        if ($profile && $profile->user_id == AuthHelper::id()) {
            $db = \App\Core\Database::getInstance()->getConnection();
            // Bersihkan data terkait
            $db->exec("DELETE b FROM experience_bullets b JOIN experiences e ON b.experience_id=e.id WHERE e.profile_id = $id");
            $db->exec("DELETE FROM experiences WHERE profile_id = $id");
            $db->exec("DELETE b FROM project_bullets b JOIN projects p ON b.project_id=p.id WHERE p.profile_id = $id");
            $db->exec("DELETE FROM projects WHERE profile_id = $id");
            $db->exec("DELETE s FROM skills s JOIN skill_categories c ON s.category_id=c.id WHERE c.profile_id = $id");
            $db->exec("DELETE FROM skill_categories WHERE profile_id = $id");
            $db->exec("DELETE FROM certifications WHERE profile_id = $id");
            $db->exec("DELETE FROM awards WHERE profile_id = $id");
            $db->exec("DELETE b FROM organization_bullets b JOIN organizations o ON b.organization_id=o.id WHERE o.profile_id = $id");
            $db->exec("DELETE FROM organizations WHERE profile_id = $id");
            $db->exec("DELETE FROM educations WHERE profile_id = $id");
            
            $profileModel->delete($id);
            $_SESSION['success'] = "Profil dan seluruh datanya berhasil dihapus.";
            
            if (AuthHelper::activeProfileId() == $id) {
                unset($_SESSION['active_profile_id']);
            }
        } else {
            $_SESSION['error'] = "Unauthorized.";
        }
        
        $this->redirect('/profiles/manage');
    }
}
