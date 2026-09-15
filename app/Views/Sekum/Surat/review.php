<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Tinjau dan proses pengajuan draf surat kepanitiaan. Sekretaris Umum berhak memberikan revisi, menyetujui, atau menolak dokumen surat secara presisi dan tepat.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>

<style>
    /* Sidebar Aksi */
    .sidebar-aksi { position: sticky; top: 20px; }
</style>

<div id="layoutSidenav_content">
    <main>
        <div class="container-xl px-4 mt-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="mb-0 text-dark">Tinjauan Surat Resmi - Sekum</h2>
                <div>
                    <a href="<?= base_url('/') ?>" class="btn btn-sm btn-light border">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- PREVIEW SURAT (KIRI) MENGGUNAKAN IFRAME SEKUM CETAK -->
                <div class="col-lg-8 mb-4">
                        <div class="text-center py-5" style="border: 2px dashed #cbd5e1; border-radius: 12px; background: #f8fafc;">
                            <i class="fa-solid fa-file-signature mb-3 text-primary" style="font-size: 3rem;"></i>
                            <h4 class="fw-bold text-dark">Layar Tinjauan Surat</h4>
                            <p class="text-muted mb-4 px-5">Dokumen surat akan ditampilkan secara otomatis dalam format cetak (PDF) siap edar. Harap periksa dengan saksama seluruh tatanan letak, ejaan, lampiran, dan format surat sebelum Anda menyetujuinya.</p>
                            
                            <button onclick="bukaMesinPrint()" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                                <i class="fa-solid fa-eye me-2"></i> Buka Ulang Pratinjau Surat
                            </button>
                        </div>
                </div>

<!-- HIDDEN IFRAME UNTUK CETAK SENYAP -->
<iframe id="printHiddenFrame" src="<?= base_url('sekum/surat/print/'.$surat['public_id'].'?preview=1') ?>" style="display:none;" title="Print Surat Hidden"></iframe>

<script>
    function bukaMesinPrint() {
        var printFrame = document.getElementById('printHiddenFrame');
        if (printFrame && printFrame.contentWindow) {
            printFrame.contentWindow.focus();
            printFrame.contentWindow.print();
        }
    }
</script>

                <!-- PANEL AKSI (KANAN) -->
                <div class="col-lg-4">
                    <div class="sidebar-aksi">
                        <div class="card shadow-sm border-success mb-3">
                            <div class="card-header bg-success text-white fw-bold">Setujui Surat</div>
                            <div class="card-body text-center">
                                <p class="small text-muted">Masukkan nomor surat resmi untuk menyetujui.</p>
                                <form action="<?= base_url('sekum/surat/approve/'.$surat['public_id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="text" class="form-control mb-3" name="nomor_surat" value="<?= $suggested_nomor ?>" required>
                                    <button class="btn btn-success w-100">SAH & SETUJUI</button>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow-sm border-danger">
                            <div class="card-header bg-danger text-white fw-bold">Tolak / Revisi</div>
                            <div class="card-body">
                                <form action="<?= base_url('sekum/surat/reject/'.$surat['public_id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <textarea class="form-control mb-3" name="catatan_revisi" rows="4" placeholder="Alasan penolakan..." required></textarea>
                                    <button class="btn btn-danger w-100">KEMBALIKAN KE SEKPEL</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?= $this->endSection(); ?>
