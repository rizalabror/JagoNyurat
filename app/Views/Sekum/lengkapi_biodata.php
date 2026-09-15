<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Halaman wajib untuk Sekretaris Umum baru. Lengkapi identitas dan ganti kata sandi sebelum mulai menggunakan sistem.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-xl px-4 mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-7">

                    <!-- Header Banner -->
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                             style="width:72px;height:72px;background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                            <i class="fa-solid fa-crown text-white fs-3"></i>
                        </div>
                        <h1 class="fw-bold" style="color:#1e293b;font-family:'Inter',sans-serif;font-size:1.6rem;">
                            Selamat Datang, Sekum Baru! 🎉
                        </h1>
                        <p class="text-muted">Sebelum memulai, lengkapi identitas Anda dan buat kata sandi baru yang aman.</p>
                    </div>

                    <!-- Alert Error -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <?= session()->getFlashdata('error'); ?>
                            <button type="button" class="btn-close" onclick="this.closest('.alert').remove()"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                    <li><?= esc($err); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" onclick="this.closest('.alert').remove()"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Form Card -->
                    <div class="card shadow-sm border-0" style="border-radius:1.25rem;">
                        <div class="card-body p-4 p-md-5">
                            <form action="<?= base_url('sekum/profil/simpan'); ?>" method="POST">
                                <?= csrf_field(); ?>

                                <!-- Identitas Diri -->
                                <h6 class="fw-bold text-uppercase mb-3" style="font-size:.8rem;letter-spacing:1px;color:#6366f1;">
                                    <i class="fa-solid fa-user-pen me-2"></i>Identitas Sekretaris Umum
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Lengkap</label>
                                        <input type="text" class="form-control bg-light border-0"
                                               name="fullname" value="<?= old('fullname'); ?>"
                                               placeholder="Masukkan nama lengkap Anda" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold" style="font-size:.85rem;">NPM</label>
                                        <input type="text" class="form-control bg-light border-0"
                                               name="npm" value="<?= old('npm'); ?>"
                                               placeholder="Nomor Pokok Mahasiswa" required>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Ganti Password -->
                                <h6 class="fw-bold text-uppercase mb-3" style="font-size:.8rem;letter-spacing:1px;color:#6366f1;">
                                    <i class="fa-solid fa-lock me-2"></i>Buat Kata Sandi Baru
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size:.85rem;">Kata Sandi Baru</label>
                                        <input type="password" class="form-control bg-light border-0"
                                               name="password" placeholder="Minimal 8 karakter" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size:.85rem;">Konfirmasi Kata Sandi</label>
                                        <input type="password" class="form-control bg-light border-0"
                                               name="pass_confirm" placeholder="Ulangi kata sandi" required>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="mt-4">
                                    <button type="submit" class="btn w-100 fw-bold py-3 text-white shadow"
                                            style="background:linear-gradient(135deg,#6366f1,#8b5cf6);border:none;border-radius:.75rem;letter-spacing:.5px;"
                                            onclick="return confirm('Pastikan data Anda sudah benar sebelum menyimpan.')">
                                        <i class="fa-solid fa-rocket me-2"></i> SIMPAN & MULAI KEPENGURUSAN
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

<!-- Sembunyikan sidebar pada halaman onboarding ini -->
<style>
    body > aside { display: none !important; }
    body > div.pl-64 { padding-left: 0 !important; }
    body > div > header.fixed { width: 100% !important; left: 0 !important; }
</style>

<?= $this->endSection(); ?>
