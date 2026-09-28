<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= base_url('vacancies') ?>">Lowongan</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= e($vacancy['folder']) ?></li>
            </ol>
        </nav>
        <h4 class="mb-0 fw-bold"><?= e($vacancy['title']) ?></h4>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('vacancies') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <a href="<?= base_url('vacancies/download?folder=' . urlencode($vacancy['folder'])) ?>" class="btn btn-outline-success">
            <i class="fas fa-download me-1"></i> Download .md
        </a>
        <button type="button" class="btn btn-outline-primary" onclick="copyMarkdown()">
            <i class="fas fa-copy me-1"></i> Salin Markdown
        </button>
        <a href="<?= base_url('applications/create?position=' . urlencode($vacancy['title'])) ?>" class="btn btn-primary">
            <i class="fas fa-paper-plane me-1"></i> Lamar Posisi Ini
        </a>
    </div>
</div>

<!-- Metadata Bar -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2 px-3 bg-light rounded d-flex flex-wrap align-items-center justify-content-between small">
        <div>
            <i class="fas fa-folder text-warning me-1"></i> <strong>Lokasi Folder:</strong> 
            <code>vacancies/<?= e($vacancy['folder']) ?>/</code>
        </div>
        <div>
            <i class="fas fa-file-code text-primary me-1"></i> <strong>File Markdown:</strong> 
            <code><?= e($vacancy['md_file']) ?></code>
        </div>
        <?php if($vacancy['source_file']): ?>
            <div>
                <i class="fas fa-paperclip text-info me-1"></i> <strong>Lampiran:</strong>
                <a href="<?= base_url('vacancies/source?folder=' . urlencode($vacancy['folder'])) ?>" target="_blank" class="fw-bold">
                    <?= e($vacancy['source_file']) ?> <i class="fas fa-external-link-alt fa-xs"></i>
                </a>
            </div>
        <?php endif; ?>
        <?php
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmtTr = $db->prepare("SELECT * FROM vacancy_trackings WHERE folder_name = ? AND user_id = ?");
            $stmtTr->execute([$vacancy['folder'], \App\Helpers\AuthHelper::id()]);
            $currentTracking = $stmtTr->fetch(\PDO::FETCH_ASSOC);
        ?>
        <?php if($currentTracking): ?>
            <div>
                <i class="fas fa-flag text-secondary me-1"></i> <strong>Status Lamaran:</strong>
                <?php if($currentTracking['status'] === 'Berhasil Dilamar'): ?>
                    <span class="badge bg-primary"><i class="fas fa-paper-plane me-1"></i> Berhasil Dilamar</span>
                <?php elseif($currentTracking['status'] === 'Tahap Progress'): ?>
                    <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i> Tahap Progress</span>
                <?php elseif($currentTracking['status'] === 'Berhasil'): ?>
                    <span class="badge bg-success"><i class="fas fa-check-double me-1"></i> Berhasil</span>
                <?php elseif($currentTracking['status'] === 'Gagal'): ?>
                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Gagal</span>
                <?php endif; ?>
                <a href="<?= base_url('vacancies') ?>" class="small ms-2 text-decoration-none"><i class="fas fa-edit"></i> Update di Tabel</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
    $db = \App\Core\Database::getInstance()->getConnection();
    $stmtM = $db->prepare("SELECT * FROM candidate_profiles WHERE user_id = ? AND (parent_id IS NULL OR parent_id = 0) ORDER BY id ASC");
    $stmtM->execute([\App\Helpers\AuthHelper::id()]);
    $masterProfiles = $stmtM->fetchAll(\PDO::FETCH_OBJ);
?>

<!-- Card: Buat Sub-Profil Khusus Lowongan Ini -->
<div class="card border-0 shadow-sm mb-4 border-start border-primary border-4 bg-white">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-1">
                    <span class="badge bg-primary me-2"><i class="fas fa-bullseye me-1"></i>ATS Tailoring</span>
                    <h5 class="fw-bold text-dark mb-0">Sesuaikan CV untuk Lowongan Ini (Sub-Profil Khusus)</h5>
                </div>
                <p class="text-muted small mb-0 mt-2">
                    Ingin menyesuaikan kata kunci, ringkasan, dan skill agar <strong>100% cocok dengan lowongan ini</strong>? 
                    Buat <strong>Sub-Profil</strong> baru dari profil induk (misal: <em>IT Bisnis</em>). Profil master asli kamu <strong>100% terlindungi & tidak akan berubah</strong>.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#createSubProfileModal">
                    <i class="fas fa-copy me-1"></i> Buat Sub-Profil CV
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Sub Profile -->
<div class="modal fade" id="createSubProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('profiles/clone') ?>" method="POST">
            <input type="hidden" name="target_vacancy" value="<?= e($vacancy['folder']) ?>">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-layer-group text-primary me-2"></i>Buat Sub-Profil CV Khusus Lowongan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="fas fa-shield-alt me-1"></i> <strong>Aman & Terisolasi:</strong> Seluruh data pengalaman, pendidikan, sertifikasi, dan skill dari profil induk akan diduplikasi ke sub-profil baru ini. Profil master kamu tidak akan tersentuh sama sekali.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">1. Pilih Profil Induk (Master) sebagai Basis:</label>
                        <select name="source_profile_id" id="sourceProfileSelect" class="form-select form-select-lg" required>
                            <?php foreach($masterProfiles as $mp): ?>
                                <option value="<?= $mp->id ?>" <?= $mp->id == 1 ? 'selected' : '' ?> data-title="<?= e($mp->professional_title) ?>">
                                    <?= e($mp->full_name) ?> — <?= e($mp->professional_title ? explode('|', $mp->professional_title)[0] : 'Profile #' . $mp->id) ?> (Master Induk)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">2. Judul / Headline Sub-Profil Baru:</label>
                        <input type="text" name="professional_title" id="subProfileTitleInput" class="form-control form-control-lg" required 
                               value="IT Business Analyst | <?= e($vacancy['title']) ?>">
                        <small class="text-muted">Sesuaikan judul ini dengan posisi yang disyaratkan pada lowongan agar skor ATS optimal.</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">3. Target Lowongan Kerja:</label>
                        <input type="text" class="form-control bg-light" readonly value="<?= e($vacancy['title']) ?> (vacancies/<?= e($vacancy['folder']) ?>)">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-check me-1"></i> Duplikasi & Mulai Sesuaikan CV
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('sourceProfileSelect');
    const titleInput = document.getElementById('subProfileTitleInput');
    const vacancyTitle = <?= json_encode($vacancy['title']) ?>;

    if (select && titleInput) {
        select.addEventListener('change', function() {
            const opt = select.options[select.selectedIndex];
            const baseTitle = opt.getAttribute('data-title') || 'Specialist';
            const role = baseTitle.split('|')[0].trim();
            titleInput.value = role + ' | ' + vacancyTitle;
        });
    }
});
</script>

<!-- Content Viewers -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <ul class="nav nav-tabs card-header-tabs" id="viewTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="preview-tab" data-bs-toggle="tab" data-bs-target="#previewPane" type="button">
                    <i class="fas fa-eye me-1"></i> Tampilan Dokumen (Rendered)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="raw-tab" data-bs-toggle="tab" data-bs-target="#rawPane" type="button">
                    <i class="fas fa-code me-1"></i> Berkas Asli (Raw .md)
                </button>
            </li>
        </ul>
        <span id="copyNotice" class="badge bg-success d-none"><i class="fas fa-check me-1"></i> Tersalin ke Clipboard!</span>
    </div>
    <div class="card-body p-4 tab-content">
        <!-- Rendered Tab -->
        <div class="tab-pane fade show active" id="previewPane">
            <div id="markdownRender" class="p-3 bg-white" style="line-height: 1.6; font-size: 10.5pt;"></div>
        </div>

        <!-- Raw Tab -->
        <div class="tab-pane fade" id="rawPane">
            <div class="d-flex justify-content-end mb-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="copyMarkdown()">
                    <i class="fas fa-copy me-1"></i> Salin Semua Teks
                </button>
            </div>
            <textarea id="rawMarkdownText" class="form-control font-monospace" rows="20" readonly style="font-size: 9.5pt; background-color: #f8f9fa;"><?= htmlspecialchars($vacancy['content']) ?></textarea>
        </div>
    </div>
</div>

<!-- Marked JS for Client-side Markdown Rendering -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawContent = document.getElementById('rawMarkdownText').value;
        const renderContainer = document.getElementById('markdownRender');
        if (window.marked) {
            renderContainer.innerHTML = marked.parse(rawContent);
        } else {
            renderContainer.innerText = rawContent;
        }
    });

    function copyMarkdown() {
        const text = document.getElementById('rawMarkdownText').value;
        navigator.clipboard.writeText(text).then(function() {
            const notice = document.getElementById('copyNotice');
            notice.classList.remove('d-none');
            setTimeout(() => {
                notice.classList.add('d-none');
            }, 2500);
        });
    }
</script>
