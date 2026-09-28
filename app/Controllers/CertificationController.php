<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Certification;
use App\Models\Award;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class CertificationController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $certModel = new Certification();
        $awardModel = new Award();

        $certifications = $certModel->where('profile_id', AuthHelper::activeProfileId());
        $awards = $awardModel->where('profile_id', AuthHelper::activeProfileId());

        ob_start();
        require_once __DIR__ . '/../../resources/views/profile/certifications.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Certifications & Awards',
            'content' => $content
        ]);
    }

    // --- CERTIFICATIONS ---

    public function storeCert()
    {
        $certModel = new Certification();

        $data = [
            'user_id'        => AuthHelper::id(),
            'profile_id'     => AuthHelper::activeProfileId(),
            'certification_name' => trim($_POST['certification_name']),
            'issuer'         => trim($_POST['issuer'] ?? ''),
            'issue_date'     => $_POST['issue_date'] ?: null,
            'expiration_date'=> $_POST['expiration_date'] ?: null,
            'credential_id'  => trim($_POST['credential_id'] ?? ''),
            'credential_url' => trim($_POST['credential_url'] ?? ''),
            'description'    => trim($_POST['description'] ?? '')
        ];

        if (!empty($_POST['id'])) {
            $certModel->update($_POST['id'], $data);
            $_SESSION['success'] = "Certification updated!";
        } else {
            $certModel->create($data);
            $_SESSION['success'] = "Certification added!";
        }

        $this->redirect('/profile/certifications');
    }

    public function deleteCert($id)
    {
        $certModel = new Certification();
        $item = $certModel->find($id);
        if ($item && $item->profile_id == AuthHelper::activeProfileId()) {
            $certModel->delete($id);
            $_SESSION['success'] = "Certification deleted!";
        }
        $this->redirect('/profile/certifications');
    }

    // --- AWARDS ---

    public function storeAward()
    {
        $awardModel = new Award();

        $data = [
            'user_id'      => AuthHelper::id(),
            'profile_id'   => AuthHelper::activeProfileId(),
            'title'        => trim($_POST['title']),
            'issuer'       => trim($_POST['issuer'] ?? ''),
            'date_awarded' => $_POST['date_awarded'] ?: null,
            'description'  => trim($_POST['description'] ?? '')
        ];

        if (!empty($_POST['id'])) {
            $awardModel->update($_POST['id'], $data);
            $_SESSION['success'] = "Award updated!";
        } else {
            $awardModel->create($data);
            $_SESSION['success'] = "Award added!";
        }

        $this->redirect('/profile/certifications');
    }

    public function deleteAward($id)
    {
        $awardModel = new Award();
        $item = $awardModel->find($id);
        if ($item && $item->profile_id == AuthHelper::activeProfileId()) {
            $awardModel->delete($id);
            $_SESSION['success'] = "Award deleted!";
        }
        $this->redirect('/profile/certifications');
    }
}
