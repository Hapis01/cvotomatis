<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 pb-0 border-0">
                <h4 class="mb-0">Edit Job Application</h4>
                <p class="text-muted small">Update the details of your application.</p>
            </div>
            <div class="card-body">
                <form action="<?= base_url('applications/update/'.$application->id) ?>" method="POST">
                    
                    <h5 class="mt-2 border-bottom pb-2">Target Position</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position / Role <span class="text-danger">*</span></label>
                            <input type="text" name="position" class="form-control" required value="<?= e($application->position) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department (Optional)</label>
                            <input type="text" name="department" class="form-control" value="<?= e($application->department) ?>">
                        </div>
                    </div>

                    <h5 class="mt-4 border-bottom pb-2">Company Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" required value="<?= e($application->company_name) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Address</label>
                            <input type="text" name="company_address" class="form-control" value="<?= e($application->company_address) ?>">
                        </div>
                    </div>

                    <h5 class="mt-4 border-bottom pb-2">Recruiter Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Recruiter / HR Name</label>
                            <input type="text" name="recruiter_name" class="form-control" value="<?= e($application->recruiter_name) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Recruiter Title</label>
                            <input type="text" name="recruiter_title" class="form-control" value="<?= e($application->recruiter_title) ?>">
                        </div>
                    </div>

                    <h5 class="mt-4 border-bottom pb-2">Application Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Application Date</label>
                            <input type="date" name="application_date" class="form-control" value="<?= e($application->application_date) ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Job Description</label>
                        <textarea name="job_description" class="form-control" rows="3"><?= e($application->job_description) ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Personal Notes</label>
                        <textarea name="custom_notes" class="form-control" rows="2"><?= e($application->custom_notes) ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('applications') ?>" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Update Application</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
