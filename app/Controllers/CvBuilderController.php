<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\JobApplication;
use App\Models\CandidateProfile;
use App\Models\Education;
use App\Models\Experience;
use App\Models\ExperienceBullet;
use App\Models\Project;
use App\Models\ProjectBullet;
use App\Models\Organization;
use App\Models\OrganizationBullet;
use App\Models\SkillCategory;
use App\Models\Skill;
use App\Models\CvTemplate;
use App\Models\Certification;
use App\Models\Award;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class CvBuilderController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public static function generateCvFilename($profile, $extension = 'pdf')
    {
        $name = 'Muhammad Hafiz Batu Bara';
        
        $role = '';
        if (!empty($profile->target_vacancy)) {
            $role = str_replace(['_', '-'], ' ', $profile->target_vacancy);
        } elseif (!empty($profile->professional_title)) {
            $role = trim(explode('|', $profile->professional_title)[0]);
        } else {
            $role = 'Professional';
        }

        // Bersihkan karakter terlarang Windows filename (: * ? " < > | / \)
        $cleanRole = preg_replace('/[\/\\:*?"<>|]/', '', $role);
        $cleanRole = preg_replace('/\s+/', ' ', trim($cleanRole));

        return "CV_{$name}_{$cleanRole}." . ltrim($extension, '.');
    }

    private function resolveTarget($id)
    {
        $userId = AuthHelper::id();
        $profileModel = new CandidateProfile();
        $appModel = new JobApplication();

        // 1. Cek apakah ada query param ?profile_id=...
        if (!empty($_GET['profile_id'])) {
            $p = $profileModel->find($_GET['profile_id']);
            if ($p && $p->user_id == $userId) {
                $_SESSION['active_profile_id'] = $p->id;
                $role = $p->target_vacancy ? str_replace('_', ' ', $p->target_vacancy) : ($p->professional_title ? explode('|', $p->professional_title)[0] : 'CV Profile');
                $app = (object)[
                    'id' => $p->id,
                    'position' => trim($role),
                    'company_name' => $p->target_vacancy ? 'Target: ' . str_replace('_', ' ', $p->target_vacancy) : 'General Application',
                    'is_profile_direct' => true
                ];
                return ['application' => $app, 'profile' => $p];
            }
        }

        // 2. Cek apakah $id adalah JobApplication ID
        $application = $appModel->find($id);
        if ($application && $application->user_id == $userId) {
            $activeProfileId = AuthHelper::activeProfileId();
            $profile = $profileModel->find($activeProfileId);
            return ['application' => $application, 'profile' => $profile];
        }

        // 3. Cek apakah $id adalah CandidateProfile ID
        $profile = $profileModel->find($id);
        if ($profile && $profile->user_id == $userId) {
            $_SESSION['active_profile_id'] = $profile->id;
            $role = $profile->target_vacancy ? str_replace('_', ' ', $profile->target_vacancy) : ($profile->professional_title ? explode('|', $profile->professional_title)[0] : 'CV Profile');
            $app = (object)[
                'id' => $profile->id,
                'position' => trim($role),
                'company_name' => $profile->target_vacancy ? 'Target: ' . str_replace('_', ' ', $profile->target_vacancy) : 'General Application',
                'is_profile_direct' => true
            ];
            return ['application' => $app, 'profile' => $profile];
        }

        // 4. Fallback ke active profile
        $activeProfileId = AuthHelper::activeProfileId();
        $profile = $profileModel->find($activeProfileId);
        $role = $profile ? ($profile->target_vacancy ? str_replace('_', ' ', $profile->target_vacancy) : ($profile->professional_title ? explode('|', $profile->professional_title)[0] : 'CV Profile')) : 'CV';
        $app = (object)[
            'id' => $profile ? $profile->id : 1,
            'position' => trim($role),
            'company_name' => 'General Application',
            'is_profile_direct' => true
        ];

        return ['application' => $app, 'profile' => $profile];
    }

    public function create($id)
    {
        $target = $this->resolveTarget($id);
        $application = $target['application'];
        $profile = $target['profile'];

        if (!$profile) {
            $_SESSION['error'] = "Profil tidak ditemukan atau belum lengkap.";
            $this->redirect('/profiles/manage');
            return;
        }

        $templateModel = new CvTemplate();
        $templates = $templateModel->where('is_active', 1);

        ob_start();
        require_once __DIR__ . '/../../resources/views/cv-builder/index.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'CV Builder - ' . ($application->position ?? 'Resume'),
            'content' => $content
        ]);
    }

    public function preview($id)
    {
        $target = $this->resolveTarget($id);
        $profile = $target['profile'];
        if (!$profile) die("Profile incomplete.");

        $profileId = $profile->id;

        $educations = (new Education())->where('profile_id', $profileId);
        
        $experiences = (new Experience())->where('profile_id', $profileId);
        $expBulletModel = new ExperienceBullet();
        foreach($experiences as $exp) $exp->bullets = $expBulletModel->where('experience_id', $exp->id);

        $projects = (new Project())->where('profile_id', $profileId);
        $projBulletModel = new ProjectBullet();
        foreach($projects as $proj) $proj->bullets = $projBulletModel->where('project_id', $proj->id);

        $organizations = (new Organization())->where('profile_id', $profileId);
        $orgBulletModel = new OrganizationBullet();
        foreach($organizations as $org) $org->bullets = $orgBulletModel->where('organization_id', $org->id);

        $categories = (new SkillCategory())->where('profile_id', $profileId);
        $skillModel = new Skill();
        $skills = [];
        foreach($categories as $cat) {
            $catSkills = $skillModel->where('category_id', $cat->id);
            if(count($catSkills) > 0) {
                $skills[$cat->category_name] = array_map(fn($s) => $s->skill_name, $catSkills);
            }
        }

        $certifications = (new Certification())->where('profile_id', $profileId);
        $awards = (new Award())->where('profile_id', $profileId);

        // Get Configuration from POST
        $config = [
            'sections' => $_POST['sections'] ?? ['summary', 'skills', 'experience', 'projects', 'education', 'certifications', 'awards', 'organizations'],
            'template_id' => $_POST['template_id'] ?? 1
        ];

        $templateFile = __DIR__ . '/../../resources/templates/cv/harvard.php';
        $candidate = $profile;
        
        require $templateFile;
    }

    private function getCvHtml($profile)
    {
        $profileId = $profile->id;

        $educations = (new Education())->where('profile_id', $profileId);
        
        $experiences = (new Experience())->where('profile_id', $profileId);
        $expBulletModel = new ExperienceBullet();
        foreach($experiences as $exp) $exp->bullets = $expBulletModel->where('experience_id', $exp->id);

        $projects = (new Project())->where('profile_id', $profileId);
        $projBulletModel = new ProjectBullet();
        foreach($projects as $proj) $proj->bullets = $projBulletModel->where('project_id', $proj->id);

        $organizations = (new Organization())->where('profile_id', $profileId);
        $orgBulletModel = new OrganizationBullet();
        foreach($organizations as $org) $org->bullets = $orgBulletModel->where('organization_id', $org->id);

        $categories = (new SkillCategory())->where('profile_id', $profileId);
        $skillModel = new Skill();
        $skills = [];
        foreach($categories as $cat) {
            $catSkills = $skillModel->where('category_id', $cat->id);
            if(count($catSkills) > 0) {
                $skills[$cat->category_name] = array_map(fn($s) => $s->skill_name, $catSkills);
            }
        }

        $certifications = (new Certification())->where('profile_id', $profileId);
        $awards = (new Award())->where('profile_id', $profileId);

        // All sections enabled for export
        $config = ['sections' => ['summary', 'skills', 'experience', 'projects', 'education', 'certifications', 'awards', 'organizations']];

        $candidate = $profile;
        $templateFile = __DIR__ . '/../../resources/templates/cv/harvard.php';
        
        ob_start();
        require $templateFile;
        return ob_get_clean();
    }

    public function exportPdf($id)
    {
        $target = $this->resolveTarget($id);
        $profile = $target['profile'];

        if (!$profile) {
            $_SESSION['error'] = "Profil tidak ditemukan.";
            $this->redirect('/profiles/manage');
            return;
        }

        $filename = self::generateCvFilename($profile, 'pdf');
        $html = $this->getCvHtml($profile);

        $export = new \App\Services\ExportService();
        $export->exportPdf($html, $filename);
    }

    public function createByProfile($profileId)
    {
        $_SESSION['active_profile_id'] = $profileId;
        $_GET['profile_id'] = $profileId;
        return $this->create($profileId);
    }

    public function exportProfilePdf($profileId)
    {
        $profile = (new \App\Models\CandidateProfile())->find($profileId);
        if (!$profile || $profile->user_id != \App\Helpers\AuthHelper::id()) {
            $_SESSION['error'] = "Profil tidak ditemukan.";
            $this->redirect('/profiles/manage');
            return;
        }

        $filename = self::generateCvFilename($profile, 'pdf');
        $html = $this->getCvHtml($profile);

        $export = new \App\Services\ExportService();
        $export->exportPdf($html, $filename);
    }
}
