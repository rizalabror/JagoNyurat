<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Wajib lengkapi biodata dasar kepanitiaan dan Ketua Pelaksana. Ganti kata sandi utama dan siapkan pangkalan data program kerja untuk bisa mengakses menu surat.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-xl px-4 mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-lg mt-3 mb-5">
                        <div class="card-header bg-primary text-white text-center py-4">
                            <h3 class="font-weight-light my-2 text-white"><i class="fa-solid fa-user-shield me-2"></i>Lengkapi Biodata Kepanitiaan & Ganti Sandi</h3>
                            <p class="mb-0 small text-white-50">Sebagai langkah keamanan pertama, wajib melengkapi data kegiatan dan mengganti kata sandi bawaan.</p>
                        </div>
                        <div class="card-body p-5">
                            
                            <?php if (session()->getFlashdata('error')) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= session()->getFlashdata('error'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('errors')) : ?>
                                <div class="alert alert-danger border-left-danger" role="alert">
                                    <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('sekpel/profil/simpan') ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                
                                <h5 class="text-primary mb-3"><i class="fa-solid fa-key me-2"></i>1. Pembaruan Kata Sandi</h5>
                                <div class="row gx-3 mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="small fw-bold mb-1" for="password">Kata Sandi Baru</label>
                                        <input class="form-control bg-light" id="password" type="password" name="password" placeholder="Minimal 8 Karakter" required />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small fw-bold mb-1" for="pass_confirm">Konfirmasi Kata Sandi Baru</label>
                                        <input class="form-control bg-light" id="pass_confirm" type="password" name="pass_confirm" placeholder="Ulangi Sandi Baru" required />
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h5 class="text-primary mb-3"><i class="fa-solid fa-user-pen me-2"></i>2. Identitas Sekretaris Pelaksana</h5>
                                <div class="row gx-3 mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="small fw-bold mb-1" for="fullname">Nama Lengkap Anda (Sekpel)</label>
                                        <input class="form-control" id="fullname" type="text" name="fullname" value="<?= old('fullname', user()->fullname) ?>" required />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small fw-bold mb-1" for="npm">NPM Anda (Sekpel)</label>
                                        <input class="form-control" id="npm" type="text" name="npm" value="<?= old('npm', user()->npm) ?>" required />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="small fw-bold mb-1" for="ttd_sekpel">Tanda Tangan Asli Anda (Image: .png / .jpg)</label>
                                        <input class="form-control" type="file" id="ttd_sekpel" name="ttd_sekpel" accept=".png, .jpg, .jpeg" required />
                                        <div class="form-text small">Tanda tangan ini akan dicetak otomatis dalam PDF surat. (Maks 2MB)</div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h5 class="text-primary mb-3"><i class="fa-solid fa-users me-2"></i>3. Profil Kegiatan (Program Kerja)</h5>
                                <div class="row gx-3 mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="small fw-bold mb-1" for="kode_proker">Kode Program Kerja</label>
                                        <input class="form-control" id="kode_proker" type="text" name="kode_proker" placeholder="Contoh: P-001" value="<?= old('kode_proker') ?>" required />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small fw-bold mb-1" for="tahun_aktif">Tahun Pelaksanaan</label>
                                        <input class="form-control" id="tahun_aktif" type="number" name="tahun_aktif" placeholder="Contoh: 2026" min="2020" max="2100" value="<?= old('tahun_aktif') ?>" required />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="small fw-bold mb-1" for="nama_proker">Nama Kepanitiaan Lengkap</label>
                                        <input class="form-control" id="nama_proker" type="text" name="nama_proker" placeholder="Contoh: Computer Science Festival 2026" value="<?= old('nama_proker') ?>" required />
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h5 class="text-primary mb-3"><i class="fa-solid fa-user-tie me-2"></i>4. Identitas Ketua Pelaksana</h5>
                                <div class="row gx-3 mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="small fw-bold mb-1" for="nama_ketua_pelaksana">Nama Ketua Pelaksana (Ketuplak)</label>
                                        <input class="form-control" id="nama_ketua_pelaksana" type="text" name="nama_ketua_pelaksana" value="<?= old('nama_ketua_pelaksana') ?>" required />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small fw-bold mb-1" for="npm_ketua_pelaksana">NPM Ketua Pelaksana</label>
                                        <input class="form-control" id="npm_ketua_pelaksana" type="text" name="npm_ketua_pelaksana" value="<?= old('npm_ketua_pelaksana') ?>" required />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="small fw-bold mb-1" for="ttd_ketua">Tanda Tangan Ketua (Image: .png / .jpg)</label>
                                        <input class="form-control" type="file" id="ttd_ketua" name="ttd_ketua" accept=".png, .jpg, .jpeg" required />
                                        <div class="form-text small">Tanda tangan ketuplak ini juga akan ditaruh berdampingan di form surat kelak. (Maks 2MB)</div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold text-sm py-4 rounded-2xl shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2" 
                                            type="submit" 
                                            onclick="return confirm('Apakah Anda yakin data Kepanitiaan dan Sandi Baru sudah benar?')">
                                        <i class="fa-solid fa-floppy-disk text-[18px]"></i>
                                        Simpan Biodata & Masuk Dasbor
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

<!-- Sembunyikan sidebar & sesuaikan layout untuk halaman onboarding ini -->
<style>
    /* Sembunyikan <aside> sidebar Tailwind */
    body > aside { display: none !important; }
    /* Hapus geseran 256px dari konten utama */
    body > div.pl-64 { padding-left: 0 !important; }
    /* Perlebar topbar agar memenuhi layar penuh */
    body > div > header.fixed { width: 100% !important; left: 0 !important; }
</style>

<?= $this->endSection(); ?>
