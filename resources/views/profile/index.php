<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <span class="fw-bold">Personal Information</span>
            <?php if(!empty($profile->professional_title)): ?>
                <span class="badge bg-primary ms-2"><?= e(explode('|', $profile->professional_title)[0]) ?></span>
            <?php endif; ?>
        </div>
        <a href="<?= base_url('profiles/manage') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-exchange-alt me-1"></i> Switch Profile
        </a>
    </div>
    <div class="card-body">
        <form action="<?= base_url('profile/update') ?>" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Profile Photo (Optional)</label>
                    <?php if(!empty($profile->photo_path)): ?>
                        <div class="mb-2">
                            <img src="<?= base_url($profile->photo_path) ?>" alt="Profile Photo" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/gif">
                    <small class="text-muted">Will be used in CV templates that support photos.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control" required value="<?= e($profile->full_name ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Professional Title / Headline <span class="text-danger">*</span></label>
                    <input type="text" name="professional_title" class="form-control" placeholder="e.g. IT Support | Web Developer" required value="<?= e($profile->professional_title ?? '') ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= e($profile->email ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= e($profile->phone ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= e($profile->address ?? '') ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= e($profile->city ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Province</label>
                    <input type="text" name="province" class="form-control" value="<?= e($profile->province ?? '') ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Portfolio URL</label>
                    <input type="text" name="portfolio_url" class="form-control" value="<?= e($profile->portfolio_url ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="text" name="linkedin_url" class="form-control" value="<?= e($profile->linkedin_url ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">GitHub URL</label>
                    <input type="text" name="github_url" class="form-control" value="<?= e($profile->github_url ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Professional Summary</label>
                <textarea name="professional_summary" class="form-control" rows="5" placeholder="Write a brief professional summary..."><?= e($profile->professional_summary ?? '') ?></textarea>
                <small class="text-muted">A summary will be shown at the top of your CV.</small>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Profile</button>
        </form>
    </div>
</div>
