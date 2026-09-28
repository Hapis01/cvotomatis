<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Project;
use App\Models\ProjectBullet;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class ProjectController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $projectModel = new Project();
        $bulletModel = new ProjectBullet();
        
        $projects = $projectModel->where('profile_id', AuthHelper::activeProfileId());
        
        foreach ($projects as $proj) {
            $proj->bullets = $bulletModel->where('project_id', $proj->id);
        }

        ob_start();
        require_once __DIR__ . '/../../resources/views/profile/projects.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Projects',
            'content' => $content
        ]);
    }

    public function store()
    {
        $projectModel = new Project();
        $bulletModel = new ProjectBullet();
        
        $data = [
            'user_id' => AuthHelper::id(),
            'profile_id' => AuthHelper::activeProfileId(),
            'project_name' => $_POST['project_name'],
            'role' => $_POST['role'],
            'organization_company' => $_POST['organization_company'],
            'start_date' => $_POST['start_date'] ?: null,
            'end_date' => $_POST['end_date'] ?: null,
            'project_url' => $_POST['project_url'],
            'technologies' => $_POST['technologies']
        ];

        if (!empty($_POST['id'])) {
            $projId = $_POST['id'];
            $projectModel->update($projId, $data);
            $_SESSION['success'] = "Project updated successfully!";
            
            $existingBullets = $bulletModel->where('project_id', $projId);
            foreach ($existingBullets as $b) {
                $bulletModel->delete($b->id);
            }
        } else {
            $projId = $projectModel->create($data);
            $_SESSION['success'] = "Project added successfully!";
        }

        // Insert bullets
        if (isset($_POST['bullets']) && is_array($_POST['bullets'])) {
            foreach ($_POST['bullets'] as $index => $bulletText) {
                if (trim($bulletText) !== '') {
                    $bulletModel->create([
                        'project_id' => $projId,
                        'bullet_text' => trim($bulletText),
                        'order_num' => $index
                    ]);
                }
            }
        }

        $this->redirect('/profile/projects');
    }

    public function delete($id)
    {
        $projectModel = new Project();
        $item = $projectModel->find($id);
        if ($item && $item->profile_id == AuthHelper::activeProfileId()) {
            $projectModel->delete($id);
            $_SESSION['success'] = "Project deleted successfully!";
        } else {
            $_SESSION['error'] = "Unauthorized action.";
        }
        $this->redirect('/profile/projects');
    }
}
