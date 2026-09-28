<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Organizations</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#orgModal" onclick="resetForm()">
        <i class="fas fa-plus me-1"></i> Add Organization
    </button>
</div>

<div class="row">
    <?php foreach($organizations as $org): ?>
    <div class="col-md-12 mb-3">
        <div class="card border-left-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title mb-1"><?= e($org->organization_name) ?></h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" onclick='editOrg(<?= json_encode($org) ?>)'><i class="fas fa-edit"></i></button>
                        <form action="<?= base_url('profile/organizations/delete/'.$org->id) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <h6 class="card-subtitle text-muted mb-2"><?= e($org->position) ?> <?= $org->division ? ' - ' . e($org->division) : '' ?></h6>
                
                <p class="mb-2 text-muted small">
                    <i class="fas fa-calendar-alt me-1"></i> 
                    <?= $org->start_date ? date('M Y', strtotime($org->start_date)) : 'N/A' ?> - 
                    <?= $org->end_date ? date('M Y', strtotime($org->end_date)) : 'Present' ?>
                    <?php if($org->location): ?>
                        | <i class="fas fa-map-marker-alt ms-2 me-1"></i> <?= e($org->location) ?>
                    <?php endif; ?>
                </p>

                <?php if(!empty($org->bullets)): ?>
                    <ul class="mb-0 ps-3 small">
                        <?php foreach($org->bullets as $bullet): ?>
                            <li><?= e($bullet->bullet_text) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($organizations)): ?>
        <div class="col-12"><div class="alert alert-info">No organizations added yet.</div></div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="orgModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('profile/organizations/store') ?>" method="POST">
            <input type="hidden" name="id" id="org_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Organization</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                            <input type="text" name="organization_name" id="organization_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position / Role</label>
                            <input type="text" name="position" id="position" class="form-control" placeholder="e.g. Vice Chairperson">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Division (Optional)</label>
                            <input type="text" name="division" id="division" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" id="location" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Activities / Responsibilities</h6>
                    <div id="bullets-container">
                        <!-- Dynamic Bullets -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addBullet()">
                        <i class="fas fa-plus"></i> Add Bullet Point
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Organization</button>
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
    document.getElementById('org_id').value = '';
    document.getElementById('modalTitle').innerText = 'Add Organization';
    document.getElementById('organization_name').value = '';
    document.getElementById('position').value = '';
    document.getElementById('division').value = '';
    document.getElementById('location').value = '';
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
    
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

function editOrg(org) {
    document.getElementById('org_id').value = org.id;
    document.getElementById('modalTitle').innerText = 'Edit Organization';
    document.getElementById('organization_name').value = org.organization_name;
    document.getElementById('position').value = org.position;
    document.getElementById('division').value = org.division;
    document.getElementById('location').value = org.location;
    document.getElementById('start_date').value = org.start_date;
    document.getElementById('end_date').value = org.end_date;
    
    const container = document.getElementById('bullets-container');
    container.innerHTML = '';
    if(org.bullets && org.bullets.length > 0) {
        org.bullets.forEach(b => {
            addBullet(escapeHtml(b.bullet_text));
        });
    } else {
        addBullet();
    }
    
    var modal = new bootstrap.Modal(document.getElementById('orgModal'));
    modal.show();
}
</script>
