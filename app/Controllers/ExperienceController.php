<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Experience;
use App\Models\ExperienceBullet;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class ExperienceController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $expModel = new Experience();
        $bulletModel = new ExperienceBullet();
        
        $experiences = $expModel->where('profile_id', AuthHelper::activeProfileId());
        
        // Attach bullets to each experience
        foreach ($experiences as $exp) {
            $exp->bullets = $bulletModel->where('experience_id', $exp->id);
        }

        ob_start();
        require_once __DIR__ . '/../../resources/views/profile/experience.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Experience',
            'content' => $content
        ]);
    }

    public function store()
    {
        $expModel = new Experience();
        $bulletModel = new ExperienceBullet();
        
        $data = [
            'user_id' => AuthHelper::id(),
            'profile_id' => AuthHelper::activeProfileId(),
            'job_title' => $_POST['job_title'],
            'company' => $_POST['company'],
            'location' => $_POST['location'],
            'start_date' => $_POST['start_date'] ?: null,
            'end_date' => !empty($_POST['current_job']) ? null : ($_POST['end_date'] ?: null),
            'current_job' => !empty($_POST['current_job']) ? 1 : 0
        ];

        if (!empty($_POST['id'])) {
            $expId = $_POST['id'];
            $expModel->update($expId, $data);
            $_SESSION['success'] = "Experience updated successfully!";
            
            // Delete existing bullets and re-insert
            $existingBullets = $bulletModel->where('experience_id', $expId);
            foreach ($existingBullets as $b) {
                $bulletModel->delete($b->id);
            }
        } else {
            $expId = $expModel->create($data);
            $_SESSION['success'] = "Experience added successfully!";
        }

        // Insert bullets
        if (isset($_POST['bullets']) && is_array($_POST['bullets'])) {
            foreach ($_POST['bullets'] as $index => $bulletText) {
                if (trim($bulletText) !== '') {
                    $bulletModel->create([
                        'experience_id' => $expId,
                        'bullet_text' => trim($bulletText),
                        'order_num' => $index
                    ]);
                }
            }
        }

        $this->redirect('/profile/experience');
    }

    public function delete($id)
    {
        $expModel = new Experience();
        $item = $expModel->find($id);
        if ($item && $item->profile_id == AuthHelper::activeProfileId()) {
            $expModel->delete($id);
            $_SESSION['success'] = "Experience deleted successfully!";
        } else {
            $_SESSION['error'] = "Unauthorized action.";
        }
        $this->redirect('/profile/experience');
    }
}
