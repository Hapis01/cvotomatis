<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white pt-4 pb-0 border-0">
                <h5 class="mb-0">Signature</h5>
                <p class="text-muted small">Upload your digital signature (PNG recommended, transparent background).</p>
            </div>
            <div class="card-body">
                <?php if($signature): ?>
                    <div class="text-center mb-4 p-3 border rounded bg-light">
                        <!-- Ideally, serve via a protected route or symlink public folder, for simplicity using base64 or direct if accessible -->
                        <?php 
                            $sigPath = __DIR__ . '/../../storage/uploads/signature/' . $signature->file_path;
                            $sigSrc = file_exists($sigPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($sigPath)) : '';
                        ?>
                        <img src="<?= $sigSrc ?>" alt="Signature" style="max-height: 100px; max-width: 100%;">
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning small">No signature uploaded yet.</div>
                <?php endif; ?>

                <form action="<?= base_url('settings/signature') ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="file" name="signature" class="form-control" accept="image/png, image/jpeg" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Upload Signature</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white pt-4 pb-0 border-0">
                <h5 class="mb-0">Custom Letterhead</h5>
                <p class="text-muted small">Upload your custom letterhead (Kop Surat) for cover letters.</p>
            </div>
            <div class="card-body">
                <?php if($letterhead): ?>
                    <div class="text-center mb-4 p-3 border rounded bg-light">
                        <?php 
                            $headPath = __DIR__ . '/../../storage/uploads/letterhead/' . $letterhead->file_path;
                            $headSrc = file_exists($headPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($headPath)) : '';
                        ?>
                        <img src="<?= $headSrc ?>" alt="Letterhead" style="max-height: 100px; max-width: 100%;">
                        <div class="mt-2 text-muted small">Alignment: <?= ucfirst($letterhead->alignment) ?></div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning small">No letterhead uploaded yet.</div>
                <?php endif; ?>

                <form action="<?= base_url('settings/letterhead') ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label small">Alignment</label>
                        <select name="alignment" class="form-select mb-2">
                            <option value="center" <?= ($letterhead && $letterhead->alignment == 'center') ? 'selected' : '' ?>>Center</option>
                            <option value="left" <?= ($letterhead && $letterhead->alignment == 'left') ? 'selected' : '' ?>>Left</option>
                            <option value="right" <?= ($letterhead && $letterhead->alignment == 'right') ? 'selected' : '' ?>>Right</option>
                        </select>
                        <input type="file" name="letterhead" class="form-control" accept="image/png, image/jpeg" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Upload Letterhead</button>
                </form>
            </div>
        </div>
    </div>
</div>
