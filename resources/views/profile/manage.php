<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-id-card-alt text-primary me-2"></i>Manajemen Profil & Sub-Profil CV</h4>
        <p class="text-muted small mb-0">Kelola Profil Induk (Master) yang terlindungi, dan buat Sub-Profil yang dapat disesuaikan untuk setiap lowongan pekerjaan.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cloneProfileModal">
            <i class="fas fa-copy me-1"></i> Buat Sub-Profil Baru
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#newProfileModal">
            <i class="fas fa-plus me-1"></i> Profil Kosong
        </button>
    </div>
</div>

<?php 
    $masterList = [];
    $subList = [];
    foreach($profiles as $p) {
        if(empty($p->parent_id)) {
            $masterList[] = $p;
        } else {
            $subList[] = $p;
        }
    }
?>

<!-- 1. SECTION: MASTER PROFILES -->
<div class="mb-4">
    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-primary me-2 px-2 py-1"><i class="fas fa-shield-alt me-1"></i>MASTER</span>
        <h5 class="fw-bold mb-0 text-dark">Profil Induk (Original / Master)</h5>
        <span class="text-muted small ms-2">— Profil utama yang menjadi acuan standar dan terlindungi.</span>
    </div>

    <div class="row">
        <?php foreach($masterList as $p): ?>
        <?php $isActive = ($p->id == \App\Helpers\AuthHelper::activeProfileId()); ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100 <?= $isActive ? 'border-primary border-2 shadow-sm' : 'border-0 shadow-sm' ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-primary text-white mb-2"><i class="fas fa-star me-1"></i>Master Induk</span>
                            <h5 class="card-title fw-bold mb-1"><?= e($p->full_name) ?></h5>
                            <p class="text-primary fw-semibold small mb-1">
                                <?= e($p->professional_title ? explode('|', $p->professional_title)[0] : 'No Title Set') ?>
                            </p>
                        </div>
                        <?php if($isActive): ?>
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Aktif</span>
                        <?php endif; ?>
                    </div>

                    <p class="text-muted small mb-3">
                        <i class="fas fa-envelope me-1"></i><?= e($p->email) ?>
                    </p>

                    <div class="d-flex gap-1 mt-auto">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="openCloneModal(<?= $p->id ?>, '<?= e(addslashes($p->professional_title ?: $p->full_name)) ?>')">
                            <i class="fas fa-copy me-1"></i> Buat Sub-Profil
                        </button>
                    </div>
                </div>

                <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                    <?php if(!$isActive): ?>
                        <a href="<?= base_url('profiles/switch/'.$p->id) ?>" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="fas fa-exchange-alt me-1"></i> Beralih ke Profil Ini
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('profile') ?>" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-edit me-1"></i> Edit Detail Profil
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- 2. SECTION: SUB PROFILES -->
<div class="mt-4">
    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-info text-dark me-2 px-2 py-1"><i class="fas fa-bullseye me-1"></i>TAILORED</span>
        <h5 class="fw-bold mb-0 text-dark">Sub-Profil Khusus Lowongan (Tailored Sub-Profiles)</h5>
        <span class="text-muted small ms-2">— Disesuaikan secara spesifik untuk lowongan tertentu tanpa mengubah profil master.</span>
    </div>

    <?php if(empty($subList)): ?>
        <div class="card border-0 shadow-sm p-4 text-center">
            <p class="text-muted mb-2">Belum ada sub-profil khusus lowongan yang dibuat.</p>
            <p class="text-muted small mb-3">Gunakan menu <strong>"Ekstraksi Lowongan (MD)"</strong> atau klik tombol <strong>"Buat Sub-Profil Baru"</strong> di atas untuk membuat CV khusus lowongan.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($subList as $p): ?>
            <?php 
                $isActive = ($p->id == \App\Helpers\AuthHelper::activeProfileId());
                // Cari nama master
                $parentName = 'Master';
                foreach($masterList as $m) {
                    if($m->id == $p->parent_id) {
                        $parentName = $m->professional_title ? explode('|', $m->professional_title)[0] : $m->full_name;
                        break;
                    }
                }
            ?>
            <div class="col-md-6 mb-3">
                <div class="card h-100 <?= $isActive ? 'border-info border-2 shadow-sm' : 'border-0 shadow-sm' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-info text-dark mb-1">
                                    <i class="fas fa-code-branch me-1"></i>Sub-Profil dari: <?= e($parentName) ?>
                                </span>
                                <?php if($p->target_vacancy): ?>
                                    <span class="badge bg-light text-secondary border mb-1">
                                        <i class="fas fa-briefcase me-1"></i><?= e($p->target_vacancy) ?>
                                    </span>
                                <?php endif; ?>
                                <h5 class="card-title fw-bold mb-1 mt-1"><?= e($p->professional_title ?: $p->full_name) ?></h5>
                                <p class="text-muted small mb-0"><i class="fas fa-user me-1"></i><?= e($p->full_name) ?></p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li><a class="dropdown-item" href="<?= base_url('profiles/switch/'.$p->id) ?>"><i class="fas fa-check text-success me-2"></i> Jadikan Aktif</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="fas fa-edit text-primary me-2"></i> Edit Data</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="<?= base_url('profiles/delete/'.$p->id) ?>" method="POST" onsubmit="return confirm('Hapus sub-profil ini? Profil master asli tidak akan terpengaruh.');">
                                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash me-2"></i> Hapus Sub-Profil</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <?php if($isActive): ?>
                            <span class="badge bg-success mb-2"><i class="fas fa-check-circle me-1"></i>Sedang Aktif</span>
                        <?php endif; ?>

                        <div class="d-flex gap-2 mt-3">
                            <?php if(!$isActive): ?>
                                <a href="<?= base_url('profiles/switch/'.$p->id) ?>" class="btn btn-sm btn-outline-primary flex-grow-1">
                                    <i class="fas fa-exchange-alt me-1"></i> Beralih ke Sub-Profil Ini
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('profile') ?>" class="btn btn-sm btn-info text-dark flex-grow-1">
                                    <i class="fas fa-sliders-h me-1"></i> Sesuaikan Kata Kunci & Skill
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Clone / Create Sub Profile -->
<div class="modal fade" id="cloneProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('profiles/clone') ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="fas fa-copy text-primary me-2"></i>Buat Sub-Profil CV Khusus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="fas fa-shield-alt me-1"></i> Profil master asli kamu <strong>100% aman</strong>. Sub-profil ini adalah salinan terpisah yang bebas kamu modifikasi untuk lowongan tertentu.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Profil Induk (Master):</label>
                        <select name="source_profile_id" id="modalSourceSelect" class="form-select" required>
                            <?php foreach($masterList as $mp): ?>
                                <option value="<?= $mp->id ?>" data-title="<?= e($mp->professional_title) ?>">
                                    <?= e($mp->full_name) ?> — <?= e($mp->professional_title ? explode('|', $mp->professional_title)[0] : 'Profile #' . $mp->id) ?> (Master)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul / Headline Sub-Profil Baru:</label>
                        <input type="text" name="professional_title" id="modalTitleInput" class="form-control" required placeholder="e.g. IT Business Analyst - BCA">
                        <small class="text-muted">Nama peran atau jabatan yang disesuaikan dengan lowongan kerja.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Target Perusahaan / Lowongan (Opsional):</label>
                        <input type="text" name="target_vacancy" class="form-control" placeholder="e.g. PT Bank Central Asia / BCA">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Buat & Beralih ke Sub-Profil</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal: New Blank Profile -->
<div class="modal fade" id="newProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('profiles/store-new') ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Profil Kosong Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" required placeholder="e.g. Muhammad Hafiz Batubara">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (Optional)</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. hapisbatubara@gmail.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openCloneModal(profileId, title) {
    const sel = document.getElementById('modalSourceSelect');
    const input = document.getElementById('modalTitleInput');
    if (sel) sel.value = profileId;
    if (input) input.value = title ? (title.split('|')[0].trim() + ' - Tailored') : 'Tailored Profile';
    
    const modalEl = document.getElementById('cloneProfileModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}
</script>
