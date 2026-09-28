<div class="row">
    <div class="col-md-3">
        <div class="card text-center text-white bg-primary">
            <div class="card-body">
                <h3>0</h3>
                <p>Total CVs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center text-white bg-success">
            <div class="card-body">
                <h3>0</h3>
                <p>Job Applications</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center text-white bg-info">
            <div class="card-body">
                <h3>0</h3>
                <p>Cover Letters</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center text-white bg-warning">
            <div class="card-body">
                <h3>0</h3>
                <p>Templates</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Recent Applications</div>
            <div class="card-body">
                <p class="text-muted">No applications yet. Start by creating a Job Application.</p>
                <a href="<?= base_url('applications/create') ?>" class="btn btn-primary">Create Application</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Quick Actions</div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('vacancies/create') ?>" class="btn btn-outline-success"><i class="fas fa-file-import me-1"></i> Ekstrak Lowongan (PDF/Gambar)</a>
                    <a href="<?= base_url('profile') ?>" class="btn btn-outline-secondary">Update Profile</a>
                    <a href="<?= base_url('profile/experience') ?>" class="btn btn-outline-secondary">Add Experience</a>
                    <a href="<?= base_url('applications/create') ?>" class="btn btn-outline-primary">Create Application</a>
                </div>
            </div>
        </div>
    </div>
</div>
