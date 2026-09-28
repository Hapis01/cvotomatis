<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Education History</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#educationModal" onclick="resetForm()">
        <i class="fas fa-plus me-1"></i> Add Education
    </button>
</div>

<div class="row">
    <?php foreach($educations as $edu): ?>
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title mb-1"><?= e($edu->institution) ?></h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" onclick='editEdu(<?= json_encode($edu) ?>)'><i class="fas fa-edit"></i></button>
                        <form action="<?= base_url('profile/education/delete/'.$edu->id) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <h6 class="card-subtitle text-muted mb-2"><?= e($edu->degree) ?> in <?= e($edu->major) ?></h6>
                <p class="mb-1 text-muted small">
                    <i class="fas fa-calendar-alt me-1"></i> 
                    <?= $edu->start_date ? date('M Y', strtotime($edu->start_date)) : 'N/A' ?> - 
                    <?= $edu->end_date ? date('M Y', strtotime($edu->end_date)) : 'Present' ?>
                    | <i class="fas fa-map-marker-alt ms-2 me-1"></i> <?= e($edu->city) ?>
                    <?php if($edu->gpa): ?> | <strong>GPA:</strong> <?= e($edu->gpa) ?><?php endif; ?>
                </p>
                <?php if($edu->description): ?>
                    <p class="mt-2 mb-0 small"><?= nl2br(e($edu->description)) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($educations)): ?>
        <div class="col-12"><div class="alert alert-info">No education history added yet.</div></div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="educationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('profile/education/store') ?>" method="POST">
            <input type="hidden" name="id" id="edu_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Education</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Institution <span class="text-danger">*</span></label>
                            <input type="text" name="institution" id="institution" class="form-control" required placeholder="e.g. Politeknik Negeri Medan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Degree</label>
                            <input type="text" name="degree" id="degree" class="form-control" placeholder="e.g. Diploma (D3)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Major</label>
                            <input type="text" name="major" id="major" class="form-control" placeholder="e.g. Manajemen Informatika">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="e.g. Medan">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">GPA</label>
                            <input type="text" name="gpa" id="gpa" class="form-control" placeholder="e.g. 3.79 / 4.00">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Accreditation</label>
                            <input type="text" name="accreditation" id="accreditation" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Education</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('edu_id').value = '';
    document.getElementById('modalTitle').innerText = 'Add Education';
    document.getElementById('institution').value = '';
    document.getElementById('degree').value = '';
    document.getElementById('major').value = '';
    document.getElementById('city').value = '';
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
    document.getElementById('gpa').value = '';
    document.getElementById('accreditation').value = '';
    document.getElementById('description').value = '';
}

function editEdu(edu) {
    document.getElementById('edu_id').value = edu.id;
    document.getElementById('modalTitle').innerText = 'Edit Education';
    document.getElementById('institution').value = edu.institution;
    document.getElementById('degree').value = edu.degree;
    document.getElementById('major').value = edu.major;
    document.getElementById('city').value = edu.city;
    document.getElementById('start_date').value = edu.start_date;
    document.getElementById('end_date').value = edu.end_date;
    document.getElementById('gpa').value = edu.gpa;
    document.getElementById('accreditation').value = edu.accreditation;
    document.getElementById('description').value = edu.description;
    
    var modal = new bootstrap.Modal(document.getElementById('educationModal'));
    modal.show();
}
</script>
