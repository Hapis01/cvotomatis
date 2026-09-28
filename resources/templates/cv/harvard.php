<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= e($candidate->full_name) ?> - CV</title>
    <style>
        @page {
            margin: 0.5in;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10.5pt;
            line-height: 1.15;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        /* Layout utility */
        .table-layout {
            width: 100%;
            border-collapse: collapse;
        }
        .table-layout td {
            vertical-align: top;
            padding: 0;
        }
        .text-right {
            text-align: right;
        }
        .fw-bold { font-weight: bold; }
        .fst-italic { font-style: italic; }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .header-table td {
            vertical-align: top;
        }
        .name-block {
            font-size: 22pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }
        .title-block {
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .contact-info {
            font-size: 10pt;
            margin-bottom: 0;
            line-height: 1.2;
        }
        .contact-info a {
            color: #000;
            text-decoration: none;
        }
        .photo-wrapper {
            text-align: right;
            padding-right: 15px; /* Geser sedikit ke kiri dari ujung kanan */
        }
        .photo-img {
            width: 100px; /* Dikecilkan sedikit (sekitar ~5-10%) dari 110px */
            height: auto;
            border-radius: 4px; /* Optional: for slightly rounded corners */
        }

        /* Sections */
        .section {
            margin-bottom: 12px;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            margin-bottom: 6px;
            padding-bottom: 2px;
            letter-spacing: 0.5px;
        }
        
        /* Items & Page Breaks */
        .item {
            margin-bottom: 8px;
            page-break-inside: avoid;
        }
        
        /* Bullets */
        ul {
            margin-top: 2px;
            margin-bottom: 0;
            padding-left: 15px;
        }
        li {
            margin-bottom: 2px;
            text-align: justify;
        }

        /* Core Competencies */
        .core-table {
            width: 100%;
            border-collapse: collapse;
        }
        .core-table td {
            font-size: 9.5pt;
            text-align: left !important;
            vertical-align: top;
            line-height: 1.25;
        }

        /* Certifications Table (Compact Grid) */
        .cert-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cert-table td {
            vertical-align: top;
            padding-bottom: 6px;
            padding-right: 10px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="text-align: <?= !empty($candidate->photo_path) ? 'left' : 'center' ?>; width: <?= !empty($candidate->photo_path) ? '80%' : '100%' ?>;">
                <div class="name-block"><?= e($candidate->full_name) ?></div>
                <?php if(!empty($candidate->professional_title)): ?>
                    <div class="title-block"><?= e($candidate->professional_title) ?></div>
                <?php endif; ?>
                <div class="contact-info">
                    <!-- Baris 1: Lokasi & Kontak -->
                    <div>
                        <?= e($candidate->city) ?><?= !empty($candidate->province) ? ', ' . e($candidate->province) : '' ?> &nbsp;&bull;&nbsp; 
                        <?= e($candidate->phone) ?> &nbsp;&bull;&nbsp; 
                        <a href="mailto:<?= e($candidate->email) ?>"><?= e($candidate->email) ?></a>
                    </div>
                    
                    <!-- Baris 2: Sosial Media & URL -->
                    <div style="margin-top: 3px;">
                        <?php 
                        $links = [];
                        if($candidate->linkedin_url) {
                            $links[] = '<a href="'.e($candidate->linkedin_url).'">'.str_replace(['https://', 'http://', 'www.'], '', e($candidate->linkedin_url)).'</a>';
                        }
                        if($candidate->github_url) {
                            $links[] = '<a href="'.e($candidate->github_url).'">'.str_replace(['https://', 'http://', 'www.'], '', e($candidate->github_url)).'</a>';
                        }
                        if($candidate->portfolio_url) {
                            $links[] = '<a href="'.e($candidate->portfolio_url).'">'.str_replace(['https://', 'http://', 'www.'], '', e($candidate->portfolio_url)).'</a>';
                        }
                        echo implode(' &nbsp;&bull;&nbsp; ', $links);
                        ?>
                    </div>
                </div>
            </td>
            <?php if(!empty($candidate->photo_path)): ?>
            <td style="width: 20%;" class="photo-wrapper">
                <?php 
                    $photoSrc = '';
                    $absPath = realpath(__DIR__ . '/../../../' . $candidate->photo_path);
                    if ($absPath && file_exists($absPath)) {
                        $type = pathinfo($absPath, PATHINFO_EXTENSION);
                        // Untuk JPG terkadang terdeteksi jpg, tapi mime typenya jpeg
                        if (strtolower($type) == 'jpg') $type = 'jpeg';
                        
                        $data = file_get_contents($absPath);
                        $photoSrc = 'data:image/' . strtolower($type) . ';base64,' . base64_encode($data);
                    }
                ?>
                <?php if($photoSrc): ?>
                    <img src="<?= $photoSrc ?>" class="photo-img" alt="Profile Photo">
                <?php endif; ?>
            </td>
            <?php endif; ?>
        </tr>
    </table>

    <?php if(!empty($candidate->professional_summary) && in_array('summary', $config['sections'] ?? [])): ?>
    <div class="section" style="page-break-inside: avoid;">
        <div class="section-title">Professional Summary</div>
        <div style="text-align: justify; font-size: 10pt;">
            <?= e($candidate->professional_summary) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if(!empty($skills) && in_array('skills', $config['sections'] ?? [])): ?>
    <div class="section" style="page-break-inside: avoid;">
        <div class="section-title">Core Competencies</div>
        <table class="core-table">
            <?php 
                $allSkills = [];
                foreach($skills as $catName => $skillList) {
                    $allSkills = array_merge($allSkills, $skillList);
                }
                $chunks = array_chunk($allSkills, 2);
                foreach($chunks as $row) {
                    echo '<tr>';
                    echo '<td style="width: 50%; padding-bottom: 2px; padding-right: 15px;">&bull; ' . e($row[0]) . '</td>';
                    if(isset($row[1])) {
                        echo '<td style="width: 50%; padding-bottom: 2px; padding-left: 5px;">&bull; ' . e($row[1]) . '</td>';
                    } else {
                        echo '<td style="width: 50%;"></td>';
                    }
                    echo '</tr>';
                }
            ?>
        </table>
    </div>
    <?php endif; ?>

    <?php if(!empty($experiences) && in_array('experience', $config['sections'] ?? [])): ?>
    <div class="section">
        <div class="section-title">Professional Experience</div>
        <?php foreach($experiences as $exp): ?>
            <div class="item">
                <table class="table-layout">
                    <tr>
                        <td class="fw-bold" style="font-size: 11pt;"><?= e($exp->job_title) ?></td>
                        <td class="text-right fw-bold" style="width: 35%;"><?= $exp->start_date ? date('M Y', strtotime($exp->start_date)) : '' ?> - <?= $exp->current_job ? 'Present' : ($exp->end_date ? date('M Y', strtotime($exp->end_date)) : '') ?></td>
                    </tr>
                    <tr>
                        <td class="fst-italic"><?= e($exp->company) ?></td>
                        <td class="text-right fst-italic"><?= e($exp->location) ?></td>
                    </tr>
                </table>
                <?php if(!empty($exp->bullets)): ?>
                <ul>
                    <?php foreach($exp->bullets as $bullet): ?>
                        <li><?= e($bullet->bullet_text) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if(!empty($projects) && in_array('projects', $config['sections'] ?? [])): ?>
    <div class="section">
        <div class="section-title">Key Projects</div>
        <?php foreach($projects as $proj): ?>
            <div class="item">
                <table class="table-layout">
                    <tr>
                        <td class="fw-bold" style="width: 75%; font-size: 11pt;"><?= e($proj->project_name) ?></td>
                        <td class="text-right fw-bold" style="width: 25%; white-space: nowrap;">
                            <?= $proj->start_date ? date('M Y', strtotime($proj->start_date)) : '' ?><?= ($proj->start_date || $proj->end_date) ? ' - ' : '' ?><?= $proj->end_date ? date('M Y', strtotime($proj->end_date)) : ($proj->start_date ? 'Present' : '') ?>
                        </td>
                    </tr>
                    <?php if($proj->role || $proj->project_url || $proj->organization_company): ?>
                    <tr>
                        <td class="fst-italic" style="vertical-align: top; padding-top: 1px;">
                            <?= $proj->role ? e($proj->role) : '' ?><?= ($proj->role && $proj->organization_company) ? ' &mdash; ' : '' ?><?= $proj->organization_company ? e($proj->organization_company) : '' ?>
                        </td>
                        <td class="text-right" style="vertical-align: top; white-space: nowrap; padding-top: 1px;">
                            <?php if($proj->project_url): ?>
                                <a href="<?= e($proj->project_url) ?>" style="display: inline-block; background-color: #333; color: #fff; text-decoration: none; padding: 2px 6px; font-size: 8pt; border-radius: 3px;">View Project</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
                <?php if($proj->technologies): ?>
                    <div style="font-size: 9.5pt; margin-top: 2px;"><strong>Tech Stack:</strong> <?= e($proj->technologies) ?></div>
                <?php endif; ?>
                <?php if(!empty($proj->bullets)): ?>
                <ul>
                    <?php foreach($proj->bullets as $bullet): ?>
                        <li><?= e($bullet->bullet_text) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if(!empty($educations) && in_array('education', $config['sections'] ?? [])): ?>
    <div class="section">
        <div class="section-title">Education</div>
        <?php foreach($educations as $edu): ?>
            <div class="item">
                <table class="table-layout">
                    <tr>
                        <td class="fw-bold" style="font-size: 11pt;"><?= e($edu->institution) ?></td>
                        <td class="text-right fw-bold" style="width: 30%;"><?= $edu->start_date ? date('M Y', strtotime($edu->start_date)) : '' ?> - <?= $edu->end_date ? date('M Y', strtotime($edu->end_date)) : 'Present' ?></td>
                    </tr>
                    <tr>
                        <td class="fst-italic">
                            <?= e($edu->degree) ?> in <?= e($edu->major) ?>
                            <?php if($edu->gpa) echo " (GPA: " . e($edu->gpa) . ")"; ?>
                        </td>
                        <td class="text-right fst-italic"><?= e($edu->city) ?></td>
                    </tr>
                </table>
                <?php if($edu->description): ?>
                    <p style="margin: 2px 0 0 0; font-size: 10pt; text-align: justify;"><?= e($edu->description) ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if(!empty($certifications) && in_array('certifications', $config['sections'] ?? [])): ?>
    <div class="section">
        <div class="section-title">Certifications</div>
        <table class="cert-table">
            <?php 
            $certs = [];
            foreach($certifications as $c) { $certs[] = $c; }
            $totalCerts = count($certs);
            $i = 0;
            while ($i < $totalCerts): 
                $cert1 = $certs[$i];
                $cert2 = ($i + 1 < $totalCerts) ? $certs[$i+1] : null;
            ?>
            <tr>
                <td style="width: 50%;">
                    <div style="font-weight: bold; font-size: 10pt;"><?= e($cert1->certification_name) ?></div>
                    <div style="font-size: 9.5pt;"><?= e($cert1->issuer) ?> &bull; <?= $cert1->issue_date ? date('M Y', strtotime($cert1->issue_date)) : '' ?></div>
                    <?php if(!empty($cert1->credential_id) || !empty($cert1->credential_url)): ?>
                        <div style="font-size: 8.5pt; margin-top: 1px; color: #333;">
                            <?php if(!empty($cert1->credential_id)): ?>ID: <?= e($cert1->credential_id) ?><?php endif; ?>
                            <?php if(!empty($cert1->credential_id) && !empty($cert1->credential_url)): ?> | <?php endif; ?>
                            <?php if(!empty($cert1->credential_url)): ?><a href="<?= e($cert1->credential_url) ?>" style="color: #333; text-decoration: underline;">View Credential</a><?php endif; ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td style="width: 50%;">
                    <?php if($cert2): ?>
                        <div style="font-weight: bold; font-size: 10pt;"><?= e($cert2->certification_name) ?></div>
                        <div style="font-size: 9.5pt;"><?= e($cert2->issuer) ?> &bull; <?= $cert2->issue_date ? date('M Y', strtotime($cert2->issue_date)) : '' ?></div>
                        <?php if(!empty($cert2->credential_id) || !empty($cert2->credential_url)): ?>
                            <div style="font-size: 8.5pt; margin-top: 1px; color: #333;">
                                <?php if(!empty($cert2->credential_id)): ?>ID: <?= e($cert2->credential_id) ?><?php endif; ?>
                                <?php if(!empty($cert2->credential_id) && !empty($cert2->credential_url)): ?> | <?php endif; ?>
                                <?php if(!empty($cert2->credential_url)): ?><a href="<?= e($cert2->credential_url) ?>" style="color: #333; text-decoration: underline;">View Credential</a><?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php 
                $i += 2;
            endwhile; 
            ?>
        </table>
    </div>
    <?php endif; ?>

    
    
<?php if(!empty($awards) && in_array('awards', $config['sections'] ?? [])): ?>
    <div class="section">
        <div class="section-title">Awards & Honors</div>
        <?php foreach($awards as $award): ?>
            <div class="item">
                <table class="table-layout">
                    <tr>
                        <td class="fw-bold" style="width: 75%; font-size: 10.5pt;"><?= e($award->title) ?></td>
                        <td class="text-right fw-bold" style="width: 25%; white-space: nowrap;"><?= $award->date_awarded ? date('M Y', strtotime($award->date_awarded)) : '' ?></td>
                    </tr>
                    <?php if($award->issuer): ?>
                    <tr>
                        <td class="fst-italic"><?= e($award->issuer) ?></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<?php if(!empty($organizations) && in_array('organizations', $config['sections'] ?? [])): ?>
    <div class="section">
        <div class="section-title">Leadership & Organizations</div>
        <?php foreach($organizations as $org): ?>
            <div class="item">
                <table class="table-layout">
                    <tr>
                        <td class="fw-bold" style="font-size: 10.5pt;"><?= e($org->organization_name) ?></td>
                        <td class="text-right fw-bold"><?= $org->start_date ? date('M Y', strtotime($org->start_date)) : '' ?> - <?= $org->end_date ? date('M Y', strtotime($org->end_date)) : 'Present' ?></td>
                    </tr>
                    <tr>
                        <td class="fst-italic"><?= e($org->position) ?></td>
                        <td class="text-right fst-italic"><?= e($org->location) ?></td>
                    </tr>
                </table>
                <?php if(!empty($org->bullets)): ?>
                <ul>
                    <?php foreach($org->bullets as $bullet): ?>
                        <li><?= e($bullet->bullet_text) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</body>
</html>