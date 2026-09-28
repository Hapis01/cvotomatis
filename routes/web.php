<?php

/** @var \App\Core\Router $router */

$router->get('/', function() {
    // Redirect to login if not logged in
    $base = rtrim(env('APP_URL'), '/');
    header("Location: {$base}/login");
    exit;
});

// Auth Routes
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [\App\Controllers\AuthController::class, 'processLogin']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);

// Dashboard
$router->get('/dashboard', [\App\Controllers\DashboardController::class, 'index']);

// Profile
$router->get('/profile', [\App\Controllers\ProfileController::class, 'index']);
$router->post('/profile/update', [\App\Controllers\ProfileController::class, 'update']);
$router->get('/profiles/manage', [\App\Controllers\ProfileController::class, 'manage']);
$router->post('/profiles/store-new', [\App\Controllers\ProfileController::class, 'store_new']);
$router->post('/profiles/clone', [\App\Controllers\ProfileController::class, 'clone_profile']);
$router->get('/profiles/switch/{id}', [\App\Controllers\ProfileController::class, 'switch_profile']);
$router->post('/profiles/delete/{id}', [\App\Controllers\ProfileController::class, 'delete_profile']);

// Profile - Education
$router->get('/profile/education', [\App\Controllers\EducationController::class, 'index']);
$router->post('/profile/education/store', [\App\Controllers\EducationController::class, 'store']);
$router->post('/profile/education/delete/{id}', [\App\Controllers\EducationController::class, 'delete']);

// Profile - Experience
$router->get('/profile/experience', [\App\Controllers\ExperienceController::class, 'index']);
$router->post('/profile/experience/store', [\App\Controllers\ExperienceController::class, 'store']);
$router->post('/profile/experience/delete/{id}', [\App\Controllers\ExperienceController::class, 'delete']);

// Profile - Projects
$router->get('/profile/projects', [\App\Controllers\ProjectController::class, 'index']);
$router->post('/profile/projects/store', [\App\Controllers\ProjectController::class, 'store']);
$router->post('/profile/projects/delete/{id}', [\App\Controllers\ProjectController::class, 'delete']);

// Profile - Organizations
$router->get('/profile/organizations', [\App\Controllers\OrganizationController::class, 'index']);
$router->post('/profile/organizations/store', [\App\Controllers\OrganizationController::class, 'store']);
$router->post('/profile/organizations/delete/{id}', [\App\Controllers\OrganizationController::class, 'delete']);

// Profile - Skills
$router->get('/profile/skills', [\App\Controllers\SkillController::class, 'index']);
$router->post('/profile/skills/store', [\App\Controllers\SkillController::class, 'store']);
$router->post('/profile/skills/store-category', [\App\Controllers\SkillController::class, 'storeCategory']);
$router->post('/profile/skills/delete/{id}', [\App\Controllers\SkillController::class, 'delete']);
$router->post('/profile/skills/delete-category/{id}', [\App\Controllers\SkillController::class, 'deleteCategory']);

// Profile - Certifications & Awards
$router->get('/profile/certifications', [\App\Controllers\CertificationController::class, 'index']);
$router->post('/profile/certifications/store-cert', [\App\Controllers\CertificationController::class, 'storeCert']);
$router->post('/profile/certifications/delete-cert/{id}', [\App\Controllers\CertificationController::class, 'deleteCert']);
$router->post('/profile/certifications/store-award', [\App\Controllers\CertificationController::class, 'storeAward']);
$router->post('/profile/certifications/delete-award/{id}', [\App\Controllers\CertificationController::class, 'deleteAward']);

// Applications
$router->get('/applications', [\App\Controllers\ApplicationController::class, 'index']);
$router->get('/applications/create', [\App\Controllers\ApplicationController::class, 'create']);
$router->post('/applications/store', [\App\Controllers\ApplicationController::class, 'store']);
$router->get('/applications/edit/{id}', [\App\Controllers\ApplicationController::class, 'edit']);
$router->post('/applications/update/{id}', [\App\Controllers\ApplicationController::class, 'update']);
$router->post('/applications/duplicate/{id}', [\App\Controllers\ApplicationController::class, 'duplicate']);
$router->post('/applications/delete/{id}', [\App\Controllers\ApplicationController::class, 'delete']);

// Vacancies / Input Lowongan (PDF / Image OCR / Teks -> MD)
$router->get('/vacancies', [\App\Controllers\VacancyController::class, 'index']);
$router->get('/vacancies/create', [\App\Controllers\VacancyController::class, 'create']);
$router->post('/vacancies/store', [\App\Controllers\VacancyController::class, 'store']);
$router->get('/vacancies/show', [\App\Controllers\VacancyController::class, 'show']);
$router->get('/vacancies/download', [\App\Controllers\VacancyController::class, 'download']);
$router->get('/vacancies/source', [\App\Controllers\VacancyController::class, 'downloadSource']);
$router->post('/vacancies/delete', [\App\Controllers\VacancyController::class, 'delete']);
$router->post('/vacancies/update-tracking', [\App\Controllers\VacancyController::class, 'updateTracking']);
$router->post('/vacancies/delete-tracking', [\App\Controllers\VacancyController::class, 'deleteTracking']);

// CV Builder
$router->get('/cv/create/{id}', [\App\Controllers\CvBuilderController::class, 'create']);
$router->post('/cv/preview/{id}', [\App\Controllers\CvBuilderController::class, 'preview']);
$router->get('/cv/export/pdf/{id}', [\App\Controllers\CvBuilderController::class, 'exportPdf']);
$router->get('/cv/profile/{profileId}', [\App\Controllers\CvBuilderController::class, 'createByProfile']);
$router->get('/cv/export-profile/pdf/{profileId}', [\App\Controllers\CvBuilderController::class, 'exportProfilePdf']);

// Cover Letter Builder
$router->get('/cover-letter/create/{id}', [\App\Controllers\CoverLetterController::class, 'create']);
$router->post('/cover-letter/preview/{id}', [\App\Controllers\CoverLetterController::class, 'preview']);
$router->post('/cover-letter/save/{id}', [\App\Controllers\CoverLetterController::class, 'save']);
$router->get('/cover-letter/export/pdf/{id}', [\App\Controllers\CoverLetterController::class, 'exportPdf']);

// Settings
$router->get('/settings', [\App\Controllers\SettingsController::class, 'index']);
$router->post('/settings/signature', [\App\Controllers\SettingsController::class, 'uploadSignature']);
$router->post('/settings/letterhead', [\App\Controllers\SettingsController::class, 'uploadLetterhead']);
