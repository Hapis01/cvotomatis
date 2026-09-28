<div class="container-fluid px-0">
    <div class="row g-0">
        <!-- Configuration Form (Left Side) -->
        <div class="col-md-5 p-4 border-end" style="height: calc(100vh - 70px); overflow-y: auto; background-color: #fff;">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h4 class="mb-0">Cover Letter Builder</h4>
                <a href="<?= base_url('settings') ?>" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="fas fa-cog"></i> Settings (Signature/Kop)</a>
            </div>
            <p class="text-muted small">Target: <strong><?= e($application->position) ?></strong> at <?= e($application->company_name) ?></p>
            
            <hr>

            <form id="clConfigForm" method="POST" target="clPreviewFrame" action="<?= base_url('cover-letter/preview/'.$application->id) ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Template</label>
                    <select name="template_id" class="form-select form-select-sm" onchange="updatePreview()">
                        <?php foreach($templates as $tpl): ?>
                            <option value="<?= $tpl->id ?>" <?= $activeTemplate == $tpl->id ? 'selected' : '' ?>><?= e($tpl->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Letter Title</label>
                    <input type="text" name="letter_title" class="form-control" 
                           value="<?= e($letterTitle) ?>" 
                           placeholder="e.g. SURAT LAMARAN KERJA" onkeyup="debouncePreview()">
                    <div class="small text-muted mt-1">Judul yang tampil di kop surat pada dokumen.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Theme Color</label>
                    <input type="color" name="theme_color" class="form-control form-control-color w-100" 
                           value="<?= e($themeColor) ?>" 
                           title="Choose your theme color" onchange="debouncePreview()">
                    <div class="small text-muted mt-1">Warna tema utama untuk elemen kop surat.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Letter Content</label>
                    <div class="small text-muted mb-2">
                        You can use variables: <code>{{candidate_name}}</code>, <code>{{company_name}}</code>, <code>{{position}}</code>, <code>{{recruiter_name}}</code>
                    </div>
                    <textarea name="letter_content" id="letter_content" class="form-control" rows="16" style="font-family: monospace; font-size: 13px;" onkeyup="debouncePreview()"><?= e($letterContent) ?></textarea>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" formaction="<?= base_url('cover-letter/save/'.$application->id) ?>" class="btn btn-primary" target="_self">Save Letter</button>
                    <a href="<?= base_url('cover-letter/export/pdf/'.$application->id) ?>" class="btn btn-danger" target="_blank"><i class="fas fa-file-pdf me-1"></i> Export PDF</a>
                </div>
            </form>
        </div>

        <!-- Live Preview (Right Side) -->
        <div class="col-md-7 bg-light d-flex justify-content-center p-4" style="height: calc(100vh - 70px); overflow-y: auto;">
            <div class="shadow-sm" style="width: 210mm; min-height: 297mm; background: white;">
                <iframe name="clPreviewFrame" id="clPreviewFrame" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    let previewTimeout;
    function debouncePreview() {
        clearTimeout(previewTimeout);
        previewTimeout = setTimeout(updatePreview, 500);
    }

    function updatePreview() {
        document.getElementById('clConfigForm').submit();
    }

    // Trigger preview on load
    window.onload = function() {
        updatePreview();
    };
</script>
