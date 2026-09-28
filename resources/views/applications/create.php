<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 pb-0 border-0">
                <h4 class="mb-0">Create Job Application</h4>
                <p class="text-muted small">Enter the details of the job you are applying for.</p>
            </div>
            <div class="card-body">
                <form action="<?= base_url('applications/store') ?>" method="POST">
                    
                    <h5 class="mt-2 border-bottom pb-2">Target Position</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position / Role <span class="text-danger">*</span></label>
                            <input type="text" name="position" class="form-control" required placeholder="e.g. IT Business Analyst" value="<?= e($_GET['position'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department (Optional)</label>
                            <input type="text" name="department" class="form-control" placeholder="e.g. Information Technology">
                        </div>
                    </div>

                    <h5 class="mt-4 border-bottom pb-2">Company Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" required placeholder="e.g. PT Pertamina" value="<?= e($_GET['company'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Address</label>
                            <input type="text" name="company_address" class="form-control" placeholder="e.g. Jakarta, Indonesia">
                        </div>
                    </div>

                    <h5 class="mt-4 border-bottom pb-2">Recruiter Details (For Cover Letter)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Recruiter / HR Name</label>
                            <input type="text" name="recruiter_name" class="form-control" placeholder="e.g. John Doe / Hiring Manager">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Recruiter Title</label>
                            <input type="text" name="recruiter_title" class="form-control" placeholder="e.g. Head of HR">
                        </div>
                    </div>

                    <h5 class="mt-4 border-bottom pb-2">Application Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Application Date</label>
                            <input type="date" name="application_date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Job Description (Optional)</label>
                        <textarea name="job_description" class="form-control" rows="3" placeholder="Paste the job description here to help AI generate a tailored cover letter later..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Personal Notes</label>
                        <textarea name="custom_notes" class="form-control" rows="2" placeholder="Any internal notes for yourself..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('applications') ?>" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Save Application</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
