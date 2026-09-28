<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-tasks text-primary me-2"></i>Ekstraksi & Tracking Lowongan Kerja</h4>
        <p class="text-muted small mb-0">Input lowongan kerja (PDF / Gambar OCR / Teks), kelola sub-profil ATS, dan pantau status proses lamaran kerja kamu secara terpusat.</p>
    </div>
    <div>
        <a href="<?= base_url('vacancies/create') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Input Lowongan Baru
        </a>
    </div>
</div>

<!-- Statistik KPI Tracking -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm border-start border-primary border-4 bg-white h-100">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Dilamar</div>
                        <div class="h3 fw-bold mb-0 text-primary"><?= $stats['total'] ?></div>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                        <i class="fas fa-paper-plane fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm border-start border-warning border-4 bg-white h-100">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Tahap Progress</div>
                        <div class="h3 fw-bold mb-0 text-warning"><?= $stats['progress'] ?></div>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm border-start border-success border-4 bg-white h-100">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Berhasil / Lolos</div>
                        <div class="h3 fw-bold mb-0 text-success"><?= $stats['berhasil'] ?></div>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm border-start border-danger border-4 bg-white h-100">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Gagal / Ditolak</div>
                        <div class="h3 fw-bold mb-0 text-danger"><?= $stats['gagal'] ?></div>
                    </div>
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABEL TRACKING UTAMA -->
<div class="card border-0 shadow-sm mb-5">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                <i class="fas fa-table-list text-primary me-2"></i>Tabel Tracking Status Lamaran
            </h5>
            <small class="text-muted">Setiap lowongan yang diinput otomatis tercatat di sini dengan status awal <strong>Berhasil Dilamar</strong>.</small>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge bg-light text-dark border px-3 py-2">
                <i class="fas fa-briefcase me-1 text-primary"></i> <?= count($trackings) ?> Posisi Aktif
            </span>
        </div>
    </div>

    <div class="card-body p-0">
        <?php if(empty($trackings)): ?>
            <div class="py-5 text-center">
                <i class="fas fa-clipboard-list text-muted fa-3x mb-3"></i>
                <h5>Belum ada lamaran yang ditracking</h5>
                <p class="text-muted small mb-3">Input lowongan baru untuk otomatis membuat tabel tracking dan sub-profil CV.</p>
                <a href="<?= base_url('vacancies/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Input Lowongan Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase fw-bold text-muted">
                        <tr>
                            <th class="ps-3" style="width: 50px;">#</th>
                            <th style="min-width: 250px;">Posisi & Perusahaan</th>
                            <th style="min-width: 130px;">Tgl Lamar</th>
                            <th style="min-width: 170px;">Status Lamaran</th>
                            <th style="min-width: 220px;">Sub-Profil CV (ATS)</th>
                            <th style="min-width: 200px;">Catatan / Detail Tahap</th>
                            <th class="text-end pe-3" style="min-width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($trackings as $idx => $t): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark fs-6">
                                        <a href="<?= base_url('vacancies/show?folder=' . urlencode($t['folder_name'])) ?>" class="text-decoration-none text-dark hover-primary">
                                            <?= e($t['job_title']) ?>
                                        </a>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-building me-1 text-secondary"></i><?= e($t['company_name'] ?: 'Perusahaan Belum Ditentukan') ?>
                                    </div>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-secondary border font-monospace small" style="font-size: 0.72rem;">
                                            <i class="fas fa-folder me-1 text-warning"></i><?= e($t['folder_name']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i>
                                        <?= $t['applied_date'] ? date('d M Y', strtotime($t['applied_date'])) : '-' ?>
                                    </div>
                                    <small class="text-muted"><?= $t['applied_date'] ? date('l', strtotime($t['applied_date'])) : '' ?></small>
                                </td>
                                <td>
                                    <?php if($t['status'] === 'Berhasil Dilamar'): ?>
                                        <span class="badge bg-primary px-3 py-2 text-white">
                                            <i class="fas fa-paper-plane me-1"></i> Berhasil Dilamar
                                        </span>
                                    <?php elseif($t['status'] === 'Tahap Progress'): ?>
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="fas fa-hourglass-half me-1"></i> Tahap Progress
                                        </span>
                                    <?php elseif($t['status'] === 'Berhasil'): ?>
                                        <span class="badge bg-success px-3 py-2 text-white">
                                            <i class="fas fa-check-double me-1"></i> Berhasil / Lolos
                                        </span>
                                    <?php elseif($t['status'] === 'Gagal'): ?>
                                        <span class="badge bg-danger px-3 py-2 text-white">
                                            <i class="fas fa-times me-1"></i> Gagal / Ditolak
                                        </span>
                                    <?php endif; ?>

                                    <?php if(!empty($t['stage'])): ?>
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-tag me-1 fa-xs"></i><?= e($t['stage']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($t['sub_profile_id'])): ?>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                                <i class="fas fa-check-circle me-1"></i> Sub-Profil Siap
                                            </span>
                                        </div>
                                        <small class="fw-semibold text-dark d-block text-truncate" style="max-width: 220px;" title="<?= e($t['sub_profile_title']) ?>">
                                            <?= e($t['sub_profile_title']) ?>
                                        </small>
                                        <div class="mt-1 d-flex flex-wrap gap-1">
                                            <a href="<?= base_url('cv/profile/' . ($t['sub_profile_id'])) ?>" class="btn btn-outline-primary btn-xs py-0 px-2" style="font-size: 0.75rem;" title="Buka CV Generator">
                                                <i class="fas fa-eye me-1"></i>Lihat CV
                                            </a>
                                            <a href="<?= base_url('cv/export-profile/pdf/' . ($t['sub_profile_id'])) ?>" class="btn btn-outline-danger btn-xs py-0 px-2" style="font-size: 0.75rem;" title="Export & Simpan PDF ke Folder Lamaran">
                                                <i class="fas fa-file-pdf me-1"></i>PDF
                                            </a>
                                            <a href="<?= base_url('profiles/switch/' . $t['sub_profile_id']) ?>" class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size: 0.75rem;" title="Edit Data Sub-Profil">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-muted small mb-1">Belum ada sub-profil khusus</div>
                                        <a href="<?= base_url('vacancies/show?folder=' . urlencode($t['folder_name'])) ?>" class="btn btn-outline-primary btn-sm py-1 px-2" style="font-size: 0.78rem;">
                                            <i class="fas fa-plus me-1"></i> Buat Sub-Profil CV
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($t['notes'])): ?>
                                        <div class="small bg-light p-2 rounded border" style="max-width: 220px; white-space: pre-wrap; font-size: 0.8rem;">
                                            <?= e($t['notes']) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">Belum ada catatan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick='openUpdateModal(<?= json_encode($t) ?>)' 
                                                title="Ubah Status / Catatan Progress">
                                            <i class="fas fa-edit me-1"></i> Update
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('vacancies/show?folder=' . urlencode($t['folder_name'])) ?>">
                                                    <i class="fas fa-file-alt text-primary me-2"></i> Buka Detail Markdown
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('vacancies/download?folder=' . urlencode($t['folder_name'])) ?>">
                                                    <i class="fas fa-download text-success me-2"></i> Download .md
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="<?= base_url('vacancies/delete-tracking') ?>" method="POST" onsubmit="return confirm('Hapus data tracking untuk posisi ini? Folder fisik lowongan tidak akan dihapus.');">
                                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                                    <input type="hidden" name="delete_folder" value="0">
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash me-2"></i> Hapus Tracking
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ARSIP FOLDER LOWONGAN (CARD VIEW) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0 text-dark">
        <i class="fas fa-folder-open text-warning me-2"></i>Arsip Berkas Markdown & Lampiran Dokumen
    </h5>
    <span class="badge bg-secondary"><?= count($vacancies) ?> Folder Tersimpan</span>
</div>

<div class="row g-3">
    <?php if(empty($vacancies)): ?>
        <div class="col-12"><div class="alert alert-light border">Belum ada folder berkas lowongan.</div></div>
    <?php else: ?>
        <?php foreach($vacancies as $v): ?>
            <div class="col-md-4 mb-2">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title fw-bold mb-0 text-truncate" style="max-width: 85%;">
                                <a href="<?= base_url('vacancies/show?folder=' . urlencode($v['folder'])) ?>" class="text-decoration-none text-dark">
                                    <?= e($v['title']) ?>
                                </a>
                            </h6>
                            <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.7rem;">
                                <?= round($v['size'] / 1024, 1) ?> KB
                            </span>
                        </div>
                        <div class="text-muted small mb-3">
                            <i class="fas fa-folder text-warning me-1"></i><code>vacancies/<?= e($v['folder']) ?></code>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= base_url('vacancies/show?folder=' . urlencode($v['folder'])) ?>" class="btn btn-xs btn-outline-primary py-1 px-2" style="font-size: 0.8rem;">
                                <i class="fas fa-book-open me-1"></i> Buka .md
                            </a>
                            <?php if($v['source_file']): ?>
                                <a href="<?= base_url('vacancies/source?folder=' . urlencode($v['folder'])) ?>" target="_blank" class="btn btn-xs btn-outline-info py-1 px-2" style="font-size: 0.8rem;" title="<?= e($v['source_file']) ?>">
                                    <i class="fas fa-paperclip me-1"></i> File Asli
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('vacancies/download?folder=' . urlencode($v['folder'])) ?>" class="btn btn-xs btn-outline-success py-1 px-2" style="font-size: 0.8rem;">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- MODAL UPDATE STATUS TRACKING -->
<div class="modal fade" id="updateTrackingModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('vacancies/update-tracking') ?>" method="POST">
            <input type="hidden" name="id" id="modal_tracking_id">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-sync-alt text-primary me-2"></i>Update Status Lamaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">Posisi & Perusahaan</label>
                        <div class="h6 fw-bold mb-0 text-dark" id="modal_job_title"></div>
                        <small class="text-primary fw-semibold" id="modal_company_name"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Utama <span class="text-danger">*</span></label>
                        <select name="status" id="modal_status" class="form-select form-select-lg" required>
                            <option value="Berhasil Dilamar">🔵 Berhasil Dilamar (Baru Apply)</option>
                            <option value="Tahap Progress">🟡 Tahap Progress (Sedang Berjalan)</option>
                            <option value="Berhasil">🟢 Berhasil (Lolos / Diterima / Offering)</option>
                            <option value="Gagal">🔴 Gagal (Tidak Lolos / Ditolak)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Detail Tahap / Stage</label>
                        <input type="text" name="stage" id="modal_stage" class="form-control" list="stageSuggestions" placeholder="e.g. Interview HR, Tes Teknis">
                        <datalist id="stageSuggestions">
                            <option value="Administrasi / Screening CV">
                            <option value="Psikotes Online">
                            <option value="Tes Kemampuan Teknis / Coding">
                            <option value="Interview HR & Perkenalan">
                            <option value="Interview User / Technical Lead">
                            <option value="Interview Direksi / Final">
                            <option value="Medical Checkup (MCU)">
                            <option value="Offering Letter / Negosiasi Gaji">
                        </datalist>
                        <small class="text-muted">Pilih atau ketik tahapan rekrutmen yang sedang kamu jalani saat ini.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Melamar / Update</label>
                        <input type="date" name="applied_date" id="modal_applied_date" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">Catatan Progress / Jadwal</label>
                        <textarea name="notes" id="modal_notes" class="form-control" rows="3" placeholder="Contoh: Jadwal interview via Zoom hari Rabu jam 14.00 dengan Pak Budi HR..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Simpan Status
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openUpdateModal(data) {
    document.getElementById('modal_tracking_id').value = data.id;
    document.getElementById('modal_job_title').innerText = data.job_title;
    document.getElementById('modal_company_name').innerText = data.company_name || 'Perusahaan Belum Ditentukan';
    document.getElementById('modal_status').value = data.status;
    document.getElementById('modal_stage').value = data.stage || '';
    document.getElementById('modal_applied_date').value = data.applied_date || '';
    document.getElementById('modal_notes').value = data.notes || '';

    const modal = new bootstrap.Modal(document.getElementById('updateTrackingModal'));
    modal.show();
}
</script>
