<div class="container-fluid px-0">
    <div class="row g-0">
        <!-- Configuration Form (Left Side) -->
        <div class="col-md-4 p-4 border-end" style="height: calc(100vh - 70px); overflow-y: auto; background-color: #fff;">
            <h4 class="mb-1">CV Builder</h4>
            <p class="text-muted small">Target: <strong><?= e($application->position) ?></strong> at <?= e($application->company_name) ?></p>
            
            <hr>

            <form id="cvConfigForm" method="POST" target="cvPreviewFrame" action="<?= base_url('cv/preview/'.$application->id) ?>">
                <div class="mb-4">
                    <label class="form-label fw-bold">Select Template</label>
                    <select name="template_id" class="form-select form-select-sm" onchange="updatePreview()">
                        <?php foreach($templates as $tpl): ?>
                            <option value="<?= $tpl->id ?>"><?= e($tpl->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Include Sections</label>
                    
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="sections[]" value="summary" id="sec_summary" checked onchange="updatePreview()">
                        <label class="form-check-label" for="sec_summary">Professional Summary</label>
                    </div>
                    
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="sections[]" value="education" id="sec_education" checked onchange="updatePreview()">
                        <label class="form-check-label" for="sec_education">Education</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="sections[]" value="experience" id="sec_experience" checked onchange="updatePreview()">
                        <label class="form-check-label" for="sec_experience">Work Experience</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="sections[]" value="projects" id="sec_projects" checked onchange="updatePreview()">
                        <label class="form-check-label" for="sec_projects">Projects</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="sections[]" value="organizations" id="sec_organizations" checked onchange="updatePreview()">
                        <label class="form-check-label" for="sec_organizations">Organizations & Leadership</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="sections[]" value="certifications" id="sec_certifications" checked onchange="updatePreview()">
                        <label class="form-check-label" for="sec_certifications">Certifications</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="sections[]" value="awards" id="sec_awards" checked onchange="updatePreview()">
                        <label class="form-check-label" for="sec_awards">Awards & Honors</label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="sections[]" value="skills" id="sec_skills" checked onchange="updatePreview()">
                        <label class="form-check-label" for="sec_skills">Skills</label>
                    </div>
                </div>
                
                <!-- Ideally in Phase 9 we add dragging ordering and individual item selection here -->

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" formaction="<?= base_url('cv/save/'.$application->id) ?>" class="btn btn-outline-secondary" target="_self"><i class="fas fa-save me-1"></i> Save Configuration</button>
                    <a href="<?= base_url('cv/export/pdf/'.$application->id) ?>" class="btn btn-danger" target="_blank"><i class="fas fa-file-pdf me-1"></i> Export PDF (Simpan Otomatis)</a>
                </div>
                <div class="alert alert-light border py-2 px-3 mt-3" style="font-size: 0.78rem;">
                    <i class="fas fa-check-circle text-success me-1"></i> <strong>Penyimpanan Otomatis:</strong><br>
                    File akan diunduh & otomatis tersimpan ke:<br>
                    <code class="text-primary">Downloads/lamaran/CV_Muhammad Hafiz Batu Bara_{Role}.pdf</code>
                </div>
            </form>
        </div>

        <!-- Live Preview (Right Side) -->
        <div class="col-md-8 bg-light d-flex justify-content-center p-4" style="height: calc(100vh - 70px); overflow-y: auto;">
            <div class="shadow-sm" style="width: 210mm; height: 297mm; background: white;">
                <iframe name="cvPreviewFrame" id="cvPreviewFrame" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function updatePreview() {
        document.getElementById('cvConfigForm').submit();
    }

    // Trigger preview on load
    window.onload = function() {
        updatePreview();
    };
</script>
