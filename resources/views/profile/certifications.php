<div class="row">
    <!-- CERTIFICATIONS -->
    <div class="col-lg-7 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-certificate text-warning me-2"></i>Certifications</h5>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#certModal" onclick="resetCertForm()">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>

        <?php foreach($certifications as $cert): ?>
        <div class="card mb-2 border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-0 fw-bold"><?= e($cert->certification_name) ?></h6>
                        <small class="text-muted"><?= e($cert->issuer) ?></small>
                        <?php if($cert->issue_date): ?>
                            <br><small class="text-muted"><i class="far fa-calendar-alt me-1"></i>
                            <?= date('M Y', strtotime($cert->issue_date)) ?>
                            <?= $cert->expiration_date ? ' - ' . date('M Y', strtotime($cert->expiration_date)) : '' ?>
                            </small>
                        <?php endif; ?>
                        <?php if($cert->credential_id): ?>
                            <br><small class="text-muted">ID: <?= e($cert->credential_id) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-secondary" onclick='editCert(<?= json_encode($cert) ?>)'><i class="fas fa-edit"></i></button>
                        <form action="<?= base_url('profile/certifications/delete-cert/'.$cert->id) ?>" method="POST" onsubmit="return confirm('Delete?');">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <?php if($cert->description): ?>
                    <p class="small text-muted mb-0 mt-2"><?= e($cert->description) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($certifications)): ?>
            <div class="alert alert-info small">No certifications added yet.</div>
        <?php endif; ?>
    </div>

    <!-- AWARDS -->
    <div class="col-lg-5 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-trophy text-danger me-2"></i>Awards & Honors</h5>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#awardModal" onclick="resetAwardForm()">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>

        <?php foreach($awards as $award): ?>
        <div class="card mb-2 border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-0 fw-bold"><?= e($award->title) ?></h6>
                        <small class="text-muted"><?= e($award->issuer) ?></small>
                        <?php if($award->date_awarded): ?>
                            <br><small class="text-muted"><i class="far fa-calendar-alt me-1"></i><?= date('M Y', strtotime($award->date_awarded)) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-secondary" onclick='editAward(<?= json_encode($award) ?>)'><i class="fas fa-edit"></i></button>
                        <form action="<?= base_url('profile/certifications/delete-award/'.$award->id) ?>" method="POST" onsubmit="return confirm('Delete?');">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <?php if($award->description): ?>
                    <p class="small text-muted mb-0 mt-2"><?= e($award->description) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($awards)): ?>
            <div class="alert alert-info small">No awards added yet.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Certification Modal -->
<div class="modal fade" id="certModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="<?= base_url('profile/certifications/store-cert') ?>">
            <input type="hidden" name="id" id="cert_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="certModalTitle">Add Certification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Certification Name <span class="text-danger">*</span></label>
                            <input type="text" name="certification_name" id="cert_name" class="form-control" required placeholder="e.g. AWS Certified Solutions Architect">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Issuer</label>
                            <input type="text" name="issuer" id="cert_issuer" class="form-control" placeholder="e.g. Amazon">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Issue Date</label>
                            <input type="date" name="issue_date" id="cert_issue_date" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Expiration Date</label>
                            <input type="date" name="expiration_date" id="cert_exp_date" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Credential ID</label>
                            <input type="text" name="credential_id" id="cert_cred_id" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Credential URL</label>
                            <input type="text" name="credential_url" id="cert_cred_url" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="cert_description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Award Modal -->
<div class="modal fade" id="awardModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="<?= base_url('profile/certifications/store-award') ?>">
            <input type="hidden" name="id" id="award_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="awardModalTitle">Add Award</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Award Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="award_title" class="form-control" required placeholder="e.g. Best Employee of the Year">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Issuer / Organization</label>
                        <input type="text" name="issuer" id="award_issuer" class="form-control" placeholder="e.g. PT Contoh Perusahaan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Awarded</label>
                        <input type="date" name="date_awarded" id="award_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="award_desc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function resetCertForm() {
    document.getElementById('cert_id').value = '';
    document.getElementById('certModalTitle').innerText = 'Add Certification';
    ['cert_name','cert_issuer','cert_issue_date','cert_exp_date','cert_cred_id','cert_cred_url','cert_description'].forEach(id => document.getElementById(id).value = '');
}

function editCert(c) {
    document.getElementById('cert_id').value = c.id;
    document.getElementById('certModalTitle').innerText = 'Edit Certification';
    document.getElementById('cert_name').value = c.certification_name || '';
    document.getElementById('cert_issuer').value = c.issuer || '';
    document.getElementById('cert_issue_date').value = c.issue_date || '';
    document.getElementById('cert_exp_date').value = c.expiration_date || '';
    document.getElementById('cert_cred_id').value = c.credential_id || '';
    document.getElementById('cert_cred_url').value = c.credential_url || '';
    document.getElementById('cert_description').value = c.description || '';
    new bootstrap.Modal(document.getElementById('certModal')).show();
}

function resetAwardForm() {
    document.getElementById('award_id').value = '';
    document.getElementById('awardModalTitle').innerText = 'Add Award';
    ['award_title','award_issuer','award_date','award_desc'].forEach(id => document.getElementById(id).value = '');
}

function editAward(a) {
    document.getElementById('award_id').value = a.id;
    document.getElementById('awardModalTitle').innerText = 'Edit Award';
    document.getElementById('award_title').value = a.title || '';
    document.getElementById('award_issuer').value = a.issuer || '';
    document.getElementById('award_date').value = a.date_awarded || '';
    document.getElementById('award_desc').value = a.description || '';
    new bootstrap.Modal(document.getElementById('awardModal')).show();
}
</script>
