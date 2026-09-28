<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Work Experience</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#expModal" onclick="resetForm()">
        <i class="fas fa-plus me-1"></i> Add Experience
    </button>
</div>

<div class="row">
    <?php foreach($experiences as $exp): ?>
    <div class="col-md-12 mb-3">
        <div class="card border-left-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title mb-1"><?= e($exp->job_title) ?></h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" onclick='editExp(<?= json_encode($exp) ?>)'><i class="fas fa-edit"></i></button>
                        <form action="<?= base_url('profile/experience/delete/'.$exp->id) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <h6 class="card-subtitle text-muted mb-2"><?= e($exp->company) ?></h6>
                <p class="mb-2 text-muted small">
                    <i class="fas fa-calendar-alt me-1"></i> 
                    <?= $exp->start_date ? date('M Y', strtotime($exp->start_date)) : 'N/A' ?> - 
                    <?= $exp->current_job ? 'Present' : ($exp->end_date ? date('M Y', strtotime($exp->end_date)) : 'N/A') ?>
                    | <i class="fas fa-map-marker-alt ms-2 me-1"></i> <?= e($exp->location) ?>
                </p>
                <?php if(!empty($exp->bullets)): ?>
                    <ul class="mb-0 ps-3 small">
                        <?php foreach($exp->bullets as $bullet): ?>
                            <li><?= e($bullet->bullet_text) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($experiences)): ?>
        <div class="col-12"><div class="alert alert-info">No work experience added yet.</div></div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="expModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('profile/experience/store') ?>" method="POST">
            <input type="hidden" name="id" id="exp_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Experience</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Job Title <span class="text-danger">*</span></label>
                            <input type="text" name="job_title" id="job_title" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <input type="text" name="company" id="company" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" id="location" class="form-control" placeholder="e.g. Jakarta, Indonesia">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="current_job" id="current_job" value="1" onchange="toggleEndDate()">
                                <label class="form-check-label" for="current_job">I currently work here</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Achievements / Responsibilities</h6>
                    <div id="bullets-container">
                        <!-- Dynamic Bullets -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addBullet()">
                        <i class="fas fa-plus"></i> Add Bullet Point
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Experience</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleEndDate() {
    var isChecked = document.getElementById('current_job').checked;
    document.getElementById('end_date').disabled = isChecked;
}

function addBullet(text = '') {
    const container = document.getElementById('bullets-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="bullets[]" class="form-control form-control-sm" value="${text}" placeholder="e.g. Developed a new feature...">
        <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    `;
    container.appendChild(div);
}

function resetForm() {
    document.getElementById('exp_id').value = '';
    document.getElementById('modalTitle').innerText = 'Add Experience';
    document.getElementById('job_title').value = '';
    document.getElementById('company').value = '';
    document.getElementById('location').value = '';
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
    document.getElementById('current_job').checked = false;
    toggleEndDate();
    document.getElementById('bullets-container').innerHTML = '';
    addBullet(); // Add one empty bullet by default
}

function escapeHtml(unsafe) {
    if(!unsafe) return '';
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

function editExp(exp) {
    document.getElementById('exp_id').value = exp.id;
    document.getElementById('modalTitle').innerText = 'Edit Experience';
    document.getElementById('job_title').value = exp.job_title;
    document.getElementById('company').value = exp.company;
    document.getElementById('location').value = exp.location;
    document.getElementById('start_date').value = exp.start_date;
    document.getElementById('end_date').value = exp.end_date;
    document.getElementById('current_job').checked = exp.current_job == 1;
    toggleEndDate();
    
    const container = document.getElementById('bullets-container');
    container.innerHTML = '';
    if(exp.bullets && exp.bullets.length > 0) {
        exp.bullets.forEach(b => {
            addBullet(escapeHtml(b.bullet_text));
        });
    } else {
        addBullet();
    }
    
    var modal = new bootstrap.Modal(document.getElementById('expModal'));
    modal.show();
}
</script>
