<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Job Applications</h4>
        <p class="text-muted mb-0">Manage your target positions and companies</p>
    </div>
    <a href="<?= base_url('applications/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Application
    </a>
</div>

<div class="row">
    <?php foreach($applications as $app): ?>
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title fw-bold text-primary mb-1"><?= e($app->position) ?></h5>
                        <h6 class="card-subtitle text-dark mb-2"><i class="far fa-building me-1"></i> <?= e($app->company_name) ?></h6>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('applications/edit/'.$app->id) ?>"><i class="fas fa-edit me-2"></i> Edit</a></li>
                            <li><form action="<?= base_url('applications/duplicate/'.$app->id) ?>" method="POST"><button class="dropdown-item"><i class="fas fa-copy me-2"></i> Duplicate</button></form></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?= base_url('applications/delete/'.$app->id) ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this application? CVs and Cover Letters linked to this will be lost.');">
                                    <button class="dropdown-item text-danger"><i class="fas fa-trash me-2"></i> Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <p class="text-muted small mb-3">
                    <i class="far fa-calendar-alt me-1"></i> <?= date('F j, Y', strtotime($app->application_date)) ?>
                    <?php if($app->company_address): ?>
                    <br><i class="fas fa-map-marker-alt me-1"></i> <?= e($app->company_address) ?>
                    <?php endif; ?>
                </p>

                <hr>

                <div class="d-grid gap-2">
                    <a href="<?= base_url('cv/create/'.$app->id) ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-file-pdf me-1"></i> Build CV
                    </a>
                    <a href="<?= base_url('cover-letter/create/'.$app->id) ?>" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-envelope-open-text me-1"></i> Build Cover Letter
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if(empty($applications)): ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
            <h5>No Job Applications Yet</h5>
            <p class="text-muted">Start tracking your job hunt by adding a target application.</p>
            <a href="<?= base_url('applications/create') ?>" class="btn btn-primary mt-2">Create First Application</a>
        </div>
    <?php endif; ?>
</div>
