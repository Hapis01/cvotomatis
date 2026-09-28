<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) . ' - ' : '' ?><?= env('APP_NAME') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #343a40; color: #fff; border-left: 3px solid #0d6efd; }
        .main-content { padding: 20px; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,.08); }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar" style="width: 250px;">
        <div class="p-4 mb-3">
            <h4 class="m-0 text-white"><i class="fas fa-file-alt me-2"></i>CV Builder</h4>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
        <a href="<?= base_url('profile') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'profile') !== false && strpos($_SERVER['REQUEST_URI'], 'profiles/manage') === false ? 'active' : '' ?>">
            <i class="fas fa-user me-2"></i> Candidate Profile
        </a>
        <!-- Sub-menus for profile -->
        <div class="ms-3" style="font-size: 0.9em;">
            <a href="<?= base_url('profiles/manage') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'profiles/manage') !== false ? 'text-primary' : '' ?>"><i class="fas fa-users-cog me-2"></i> Manage Profiles</a>
            <hr class="border-secondary my-1">
            <a href="<?= base_url('profile/education') ?>"><i class="fas fa-graduation-cap me-2"></i> Education</a>
            <a href="<?= base_url('profile/experience') ?>"><i class="fas fa-briefcase me-2"></i> Experience</a>
            <a href="<?= base_url('profile/projects') ?>"><i class="fas fa-project-diagram me-2"></i> Projects</a>
            <a href="<?= base_url('profile/organizations') ?>"><i class="fas fa-users me-2"></i> Organizations</a>
            <a href="<?= base_url('profile/certifications') ?>"><i class="fas fa-certificate me-2"></i> Certifications & Awards</a>
            <a href="<?= base_url('profile/skills') ?>"><i class="fas fa-cogs me-2"></i> Skills</a>
        </div>
        
        <a href="<?= base_url('vacancies') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'vacancies') !== false ? 'active' : '' ?> mt-3">
            <i class="fas fa-file-import me-2"></i> Ekstraksi Lowongan (MD)
        </a>
        <a href="<?= base_url('applications') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'applications') !== false ? 'active' : '' ?>">
            <i class="fas fa-paper-plane me-2"></i> Job Applications
        </a>
        <a href="<?= base_url('templates') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'templates') !== false ? 'active' : '' ?>">
            <i class="fas fa-layer-group me-2"></i> Templates
        </a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <nav class="navbar navbar-expand-lg navbar-light px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1"><?= isset($title) ? e($title) : 'Dashboard' ?></span>
                <div class="d-flex">
                    <?php
                        $allProfiles = (new \App\Models\CandidateProfile())->where('user_id', \App\Helpers\AuthHelper::id());
                        $activeProfileId = \App\Helpers\AuthHelper::activeProfileId();
                        $activeProfileTitle = 'Select Profile';
                        foreach($allProfiles as $p) {
                            if($p->id == $activeProfileId) {
                                $activeProfileTitle = $p->professional_title ? explode('|', $p->professional_title)[0] : $p->full_name;
                            }
                        }
                    ?>
                    <div class="dropdown me-3">
                        <button class="btn btn-outline-primary dropdown-toggle bg-white shadow-sm" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-briefcase me-1"></i> <span class="fw-semibold"><?= e(trim($activeProfileTitle)) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 320px; max-height: 500px; overflow-y: auto;">
                            <?php 
                                $masters = [];
                                $subProfiles = [];
                                foreach($allProfiles as $p) {
                                    if(empty($p->parent_id)) $masters[] = $p;
                                    else $subProfiles[] = $p;
                                }
                            ?>
                            <li class="dropdown-header text-uppercase small fw-bold text-primary">
                                <i class="fas fa-shield-alt me-1"></i> Profil Induk (Master)
                            </li>
                            <?php foreach($masters as $p): ?>
                                <?php 
                                    $roleTag = $p->professional_title ? trim(explode('|', $p->professional_title)[0]) : 'Profile #' . $p->id;
                                    $isActive = ($p->id == $activeProfileId);
                                ?>
                                <li>
                                    <a class="dropdown-item py-2 <?= $isActive ? 'active' : '' ?>" href="<?= base_url('profiles/switch/'.$p->id) ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold"><?= e($roleTag) ?></span>
                                            <?php if($isActive): ?>
                                                <span class="badge bg-light text-primary ms-2"><i class="fas fa-check"></i> Aktif</span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="<?= $isActive ? 'text-white-50' : 'text-muted' ?> d-block"><?= e($p->full_name) ?> (Original)</small>
                                    </a>
                                </li>
                            <?php endforeach; ?>

                            <?php if(!empty($subProfiles)): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header text-uppercase small fw-bold text-info">
                                    <i class="fas fa-bullseye me-1"></i> Sub-Profil Khusus Lowongan
                                </li>
                                <?php foreach($subProfiles as $p): ?>
                                    <?php 
                                        $roleTag = $p->professional_title ?: 'Sub-Profile #' . $p->id;
                                        $isActive = ($p->id == $activeProfileId);
                                    ?>
                                    <li>
                                        <a class="dropdown-item py-2 <?= $isActive ? 'active' : '' ?>" href="<?= base_url('profiles/switch/'.$p->id) ?>">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-semibold text-truncate" style="max-width: 220px;"><?= e($roleTag) ?></span>
                                                <?php if($isActive): ?>
                                                    <span class="badge bg-light text-primary ms-2"><i class="fas fa-check"></i> Aktif</span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if($p->target_vacancy): ?>
                                                <small class="<?= $isActive ? 'text-white-50' : 'text-primary' ?> d-block">
                                                    <i class="fas fa-briefcase me-1"></i><?= e($p->target_vacancy) ?>
                                                </small>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-primary" href="<?= base_url('profiles/manage') ?>"><i class="fas fa-sliders-h me-1"></i> Kelola Profil & Sub-Profil</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> <?= e(\App\Helpers\AuthHelper::user()->name ?? 'User') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('settings') ?>">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="main-content">
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= e($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= e($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- View Content Injection -->
            <?= $content ?? '' ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
