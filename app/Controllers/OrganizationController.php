<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Organization;
use App\Models\OrganizationBullet;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class OrganizationController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $orgModel = new Organization();
        $bulletModel = new OrganizationBullet();
        
        $organizations = $orgModel->where('profile_id', AuthHelper::activeProfileId());
        
        foreach ($organizations as $org) {
            $org->bullets = $bulletModel->where('organization_id', $org->id);
        }

        ob_start();
        require_once __DIR__ . '/../../resources/views/profile/organizations.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Organizations',
            'content' => $content
        ]);
    }

    public function store()
    {
        $orgModel = new Organization();
        $bulletModel = new OrganizationBullet();
        
        $data = [
            'user_id' => AuthHelper::id(),
            'profile_id' => AuthHelper::activeProfileId(),
            'organization_name' => $_POST['organization_name'],
            'position' => $_POST['position'],
            'division' => $_POST['division'],
            'location' => $_POST['location'],
            'start_date' => $_POST['start_date'] ?: null,
            'end_date' => $_POST['end_date'] ?: null,
        ];

        if (!empty($_POST['id'])) {
            $orgId = $_POST['id'];
            $orgModel->update($orgId, $data);
            $_SESSION['success'] = "Organization updated successfully!";
            
            $existingBullets = $bulletModel->where('organization_id', $orgId);
            foreach ($existingBullets as $b) {
                $bulletModel->delete($b->id);
            }
        } else {
            $orgId = $orgModel->create($data);
            $_SESSION['success'] = "Organization added successfully!";
        }

        if (isset($_POST['bullets']) && is_array($_POST['bullets'])) {
            foreach ($_POST['bullets'] as $index => $bulletText) {
                if (trim($bulletText) !== '') {
                    $bulletModel->create([
                        'organization_id' => $orgId,
                        'bullet_text' => trim($bulletText),
                        'order_num' => $index
                    ]);
                }
            }
        }

        $this->redirect('/profile/organizations');
    }

    public function delete($id)
    {
        $orgModel = new Organization();
        $item = $orgModel->find($id);
        if ($item && $item->profile_id == AuthHelper::activeProfileId()) {
            $orgModel->delete($id);
            $_SESSION['success'] = "Organization deleted successfully!";
        }
        $this->redirect('/profile/organizations');
    }
}
