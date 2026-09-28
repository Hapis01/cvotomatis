<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\JobApplication;
use App\Models\CandidateProfile;
use App\Models\CoverLetter;
use App\Models\LetterTemplate;
use App\Models\Signature;
use App\Models\Letterhead;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class CoverLetterController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function create($appId)
    {
        $appModel = new JobApplication();
        $application = $appModel->find($appId);

        if (!$application || $application->user_id != AuthHelper::id()) {
            $_SESSION['error'] = "Application not found.";
            $this->redirect('/applications');
        }

        $templateModel = new LetterTemplate();
        $templates = $templateModel->where('is_active', 1);
        
        $profile = (new CandidateProfile())->where('user_id', AuthHelper::id())[0] ?? null;
        
        $coverLetterModel = new CoverLetter();
        $existing = current($coverLetterModel->where('application_id', $appId));

        $defaultContent = $this->generateSmartDraft($profile, $application);
        $letterContent = $existing ? $existing->letter_content : $defaultContent;
        $activeTemplate = $existing ? $existing->template_id : ($templates[0]->id ?? 1);
        
        $config = $existing && $existing->configuration_json ? json_decode($existing->configuration_json, true) : [];
        $letterTitle = $config['letter_title'] ?? 'SURAT LAMARAN KERJA';
        $themeColor = $config['theme_color'] ?? '#008b8b';


        ob_start();
        require_once __DIR__ . '/../../resources/views/cover-letter/index.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Cover Letter Builder',
            'content' => $content
        ]);
    }
    
    private function generateSmartDraft($profile, $application)
    {
        $draft = "Dear " . ($application->recruiter_name ?: 'Hiring Manager') . ",\n\n";
        $draft .= "I am writing to express my strong interest in the " . $application->position . " position at " . $application->company_name . ". ";
        $draft .= "As a professional with experience in " . ($profile->professional_title ?? 'my field') . ", I believe my skills and background make me an excellent fit for this role.\n\n";
        $draft .= "In my recent roles, I have developed a deep understanding of the industry and a proven ability to deliver results. ";
        $draft .= "I am particularly drawn to " . $application->company_name . " because of your innovative approach and commitment to excellence.\n\n";
        $draft .= "I would welcome the opportunity to discuss how my qualifications align with your needs. I have attached my resume for your review. Thank you for your time and consideration.\n\n";
        $draft .= "Sincerely,\n\n" . ($profile->full_name ?? 'Candidate Name');
        
        return $draft;
    }

    public function preview($appId)
    {
        $userId = AuthHelper::id();
        $profile = (new CandidateProfile())->where('user_id', $userId)[0] ?? null;
        $application = (new JobApplication())->find($appId);
        
        $content = $_POST['letter_content'] ?? '';
        $templateId = $_POST['template_id'] ?? 1;

        $sigModel = new Signature();
        $headModel = new Letterhead();
        $signature = current($sigModel->where('user_id', $userId));
        $letterhead = current($headModel->where('user_id', $userId));
        
        $letterTitle = $_POST['letter_title'] ?? 'SURAT LAMARAN KERJA';
        $themeColor = $_POST['theme_color'] ?? '#008b8b'; // default teal

        
        // Template variable system replacement
        $vars = [
            '{{candidate_name}}' => $profile->full_name ?? '',
            '{{candidate_email}}' => $profile->email ?? '',
            '{{candidate_phone}}' => $profile->phone ?? '',
            '{{candidate_address}}' => $profile->address ?? '',
            '{{company_name}}' => $application->company_name ?? '',
            '{{company_address}}' => $application->company_address ?? '',
            '{{position}}' => $application->position ?? '',
            '{{recruiter_name}}' => $application->recruiter_name ?: 'Hiring Manager',
            '{{application_date}}' => date('F j, Y', strtotime($application->application_date)),
        ];

        $content = str_replace(array_keys($vars), array_values($vars), $content);
        $content = nl2br(e($content));

        // In a full implementation, select template file dynamically based on $templateId
        $templateFile = __DIR__ . '/../../resources/templates/cover-letter/formal.php';
        
        require $templateFile;
    }

    public function save($appId)
    {
        $coverLetterModel = new CoverLetter();
        $existing = current($coverLetterModel->where('application_id', $appId));
        
        $data = [
            'application_id' => $appId,
            'user_id' => AuthHelper::id(),
            'template_id' => $_POST['template_id'] ?? 1,
            'letter_content' => $_POST['letter_content'],
            'configuration_json' => json_encode([
                'letter_title' => $_POST['letter_title'] ?? 'SURAT LAMARAN KERJA',
                'theme_color' => $_POST['theme_color'] ?? '#008b8b'
            ])
        ];
        
        if ($existing) {
            $coverLetterModel->update($existing->id, $data);
        } else {
            $coverLetterModel->create($data);
        }
        
        $_SESSION['success'] = "Cover letter saved successfully.";
        $this->redirect('/cover-letter/create/' . $appId);
    }

    private function getLetterHtml($appId)
    {
        $userId = AuthHelper::id();
        $profile = (new CandidateProfile())->where('user_id', $userId)[0] ?? null;
        $application = (new JobApplication())->find($appId);
        
        $coverLetterModel = new CoverLetter();
        $existing = current($coverLetterModel->where('application_id', $appId));
        
        $content = $existing ? $existing->letter_content : $this->generateSmartDraft($profile, $application);
        $config = $existing && $existing->configuration_json ? json_decode($existing->configuration_json, true) : [];
        
        $letterTitle = $config['letter_title'] ?? 'SURAT LAMARAN KERJA';
        $themeColor = $config['theme_color'] ?? '#008b8b';
        
        $sigModel = new Signature();
        $headModel = new Letterhead();
        $signature = current($sigModel->where('user_id', $userId));
        $letterhead = current($headModel->where('user_id', $userId));
        
        $vars = [
            '{{candidate_name}}' => $profile->full_name ?? '',
            '{{candidate_email}}' => $profile->email ?? '',
            '{{candidate_phone}}' => $profile->phone ?? '',
            '{{candidate_address}}' => $profile->address ?? '',
            '{{company_name}}' => $application->company_name ?? '',
            '{{company_address}}' => $application->company_address ?? '',
            '{{position}}' => $application->position ?? '',
            '{{recruiter_name}}' => $application->recruiter_name ?: 'Hiring Manager',
            '{{application_date}}' => date('F j, Y', strtotime($application->application_date)),
        ];

        $content = str_replace(array_keys($vars), array_values($vars), $content);
        $content = nl2br(e($content));

        $templateFile = __DIR__ . '/../../resources/templates/cover-letter/formal.php';
        
        ob_start();
        require $templateFile;
        return ob_get_clean();
    }

    public function exportPdf($appId)
    {
        $html = $this->getLetterHtml($appId);
        $export = new \App\Services\ExportService();
        $export->exportPdf($html, 'Cover_Letter_' . $appId . '.pdf');
    }
}
