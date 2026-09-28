<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah & Ekstrak Lowongan Kerja</h4>
                <p class="text-muted small mb-0">Sistem akan membaca dokumen PDF atau Gambar (OCR) secara otomatis dan mengekspornya menjadi berkas Markdown (<code>.md</code>).</p>
            </div>
            <a href="<?= base_url('vacancies') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="<?= base_url('vacancies/store') ?>" method="POST" enctype="multipart/form-data" id="vacancyForm">
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label fw-bold">Judul Lowongan Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg" placeholder="Contoh: IT Business Analyst - PT Bank Mandiri" required>
                            <div class="form-text">Nama folder project dan file <code>.md</code> akan dibuat otomatis berdasarkan judul ini.</div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-bold">Nama Perusahaan (Opsional)</label>
                            <input type="text" name="company" class="form-control form-control-lg" placeholder="Contoh: PT Bank Mandiri (Persero) Tbk">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3"><i class="fas fa-file-invoice text-primary me-2"></i>Sumber Data Lowongan</h5>
                    <p class="text-muted small">Pilih salah satu metode di bawah atau gunakan keduanya (upload file + catatan teks):</p>

                    <div class="row">
                        <!-- Opsi 1: File Upload (PDF / Gambar) -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light border p-3 h-100">
                                <label class="form-label fw-bold mb-1">
                                    <i class="fas fa-cloud-upload-alt text-primary me-1"></i> Unggah PDF atau Gambar
                                </label>
                                <p class="text-muted small mb-3">Mendukung format dokumen PDF atau screenshot gambar (JPG, PNG, WEBP).</p>
                                
                                <input type="file" name="file" id="fileInput" class="form-control" accept=".pdf,image/png,image/jpeg,image/jpg,image/webp">
                                
                                <div class="mt-3 p-2 bg-white rounded border small text-muted">
                                    <i class="fas fa-magic text-warning me-1"></i> <strong>Deteksi Pintar:</strong>
                                    <ul class="mb-0 ps-3 mt-1">
                                        <li><strong>PDF:</strong> Teks diekstrak langsung menggunakan PDF engine.</li>
                                        <li><strong>Gambar:</strong> Teks dibaca otomatis menggunakan teknologi <em>OCR (Optical Character Recognition)</em>.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Opsi 2: Paste Teks -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light border p-3 h-100">
                                <label class="form-label fw-bold mb-1">
                                    <i class="fas fa-keyboard text-success me-1"></i> Tempel Teks Deskripsi Lowongan
                                </label>
                                <p class="text-muted small mb-2">Salin teks lowongan dari LinkedIn, Jobstreet, Glints, atau portal karir.</p>
                                
                                <textarea name="pasted_text" rows="7" class="form-control" placeholder="Tempel (paste) kualifikasi, deskripsi pekerjaan, dan persyaratan di sini..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="<?= base_url('vacancies') ?>" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary btn-lg px-4" id="submitBtn">
                            <i class="fas fa-cogs me-1"></i> Mulai Ekstraksi & Buat Folder MD
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('vacancyForm').addEventListener('submit', function() {
    var btn = document.getElementById('submitBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sedang Mengekstrak Konten...';
    btn.disabled = true;
    this.submit();
});
</script>
