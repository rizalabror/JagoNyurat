<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Perbarui dan perbaiki kembali draf surat kepanitiaan Anda. Ajukan revisi sesuai catatan Sekretaris Umum agar proses legalisasi kelak berjalan tanpa hambatan.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 mt-4 mb-4">
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-primary fw-bold">
                                <i class="fa-solid fa-pen-to-square me-2"></i>Edit Draft Surat
                            </h5>
                            <?php if ($surat['status'] == 'Rejected'): ?>
                                <span class="badge bg-danger">Direvisi Sekum</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body" style="max-height: calc(100vh - 120px); overflow-y: auto; overflow-x: hidden;">
                            
                            <!-- ALERT CATATAN REVISI JIKA REJECTED -->
                            <?php if ($surat['status'] == 'Rejected' && !empty($surat['catatan_revisi'])): ?>
                                <div class="alert alert-danger shadow-sm mb-4" role="alert">
                                    <h6 class="alert-heading fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Instruksi Revisi dari Sekum:</h6>
                                    <hr>
                                    <p class="mb-0 text-dark"><?= nl2br(esc($surat['catatan_revisi'])); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')) : ?>
                                <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
                            <?php endif; ?>

                            <form action="<?= base_url('sekpel/surat/update/' . $surat['public_id']) ?>" method="POST" id="formSurat">
                                <?= csrf_field() ?>

                                <!-- Info Proker Otomatis -->
                                <div class="alert alert-info d-flex align-items-center gap-3 mb-3 py-2">
                                    <i class="fa-solid fa-briefcase fa-lg"></i>
                                    <div>
                                        <strong>Program Kerja:</strong> <?= esc($prokerAktif['nama'] ?? '-') ?>
                                        <span class="badge bg-secondary ms-2"><?= esc($prokerAktif['tahun_aktif'] ?? '') ?></span>
                                    </div>
                                </div>
                                <input type="hidden" name="proker_id" value="<?= esc($surat['proker_id']) ?>">

                                <!-- Jenis Surat & Info Dasar -->
                                <div class="row gx-3">
                                    <div class="col-md-4 mb-3">
                                        <label class="small mb-1 fw-bold" for="jenis_surat">Jenis Surat</label>
                                        <select class="form-select" id="jenis_surat" name="jenis_surat" required>
                                            <option value="non_elektronik" <?= ($surat['jenis_surat'] ?? 'non_elektronik') == 'non_elektronik' ? 'selected' : '' ?>>Surat Non Elektronik</option>
                                            <option value="elektronik" <?= ($surat['jenis_surat'] ?? '') == 'elektronik' ? 'selected' : '' ?>>Surat Elektronik</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small mb-1" for="perihal">Perihal</label>
                                        <input class="form-control" id="perihal" name="perihal" type="text" value="<?= esc($surat['perihal']) ?>" required />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small mb-1" for="lampiran">Lampiran <span class="text-muted">(Boleh Kosong)</span></label>
                                        <input class="form-control" id="lampiran" name="lampiran" type="text" value="<?= esc($surat['lampiran']) ?>" />
                                    </div>
                                </div>

                                <!-- Tujuan -->
                                <div class="row gx-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1" for="tujuan_surat">Kepada Yth. (Penerima)</label>
                                        <textarea class="form-control" id="tujuan_surat" name="tujuan_surat" rows="2" required><?= esc($surat['tujuan_surat']) ?></textarea>
                                        <div class="form-text">Gunakan Enter untuk baris baru jika penerima memiliki jabatan bertingkat.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1" for="tempat_tujuan">di (Tempat Tujuan)</label>
                                        <input class="form-control" id="tempat_tujuan" name="tempat_tujuan" type="text" value="<?= esc($surat['tempat_tujuan']) ?>" required />
                                    </div>
                                </div>

                                <!-- Opsi Penandatangan -->
                                <div class="row gx-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1 fw-bold text-primary" for="ttd_ketua_bem">Menyetujui: Ketua BEM <span class="fw-normal text-muted">(Opsional)</span></label>
                                        <select class="form-select" id="ttd_ketua_bem" name="ttd_ketua_bem">
                                            <option value="">-- Tidak Membutuhkan TTD Ketua BEM --</option>
                                            <?php foreach ($ketua_bem as $bem): ?>
                                                <option value="<?= esc($bem['jabatan']) ?>" <?= $surat['ttd_ketua_bem'] == $bem['jabatan'] ? 'selected' : '' ?>><?= esc($bem['nama']) ?> (<?= esc($bem['jabatan']) ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1 fw-bold text-primary" for="ttd_dekan">Mengetahui: Dekan/Pimpinan <span class="fw-normal text-muted">(Opsional)</span></label>
                                        <select class="form-select" id="ttd_dekan" name="ttd_dekan">
                                            <option value="">-- Tidak Membutuhkan TTD Dekan --</option>
                                            <?php foreach ($dekan as $d): ?>
                                                <option value="<?= esc($d['jabatan']) ?>" <?= $surat['ttd_dekan'] == $d['jabatan'] ? 'selected' : '' ?>><?= esc($d['nama']) ?> (<?= esc($d['jabatan']) ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <?php 
                                    $lampiranArr = [];
                                    $sembunyikanTabel = false;
                                    if (!empty(trim($surat['isi_lampiran'] ?? ''))) {
                                        $dec = json_decode($surat['isi_lampiran'], true);
                                        if(json_last_error() === JSON_ERROR_NONE && is_array($dec)) {
                                            if (isset($dec['pages'])) {
                                                $lampiranArr = $dec['pages'];
                                                $sembunyikanTabel = $dec['sembunyikan_tabel'] ?? false;
                                            } else {
                                                $lampiranArr = $dec;
                                            }
                                        } else {
                                            $lampiranArr = [$surat['isi_lampiran']];
                                        }
                                    }
                                    if(empty($lampiranArr)) {
                                        $lampiranArr = [''];
                                    }
                                ?>

                                <!-- Rincian Kegiatan -->
                                <div class="card bg-light border-0 p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <p class="small fw-bold mb-0"><i class="fa-regular fa-calendar me-1"></i> Rincian Kegiatan</p>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="sembunyikan_tabel" name="sembunyikan_tabel" value="1" <?= (old('sembunyikan_tabel') || ($surat['sembunyikan_tabel'] ?? false)) ? 'checked' : '' ?> onchange="document.getElementById('blokRincian').style.display = this.checked ? 'none' : 'block';">
                                            <label class="form-check-label small fw-bold text-danger" style="cursor: pointer;" for="sembunyikan_tabel">Sembunyikan (Khusus Surat Paragraf Penuh)</label>
                                        </div>
                                    </div>
                                    
                                    <?php 
                                        // Ambil status sembunyi dari JSON jika ada
                                        $decodedLampiran = json_decode($surat['isi_lampiran'], true);
                                        $isSembunyi = $decodedLampiran['sembunyikan_tabel'] ?? false;
                                    ?>
                                    <div id="blokRincian" style="display: <?= $isSembunyi ? 'none' : 'block' ?>;">
                                    <div class="row gx-3">
                                        <div class="col-md-3 mb-2">
                                            <label class="small mb-1" for="tanggal_kegiatan_mulai">Tanggal Mulai</label>
                                            <input class="form-control" id="tanggal_kegiatan_mulai" name="tanggal_kegiatan_mulai" type="date" value="<?= esc($surat['tanggal_kegiatan_mulai'] ?? '') ?>" />
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small mb-1" for="tanggal_kegiatan_selesai">Tanggal Selesai <span class="text-muted">(Kosongkan jika 1 hari)</span></label>
                                            <input class="form-control" id="tanggal_kegiatan_selesai" name="tanggal_kegiatan_selesai" type="date"
                                                value="<?= ($surat['tanggal_kegiatan_selesai'] != $surat['tanggal_kegiatan_mulai']) ? esc($surat['tanggal_kegiatan_selesai']) : '' ?>" />
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small mb-1" for="waktu_kegiatan_mulai">Waktu Mulai</label>
                                            <input class="form-control" id="waktu_kegiatan_mulai" name="waktu_kegiatan_mulai" type="time" value="<?= esc($surat['waktu_kegiatan_mulai'] ?? '') ?>" />
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small mb-1">Waktu Selesai</label>
                                            <input class="form-control" id="waktu_kegiatan_selesai" name="waktu_kegiatan_selesai" type="text"
                                                placeholder="Jam (HH:MM) atau teks" value="<?= esc($surat['waktu_kegiatan_selesai']) ?>" />
                                            <div class="form-text">Contoh: <code>17:00</code> atau <code>Selesai</code></div>
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <label class="small mb-1" for="tempat_kegiatan">Tempat Kegiatan</label>
                                        <input class="form-control" id="tempat_kegiatan" name="tempat_kegiatan" type="text" placeholder="Contoh: Aula Syekh Quro Unsika" value="<?= esc($surat['tempat_kegiatan'] ?? '') ?>" />
                                    </div>
                                    </div><!-- end blokRincian -->
                                </div><!-- end card -->

                                <hr>
                                <!-- Paragraf Surat (3 Bagian) -->
                                <p class="small fw-bold mb-2"><i class="fa-solid fa-align-left me-1"></i> Isi Surat</p>
                                <div class="mb-3">
                                    <label class="small mb-1 fw-bold" for="isi_paragraf">Paragraf Pembuka</label>
                                    <textarea class="tinymce-paragraf" id="isi_paragraf" name="isi_paragraf"><?= esc($surat['isi_paragraf']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1 fw-bold" for="paragraf_isi">Isi <span class="text-muted fw-normal">(sebelum rincian kegiatan)</span></label>
                                    <textarea class="tinymce-paragraf" id="paragraf_isi" name="paragraf_isi"><?= esc($surat['paragraf_isi'] ?? '') ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1 fw-bold" for="penutup_paragraf">Paragraf Penutup</label>
                                    <textarea class="tinymce-paragraf" id="penutup_paragraf" name="penutup_paragraf"><?= esc($surat['penutup_paragraf']) ?></textarea>
                                </div>

                                <hr>
                                <!-- Lampiran (Dinamis Multi-Halaman) -->
                                <!-- Lampiran (Dinamis Multi-Halaman) -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2 mt-4">
                                        <label class="small fw-bold text-primary">
                                            <i class="fa-solid fa-layer-group"></i> Lampiran Surat <span class="fw-normal text-muted">(Opsional)</span>
                                        </label>
                                        <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" id="btnAddLampiran">
                                            <i class="fa-solid fa-plus me-1"></i> Tambah Lampiran Baru
                                        </button>
                                    </div>
                                    <div class="alert alert-info py-2 small mb-3">
                                        <i class="fa-solid fa-circle-info me-1"></i> Tiap kolom otomatis dicetak <strong>di lembar baru</strong> beserta KOP Surat resminya tersendiri.
                                    </div>
                                    
                                    <div id="lampiranContainer">
                                        <?php foreach($lampiranArr as $idx => $lampHtml): ?>
                                        <div class="lampiran-item mb-4 shadow-sm border rounded">
                                            <div class="d-flex justify-content-between align-items-center bg-light px-3 py-2 border-bottom">
                                                <span class="fw-bold text-secondary lampiran-title" style="font-size:13px;">Halaman <?= $idx + 1 ?></span>
                                                <?php if($idx > 0): ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-lampiran py-0 px-2"><i class="fa-solid fa-trash me-1"></i>Hapus Halaman</button>
                                                <?php endif; ?>
                                            </div>
                                            <!-- Khusus textarea harus tidak di-escape jika ingin WYSIWYG render HTML dgn benar, NAMUN CI punya auto-escape di setting tertentu. Kita matikan escape agar tag HTML tidak berubah jadi &lt; dll -->
                                            <textarea name="isi_lampiran[]" id="lampiranEditor_<?= $idx ?>" class="tinymce-lampiran"><?= $lampHtml ?></textarea>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="d-flex justify-content-end gap-2 mt-4 flex-wrap">
                                    <button type="button" class="btn btn-primary bg-primary text-white" id="btnPreviewSurat">
                                        <i class="fa-solid fa-eye me-1"></i> Preview Surat
                                    </button>
                                    <a class="btn btn-secondary" href="<?= base_url('/') ?>">Batal</a>
                                    <button class="btn btn-success bg-success text-white" type="submit" id="btnAjukanEdit"
                                            onclick="this.disabled=true; this.innerHTML='<i class=\'fa-solid fa-spinner fa-spin me-1\'></i> Mengajukan...'; this.form.submit();">
                                        <i class="fa-solid fa-paper-plane me-1"></i> Ajukan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<?php if (!empty($useTinyMCE)) : ?>
    <?php pushOnce('tinymce', $this->include('components/tinymce_config')); ?>
<?php endif; ?>

<script>
// Sembunyikan Dekan jika surat elektronik
document.addEventListener('DOMContentLoaded', function () {
    const jenisSuratEl = document.getElementById('jenis_surat');
    const blokTtdDekan = document.getElementById('blok_ttd_dekan');
    const selectTtdDekan = document.getElementById('ttd_dekan');

    function toggleDekan() {
        const isElektronik = jenisSuratEl.value === 'elektronik';
        blokTtdDekan.style.display = isElektronik ? 'none' : '';
        if (isElektronik) selectTtdDekan.value = '';
    }

    jenisSuratEl.addEventListener('change', toggleDekan);
    toggleDekan();
});
</script>

<?= $this->endSection(); ?>
