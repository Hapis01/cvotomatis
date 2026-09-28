<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Education;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class EducationController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $educationModel = new Education();
        $educations = $educationModel->where('profile_id', AuthHelper::activeProfileId());

        ob_start();
        require_once __DIR__ . '/../../resources/views/profile/education.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Education',
            'content' => $content
        ]);
    }

    public function store()
    {
        $educationModel = new Education();
        
        $data = [
            'user_id' => AuthHelper::id(),
            'profile_id' => AuthHelper::activeProfileId(),
            'institution' => $_POST['institution'],
            'degree' => $_POST['degree'],
            'major' => $_POST['major'],
            'city' => $_POST['city'],
            'start_date' => $_POST['start_date'] ?: null,
            'end_date' => $_POST['end_date'] ?: null,
            'gpa' => $_POST['gpa'],
            'accreditation' => $_POST['accreditation'],
            'description' => $_POST['description']
        ];

        if (!empty($_POST['id'])) {
            $educationModel->update($_POST['id'], $data);
            $_SESSION['success'] = "Education updated successfully!";
        } else {
            $educationModel->create($data);
            $_SESSION['success'] = "Education added successfully!";
        }

        $this->redirect('/profile/education');
    }

    public function delete($id)
    {
        $educationModel = new Education();
        // Check ownership
        $item = $educationModel->find($id);
        if ($item && $item->profile_id == AuthHelper::activeProfileId()) {
            $educationModel->delete($id);
            $_SESSION['success'] = "Education deleted successfully!";
        } else {
            $_SESSION['error'] = "Unauthorized action.";
        }
        $this->redirect('/profile/education');
    }
}
