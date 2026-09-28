<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\JobApplication;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class ApplicationController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $appModel = new JobApplication();
        // Get applications descending
        $applications = array_reverse($appModel->where('user_id', AuthHelper::id()));

        ob_start();
        require_once __DIR__ . '/../../resources/views/applications/index.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Job Applications',
            'content' => $content
        ]);
    }

    public function create()
    {
        ob_start();
        require_once __DIR__ . '/../../resources/views/applications/create.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Create Job Application',
            'content' => $content
        ]);
    }

    public function store()
    {
        $appModel = new JobApplication();
        
        $data = [
            'user_id' => AuthHelper::id(),
            'company_name' => trim($_POST['company_name']),
            'company_address' => trim($_POST['company_address'] ?? ''),
            'position' => trim($_POST['position']),
            'department' => trim($_POST['department'] ?? ''),
            'recruiter_name' => trim($_POST['recruiter_name'] ?? ''),
            'recruiter_title' => trim($_POST['recruiter_title'] ?? ''),
            'application_date' => $_POST['application_date'] ?: date('Y-m-d'),
            'job_description' => trim($_POST['job_description'] ?? ''),
            'job_requirements' => trim($_POST['job_requirements'] ?? ''),
            'custom_notes' => trim($_POST['custom_notes'] ?? '')
        ];

        $appModel->create($data);
        $_SESSION['success'] = "Job application created successfully!";
        $this->redirect('/applications');
    }

    public function edit($id)
    {
        $appModel = new JobApplication();
        $application = $appModel->find($id);

        if (!$application || $application->user_id != AuthHelper::id()) {
            $_SESSION['error'] = "Application not found.";
            $this->redirect('/applications');
        }

        ob_start();
        require_once __DIR__ . '/../../resources/views/applications/edit.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Edit Job Application',
            'content' => $content
        ]);
    }

    public function update($id)
    {
        $appModel = new JobApplication();
        $application = $appModel->find($id);

        if (!$application || $application->user_id != AuthHelper::id()) {
            $_SESSION['error'] = "Unauthorized.";
            $this->redirect('/applications');
        }

        $data = [
            'company_name' => trim($_POST['company_name']),
            'company_address' => trim($_POST['company_address'] ?? ''),
            'position' => trim($_POST['position']),
            'department' => trim($_POST['department'] ?? ''),
            'recruiter_name' => trim($_POST['recruiter_name'] ?? ''),
            'recruiter_title' => trim($_POST['recruiter_title'] ?? ''),
            'application_date' => $_POST['application_date'] ?: date('Y-m-d'),
            'job_description' => trim($_POST['job_description'] ?? ''),
            'job_requirements' => trim($_POST['job_requirements'] ?? ''),
            'custom_notes' => trim($_POST['custom_notes'] ?? '')
        ];

        $appModel->update($id, $data);
        $_SESSION['success'] = "Job application updated successfully!";
        $this->redirect('/applications');
    }

    public function duplicate($id)
    {
        $appModel = new JobApplication();
        $application = $appModel->find($id);

        if ($application && $application->user_id == AuthHelper::id()) {
            $data = (array) $application;
            unset($data['id']);
            unset($data['created_at']);
            unset($data['updated_at']);
            
            $data['position'] = $data['position'] . ' (Copy)';
            $appModel->create($data);
            
            $_SESSION['success'] = "Application duplicated successfully!";
        }
        $this->redirect('/applications');
    }

    public function delete($id)
    {
        $appModel = new JobApplication();
        $application = $appModel->find($id);

        if ($application && $application->user_id == AuthHelper::id()) {
            $appModel->delete($id);
            $_SESSION['success'] = "Job application deleted successfully!";
        }
        $this->redirect('/applications');
    }
}
