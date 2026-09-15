<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Buat draf surat baru untuk berbagai keperluan kegiatan mahasiswa. Manfaatkan fitur editor untuk menyusun lampiran serta kelengkapan administrasi program Anda.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="mt-4 mb-3 d-flex align-items-center gap-2">
                <i class="text-dark fa-solid fa-envelope-open-text fa-lg"></i>
                <h2 class="mb-0 text-dark">Buat Surat Baru</h2>
            </div>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if (!$prokerAktif): ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    Anda belum memiliki Program Kerja yang terdaftar. Silakan <a href="<?= base_url('/profile/edit') ?>">lengkapi biodata Anda</a> terlebih dahulu.
                </div>
            <?php else: ?>

            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>Formulir Identitas Surat</div>
                        <div class="card-body" style="max-height: calc(100vh - 120px); overflow-y: auto; overflow-x: hidden;">
                            <form action="<?= base_url('/sekpel/surat/store') ?>" method="post" id="formSurat">
                                <?= csrf_field(); ?>
                                
                                <!-- Info Proker Otomatis -->
                                <div class="alert alert-info d-flex align-items-center gap-3 mb-3 py-2">
                                    <i class="fa-solid fa-briefcase fa-lg"></i>
                                    <div>
                                        <strong>Program Kerja:</strong> <?= esc($prokerAktif['nama']) ?>
                                        <span class="badge bg-secondary ms-2"><?= esc($prokerAktif['tahun_aktif']) ?></span>
                                    </div>
                                </div>
                                <input type="hidden" name="proker_id" value="<?= esc($prokerAktif['id']) ?>">

                                <!-- Jenis Surat & Info Dasar -->
                                <div class="row gx-3">
                                    <div class="col-md-4 mb-3">
                                        <label class="small mb-1 fw-bold" for="jenis_surat">Jenis Surat</label>
                                        <select class="form-select" id="jenis_surat" name="jenis_surat" required>
                                            <option value="non_elektronik" selected>Surat Non Elektronik</option>
                                            <option value="elektronik">Surat Elektronik</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small mb-1" for="perihal">Perihal</label>
                                        <input class="form-control" id="perihal" name="perihal" type="text" placeholder="Masukkan Perihal Surat" value="<?= old('perihal') ?>" required />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small mb-1" for="lampiran">Lampiran <span class="text-muted">(Boleh Kosong)</span></label>
                                        <input class="form-control" id="lampiran" name="lampiran" type="text" placeholder="Contoh: 1 (satu) lembar" value="<?= old('lampiran') ?>" />
                                    </div>
                                </div>

                                <!-- Tujuan -->
                                <div class="row gx-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1" for="tujuan_surat">Kepada Yth. (Penerima)</label>
                                        <textarea class="form-control" id="tujuan_surat" name="tujuan_surat" rows="2" placeholder="Contoh: Wakil Dekan Bidang Keuangan&#10;Fakultas Ilmu Komputer" required><?= old('tujuan_surat') ?></textarea>
                                        <div class="form-text">Gunakan Enter untuk baris baru jika penerima memiliki jabatan bertingkat.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1" for="tempat_tujuan">di (Tempat Tujuan)</label>
                                        <input class="form-control" id="tempat_tujuan" name="tempat_tujuan" type="text" placeholder="Contoh: Tempat" value="<?= old('tempat_tujuan') ?: 'Tempat' ?>" required />
                                    </div>
                                </div>

                                <!-- Opsi Penandatangan Tambahan (Dinamis dari Sekum) -->
                                <div class="row gx-3">
                                    <div class="col-md-6 mb-3" id="blok_ttd_ketum">
                                        <label class="small mb-1 fw-bold text-primary" for="ttd_ketua_bem">Menyetujui: Ketua BEM <span class="fw-normal text-muted">(Opsional)</span></label>
                                        <select class="form-select" id="ttd_ketua_bem" name="ttd_ketua_bem">
                                            <option value="">-- Tidak Membutuhkan TTD Ketua BEM --</option>
                                            <?php foreach ($ketua_bem as $bem): ?>
                                                <option value="<?= esc($bem['jabatan']) ?>"><?= esc($bem['nama']) ?> (<?= esc($bem['jabatan']) ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="form-text"><i class="fa-solid fa-circle-info me-1"></i>Khusus surat non-elektronik. TTD Ketua BEM pada surat elektronik ditangani otomatis saat disetujui.</div>
                                    </div>
                                    <div class="col-md-6 mb-3" id="blok_ttd_dekan">
                                        <label class="small mb-1 fw-bold text-primary" for="ttd_dekan">Mengetahui: Dekan/Pimpinan <span class="fw-normal text-muted">(Opsional)</span></label>
                                        <select class="form-select" id="ttd_dekan" name="ttd_dekan">
                                            <option value="">-- Tidak Membutuhkan TTD Dekan --</option>
                                            <?php foreach ($dekan as $d): ?>
                                                <option value="<?= esc($d['jabatan']) ?>"><?= esc($d['nama']) ?> (<?= esc($d['jabatan']) ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Rincian Waktu dan Tempat Kegiatan -->
                                <div class="card bg-light border-0 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="small fw-bold mb-0"><i class="fa-regular fa-calendar me-1"></i> Rincian Kegiatan</p>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="sembunyikan_tabel" name="sembunyikan_tabel" value="1" <?= old('sembunyikan_tabel') ? 'checked' : '' ?> onchange="document.getElementById('blokRincian').style.display = this.checked ? 'none' : 'block';">
                                        <label class="form-check-label small fw-bold text-danger" style="cursor: pointer;" for="sembunyikan_tabel">Sembunyikan (Khusus Surat Paragraf Penuh)</label>
                                    </div>
                                </div>
                                
                                <div id="blokRincian" style="display: <?= old('sembunyikan_tabel') ? 'none' : 'block' ?>;">
                                    <div class="row gx-3">
                                        <div class="col-md-3 mb-2">
                                            <label class="small mb-1" for="tanggal_kegiatan_mulai">Tanggal Mulai</label>
                                            <input class="form-control" id="tanggal_kegiatan_mulai" name="tanggal_kegiatan_mulai" type="date" value="<?= old('tanggal_kegiatan_mulai') ?>" />
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small mb-1" for="tanggal_kegiatan_selesai">
                                                Tanggal Selesai 
                                                <span class="text-muted">(Kosongkan jika 1 hari)</span>
                                            </label>
                                            <input class="form-control" id="tanggal_kegiatan_selesai" name="tanggal_kegiatan_selesai" type="date" value="<?= old('tanggal_kegiatan_selesai') ?>" />
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small mb-1" for="waktu_kegiatan_mulai">Waktu Mulai</label>
                                            <input class="form-control" id="waktu_kegiatan_mulai" name="waktu_kegiatan_mulai" type="time" value="<?= old('waktu_kegiatan_mulai') ?>" />
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="small mb-1">Waktu Selesai</label>
                                            <div class="input-group">
                                                <input class="form-control" id="waktu_kegiatan_selesai" name="waktu_kegiatan_selesai" type="text" 
                                                       placeholder="Jam (HH:MM) atau teks" value="<?= old('waktu_kegiatan_selesai', 'Selesai') ?>" />
                                            </div>
                                            <div class="form-text">Contoh: <code>17:00</code> atau <code>Selesai</code></div>
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <label class="small mb-1" for="tempat_kegiatan">Tempat Kegiatan</label>
                                        <input class="form-control" id="tempat_kegiatan" name="tempat_kegiatan" type="text" placeholder="Contoh: Aula Syekh Quro Unsika" value="<?= old('tempat_kegiatan') ?>" />
                                    </div>
                                </div>

                                <hr>
                                <!-- Paragraf Surat (3 Bagian) -->
                                <p class="small fw-bold mb-2"><i class="fa-solid fa-align-left me-1"></i> Isi Surat</p>
                                <div class="mb-3">
                                    <label class="small mb-1 fw-bold" for="isi_paragraf">Paragraf Pembuka</label>
                                    <textarea class="tinymce-paragraf" id="isi_paragraf" name="isi_paragraf"><?= old('isi_paragraf', 'Teriring salam dan doa, semoga Allah SWT senantiasa memberikan kesejahteraan dan rahmat-Nya kepada kita dalam menjalankan segala aktivitas sehari-hari. Aamiin.') ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1 fw-bold" for="paragraf_isi">Isi <span class="text-muted fw-normal">(Paragraf yang memuat tujuan surat, sebelum rincian kegiatan)</span></label>
                                    <textarea class="tinymce-paragraf" id="paragraf_isi" name="paragraf_isi"><?= old('paragraf_isi', 'Sehubungan akan dilaksanakannya kegiatan [Nama Kegiatan] dengan tema &quot;[Tema Kegiatan]&quot; yang akan dilaksanakan pada:') ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1 fw-bold" for="penutup_paragraf">Paragraf Penutup</label>
                                    <textarea class="tinymce-paragraf" id="penutup_paragraf" name="penutup_paragraf"><?= old('penutup_paragraf', "Maka dengan ini kami Panitia Pelaksana bermaksud menyampaikan Pemberitahuan Kegiatan Mahasiswa terkait pelaksanaan kegiatan tersebut.\nDemikian surat pemberitahuan kegiatan mahasiswa ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.") ?></textarea>
                                </div>

                                <hr>
                                <!-- WYSIWYG Lampiran Editor (Dinamis Multi-Halaman) -->
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
                                        <i class="fa-solid fa-circle-info me-1"></i> Tiap kolom di bawah ini otomatis akan dicetak <strong>di lembar/kertas baru</strong> dengan KOP Surat resmi yang baru. 
                                    </div>
                                    
                                    <!-- Kontainer Editor -->
                                    <div id="lampiranContainer">
                                        <div class="lampiran-item mb-4 shadow-sm border rounded">
                                            <div class="d-flex justify-content-between align-items-center bg-light px-3 py-2 border-bottom">
                                                <span class="fw-bold text-secondary lampiran-title" style="font-size:13px;">Halaman 1 (Susunan Acara, dll)</span>
                                            </div>
                                            <textarea id="lampiranEditor_0" name="isi_lampiran[]" class="tinymce-lampiran"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="d-flex justify-content-end gap-2 mt-4 flex-wrap">
                                    <button type="button" class="btn btn-primary bg-primary text-white" id="btnPreviewSurat">
                                        <i class="fa-solid fa-eye me-1"></i> Preview Surat
                                    </button>
                                    <a class="btn btn-secondary" href="<?= base_url('/') ?>">Batal</a>
                                    <button class="btn btn-success bg-success text-white" type="submit" id="btnAjukanSurat"
                                            onclick="this.disabled=true; this.innerHTML='<i class=\'fa-solid fa-spinner fa-spin me-1\'></i> Mengajukan...'; this.form.submit();">
                                        <i class="fa-solid fa-paper-plane me-1"></i> Ajukan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php endif; ?>
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