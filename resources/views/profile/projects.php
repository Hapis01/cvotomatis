<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Projects</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#projModal" onclick="resetForm()">
        <i class="fas fa-plus me-1"></i> Add Project
    </button>
</div>

<div class="row">
    <?php foreach($projects as $proj): ?>
    <div class="col-md-12 mb-3">
        <div class="card border-left-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title mb-1"><?= e($proj->project_name) ?></h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" onclick='editProj(<?= json_encode($proj) ?>)'><i class="fas fa-edit"></i></button>
                        <form action="<?= base_url('profile/projects/delete/'.$proj->id) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <h6 class="card-subtitle text-muted mb-2">Role: <?= e($proj->role) ?> <?= $proj->organization_company ? ' | ' . e($proj->organization_company) : '' ?></h6>
                
                <p class="mb-2 text-muted small">
                    <i class="fas fa-calendar-alt me-1"></i> 
                    <?= $proj->start_date ? date('M Y', strtotime($proj->start_date)) : 'N/A' ?> - 
                    <?= $proj->end_date ? date('M Y', strtotime($proj->end_date)) : 'Present' ?>
                    <?php if($proj->project_url): ?>
                        | <i class="fas fa-link ms-2 me-1"></i> <a href="<?= e($proj->project_url) ?>" target="_blank"><?= e($proj->project_url) ?></a>
                    <?php endif; ?>
                </p>

                <?php if($proj->technologies): ?>
                    <p class="mb-2 small"><strong>Technologies:</strong> <?= e($proj->technologies) ?></p>
                <?php endif; ?>

                <?php if(!empty($proj->bullets)): ?>
                    <ul class="mb-0 ps-3 small">
                        <?php foreach($proj->bullets as $bullet): ?>
                            <li><?= e($bullet->bullet_text) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($projects)): ?>
        <div class="col-12"><div class="alert alert-info">No projects added yet.</div></div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="projModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('profile/projects/store') ?>" method="POST">
            <input type="hidden" name="id" id="proj_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="project_name" id="project_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" name="role" id="role" class="form-control" placeholder="e.g. Lead Developer">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Organization/Company</label>
                            <input type="text" name="organization_company" id="organization_company" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project URL</label>
                            <input type="text" name="project_url" id="project_url" class="form-control" placeholder="https://...">
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
                            <label class="form-label">Technologies Used</label>
                            <input type="text" name="technologies" id="technologies" class="form-control" placeholder="e.g. PHP, Laravel, MySQL, Bootstrap">
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Project Details / Description</h6>
                    <div id="bullets-container">
                        <!-- Dynamic Bullets -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addBullet()">
                        <i class="fas fa-plus"></i> Add Bullet Point
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Project</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function addBullet(text = '') {
    const container = document.getElementById('bullets-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="bullets[]" class="form-control form-control-sm" value="${text}">
        <button class="btn btn-sm btn-outline-danger" type="button" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    `;
    container.appendChild(div);
}

function resetForm() {
    document.getElementById('proj_id').value = '';
    document.getElementById('modalTitle').innerText = 'Add Project';
    document.getElementById('project_name').value = '';
    document.getElementById('role').value = '';
    document.getElementById('organization_company').value = '';
    document.getElementById('project_url').value = '';
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
    document.getElementById('technologies').value = '';
    
    document.getElementById('bullets-container').innerHTML = '';
    addBullet(); 
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

function editProj(proj) {
    document.getElementById('proj_id').value = proj.id;
    document.getElementById('modalTitle').innerText = 'Edit Project';
    document.getElementById('project_name').value = proj.project_name;
    document.getElementById('role').value = proj.role;
    document.getElementById('organization_company').value = proj.organization_company;
    document.getElementById('project_url').value = proj.project_url;
    document.getElementById('start_date').value = proj.start_date;
    document.getElementById('end_date').value = proj.end_date;
    document.getElementById('technologies').value = proj.technologies;
    
    const container = document.getElementById('bullets-container');
    container.innerHTML = '';
    if(proj.bullets && proj.bullets.length > 0) {
        proj.bullets.forEach(b => {
            addBullet(escapeHtml(b.bullet_text));
        });
    } else {
        addBullet();
    }
    
    var modal = new bootstrap.Modal(document.getElementById('projModal'));
    modal.show();
}
</script>
