<div class="row">
    <!-- Categories List -->
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Skills Management</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                <i class="fas fa-plus"></i> New Category
            </button>
        </div>

        <?php foreach($categories as $cat): ?>
        <div class="card mb-3 border-left-info">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <h6 class="mb-0 fw-bold"><?= e($cat->category_name) ?></h6>
                <div>
                    <button class="btn btn-sm btn-outline-primary me-2" onclick="addSkill(<?= $cat->id ?>, '<?= e(addslashes($cat->category_name)) ?>')">
                        <i class="fas fa-plus"></i> Add Skill
                    </button>
                    <form action="<?= base_url('profile/skills/delete-category/'.$cat->id) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this category and all its skills?');">
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <?php if(empty($cat->skills)): ?>
                    <p class="text-muted small mb-0">No skills added to this category yet.</p>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach($cat->skills as $skill): ?>
                            <div class="badge bg-light text-dark border p-2 d-flex align-items-center">
                                <?= e($skill->skill_name) ?>
                                <form action="<?= base_url('profile/skills/delete/'.$skill->id) ?>" method="POST" class="d-inline ms-2">
                                    <button type="submit" class="btn-close" style="font-size: 0.5rem;" title="Remove"></button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if(empty($categories)): ?>
            <div class="alert alert-info">Create a category (e.g. Technical Skills, Soft Skills) to start adding skills.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Category -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('profile/skills/store-category') ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Skill Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="category_name" class="form-control" required placeholder="e.g. Technical Skills">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Skill -->
<div class="modal fade" id="skillModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('profile/skills/store') ?>" method="POST">
            <input type="hidden" name="category_id" id="skill_category_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Skills to <span id="categoryNameSpan"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Skill Name</label>
                        <input type="text" name="skill_name" class="form-control" required placeholder="e.g. PHP, MySQL, JavaScript">
                        <small class="text-muted">You can add multiple skills separated by commas.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Skills</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function addSkill(categoryId, categoryName) {
    document.getElementById('skill_category_id').value = categoryId;
    document.getElementById('categoryNameSpan').innerText = categoryName;
    var modal = new bootstrap.Modal(document.getElementById('skillModal'));
    modal.show();
}
</script>
