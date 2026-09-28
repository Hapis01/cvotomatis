<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cover Letter Preview</title>
    <style>
        @page { margin: 1in; }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header-container {
            width: 100%;
            margin-bottom: 15px;
        }
        
        table.header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table.header-table td {
            vertical-align: top;
        }

        .header-logo {
            width: 50%;
        }

        .header-logo img {
            max-height: 100px;
            max-width: 100%;
            object-fit: contain;
        }

        .header-contact {
            width: 50%;
            text-align: right;
            font-size: 10pt;
            line-height: 1.6;
        }
        
        .separator {
            border-bottom: 3px solid <?= $themeColor ?? '#008b8b' ?>;
            margin-bottom: 25px;
        }

        .letter-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 25px;
            letter-spacing: 1.5px;
            color: <?= $themeColor ?? '#008b8b' ?>;
        }

        .date-container {
            text-align: right;
            margin-bottom: 25px;
        }

        .recipient-info {
            text-align: left;
            margin-bottom: 30px;
        }
        
        .content {
            text-align: justify;
        }
        
        .signature-img {
            margin-top: 20px;
            max-height: 80px;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <?php if(!empty($letterhead)): ?>
                        <?php 
                            $headPath = __DIR__ . '/../../../storage/uploads/letterhead/' . $letterhead->file_path;
                            $headSrc = file_exists($headPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($headPath)) : '';
                            $align = $letterhead->alignment ?? 'left';
                            // Override width to not be too large in this layout, but respect user choice mostly
                            $w = $letterhead->width ?? 50;
                            if($w > 80) $w = 80; // restrict max width for this specific layout
                        ?>
                        <div style="text-align: <?= $align ?>">
                            <img src="<?= $headSrc ?>" alt="Letterhead" style="width: <?= $w ?>%;">
                        </div>
                    <?php else: ?>
                        <div style="font-size: 22pt; font-weight: bold; color: <?= $themeColor ?? '#008b8b' ?>; text-transform: uppercase;">
                            <?= e($profile->full_name ?? 'CANDIDATE NAME') ?>
                        </div>
                        <div style="font-size: 11pt; margin-top: 5px; color: #555;">
                            <?= e($profile->professional_title ?? '') ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="header-contact">
                    <table style="margin-left: auto; border-collapse: collapse; font-size: 10pt; line-height: 1.6;">
                        <tr>
                            <td style="text-align: right; padding-right: 8px; color: <?= $themeColor ?? '#008b8b' ?>; font-weight: bold;">Email :</td>
                            <td style="text-align: left;"><?= e($profile->email ?? 'email@example.com') ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding-right: 8px; color: <?= $themeColor ?? '#008b8b' ?>; font-weight: bold;">No Telp :</td>
                            <td style="text-align: left;"><?= e($profile->phone ?? '+62 000 0000 000') ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding-right: 8px; color: <?= $themeColor ?? '#008b8b' ?>; font-weight: bold;">Alamat :</td>
                            <td style="text-align: left;"><?= e($profile->city ?? 'City') ?>, <?= e($profile->province ?? 'Province') ?></td>
                        </tr>
                        <?php if(!empty($profile->portfolio_url)): ?>
                        <tr>
                            <td style="text-align: right; padding-right: 8px; color: <?= $themeColor ?? '#008b8b' ?>; font-weight: bold;">Portfolio :</td>
                            <td style="text-align: left;"><?= e($profile->portfolio_url) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="separator"></div>

    <?php if(!empty($letterTitle)): ?>
    <div class="letter-title">
        <u><?= e($letterTitle) ?></u>
    </div>
    <?php endif; ?>

    <div class="date-container">
        <?= date('F j, Y', strtotime($application->application_date ?? date('Y-m-d'))) ?>
    </div>

    <div class="recipient-info">
        <strong><?= e($application->recruiter_name ?: 'Hiring Manager') ?></strong><br>
        <?php if(!empty($application->recruiter_title)): ?>
            <?= e($application->recruiter_title) ?><br>
        <?php endif; ?>
        <?= e($application->company_name ?? 'Company Name') ?><br>
        <?= e($application->company_address ?? 'Company Address') ?>
    </div>
    
    <div class="content">
        <!-- Content already nl2br and htmlspecialchars applied in controller except for intentional breaks -->
        <?= $content ?? '' ?>
    </div>

    <div class="signature">
        <?php if(!empty($signature)): ?>
            <?php 
                $sigPath = __DIR__ . '/../../../storage/uploads/signature/' . $signature->file_path;
                $sigSrc = file_exists($sigPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($sigPath)) : '';
            ?>
            <img src="<?= $sigSrc ?>" alt="Signature" class="signature-img">
        <?php endif; ?>
        <p style="margin-top: <?= !empty($signature) ? '0' : '30px' ?>; font-weight: bold;">
            <?= e($profile->full_name ?? 'Candidate Name') ?>
        </p>
    </div>

</body>
</html>
