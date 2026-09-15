<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Tambahkan akun pengguna baru ke Sistem JagoNyurat BEM FASILKOM UNSIKA. Daftarkan identitas sekretaris pelaksana untuk memberikan hak pengelolaan kegiatan.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>
<div id="layoutSidenav_content">
    <main>
        <!-- Main page content-->
        <div class="container-xl px-4">
            <!-- User Management -->
            <div class="mt-5 d-flex align-items-center gap-2">
                <i class="text-dark fa-solid fa-user-plus"></i>
                <h2 class="mb-0 text-dark">Add User</h2>
            </div>
            <hr class="mt-0 mb-3" />
            <div class="align-items-center d-flex justify-content-end">
                <div class="mb-3">
                    <a class="btn btn-sm btn-primary text-light" href="<?= base_url('sekum/users') ?>">
                        <i class="me-1 fa-solid fa-arrow-left"></i>
                        Back to Users List
                    </a>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <form action="<?= base_url('sekum/users/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <!-- Form Row-->
                        <div class="row gx-3">
                            <!-- Form Group (first name)-->
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1" for="inputFullname">Fullname</label>
                                <input type="text"
                                    class="form-control form-control-user <?php if (session('errors.fullname')): ?>is-invalid<?php endif ?>"
                                    name="fullname" placeholder="Enter Fullname" value="<?= old('fullname') ?>">
                            </div>
                            <!-- Form Group (NPM)-->
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1" for="inputNPM">NPM</label>
                                <input type="text"
                                    class="form-control form-control-user <?php if (session('errors.npm')): ?>is-invalid<?php endif ?>"
                                    name="npm" placeholder="Enter NPM" value="<?= old('npm') ?>">
                            </div>
                        </div>
                        <!-- Form Group (email address)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                            <input type="email"
                                class="form-control form-control-user <?php if (session('errors.email')): ?>is-invalid<?php endif ?>"
                                name="email" placeholder="Enter Email address" value="<?= old('email') ?>">
                        </div>
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (Password)-->
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1" for="inputPassword">Password</label>
                                <input type="password"
                                    class="form-control form-control-user <?php if (session('errors.password')): ?>is-invalid<?php endif ?>"
                                    name="password" placeholder="Enter Password" autocomplete="off">
                            </div>
                            <!-- Form Group (Confirm Password)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                <input type="password"
                                    class="form-control form-control-user <?php if (session('errors.pass_confirm')): ?>is-invalid<?php endif ?>"
                                    name="pass_confirm" placeholder="Enter Confirm Password"
                                    autocomplete="off">
                            </div>
                        </div>
                        <!-- Submit button-->
                        <input class="container btn btn-success" value="Add User" type="submit" />
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer-admin mt-auto footer-light">
        <div class="container-xl px-4 text-center">
            <div class="small">Copyright &copy; JagoNyurat <?= date('Y'); ?></div>
        </div>
    </footer>
</div>
<?= $this->endSection(); ?>