<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SkillCategory;
use App\Models\Skill;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class SkillController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $categoryModel = new SkillCategory();
        $skillModel = new Skill();
        
        $categories = $categoryModel->where('profile_id', AuthHelper::activeProfileId());
        
        foreach ($categories as $cat) {
            $cat->skills = $skillModel->where('category_id', $cat->id);
        }

        ob_start();
        require_once __DIR__ . '/../../resources/views/profile/skills.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Skills',
            'content' => $content
        ]);
    }

    public function storeCategory()
    {
        $categoryModel = new SkillCategory();
        
        $data = [
            'user_id' => AuthHelper::id(),
            'profile_id' => AuthHelper::activeProfileId(),
            'category_name' => trim($_POST['category_name'])
        ];

        if (!empty($data['category_name'])) {
            $categoryModel->create($data);
            $_SESSION['success'] = "Category added successfully!";
        }
        
        $this->redirect('/profile/skills');
    }

    public function store()
    {
        $skillModel = new Skill();
        
        // Ensure category belongs to user
        $categoryModel = new SkillCategory();
        $cat = $categoryModel->find($_POST['category_id']);
        
        if ($cat && $cat->profile_id == AuthHelper::activeProfileId()) {
            $skillNames = explode(',', $_POST['skill_name']);
            foreach ($skillNames as $name) {
                if (trim($name) !== '') {
                    $skillModel->create([
                        'category_id' => $cat->id,
                        'skill_name' => trim($name),
                        'proficiency' => $_POST['proficiency'] ?? ''
                    ]);
                }
            }
            $_SESSION['success'] = "Skills added successfully!";
        }
        
        $this->redirect('/profile/skills');
    }

    public function delete($id)
    {
        $skillModel = new Skill();
        $categoryModel = new SkillCategory();
        
        $skill = $skillModel->find($id);
        if ($skill) {
            $cat = $categoryModel->find($skill->category_id);
            if ($cat && $cat->profile_id == AuthHelper::activeProfileId()) {
                $skillModel->delete($id);
                $_SESSION['success'] = "Skill deleted successfully!";
            }
        }
        $this->redirect('/profile/skills');
    }

    public function deleteCategory($id)
    {
        $categoryModel = new SkillCategory();
        $cat = $categoryModel->find($id);
        if ($cat && $cat->profile_id == AuthHelper::activeProfileId()) {
            $categoryModel->delete($id);
            $_SESSION['success'] = "Category deleted successfully!";
        }
        $this->redirect('/profile/skills');
    }
}
