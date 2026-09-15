<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Halaman modifikasi data pengguna BEM FASILKOM UNSIKA. Perbarui informasi akun, kata sandi, dan perizinan akses sistem untuk kelancaran pengelolaan logistik.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>
<div id="layoutSidenav_content">
    <main>
        <!-- Main page content-->
        <div class="container-xl px-4">
            <!-- User Management -->
            <div class="mt-5 d-flex align-items-center gap-2">
                <i class="text-dark fa-solid fa-user-pen"></i>
                <h2 class="mb-0 text-dark">Edit User</h2>
            </div>
            <hr class="mt-0 mb-3" />
            <div class="align-items-center d-flex justify-content-end">
                <div class="mb-3">
                    <a class="btn btn-sm btn-primary text-light" href="<?= base_url('sekum/users'); ?>">
                        <i class="me-1 fa-solid fa-arrow-left"></i>
                        Back to Users List
                    </a>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <form action="<?= base_url('sekum/users/update/' . $user->userid); ?>" method="post">
                        <?= csrf_field(); ?>
                        <!-- Form Row-->
                        <div class="row gx-3">
                            <!-- Form Group (first name)-->
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1" for="inputFullname">Fullname</label>
                                <input type="text"
                                    class="form-control <?= isset($errors['fullname']) ? 'is-invalid' : ''; ?>"
                                    id="inputFullname" name="fullname" value="<?= old('fullname', $user->fullname); ?>"
                                    required>
                            </div>
                            <!-- Form Group (NPM)-->
                            <div class="col-md-6 mb-3">
                                <label class="small mb-1" for="inputNPM">NPM</label>
                                <input type="text"
                                    class="form-control <?= isset($errors['npm']) ? 'is-invalid' : ''; ?>" id="inputNPM"
                                    name="npm" value="<?= old('npm', $user->npm); ?>" required>
                            </div>
                        </div>
                        <!-- Form Group (email address)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                id="inputEmail" name="email" value="<?= old('email', $user->email); ?>" required>
                        </div>

                        <!-- Submit button-->
                        <input class="container btn btn-success" value="Edit User" type="submit" />
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