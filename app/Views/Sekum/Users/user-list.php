<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Daftar pengguna Sistem Administrasi Surat BEM FASILKOM UNSIKA. Pantau profil, status akun, dan peran seluruh panitia dari satu halaman sentral dasbor Anda.
<?= $this->endSection(); ?>

<?= $this->section('page_css'); ?>
<!-- Simple Datatables CSS — hanya dimuat di halaman ini -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
<style>
  /* Override simple-datatables agar sesuai design system Tailwind */
  .dataTable-wrapper              { font-size: 0.875rem; color: #374151; }
  .dataTable-top, .dataTable-bottom {
      display: flex; align-items: center; justify-content: space-between;
      padding: 0.75rem 0; gap: 0.75rem; flex-wrap: wrap;
  }
  .dataTable-selector             { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.25rem 0.5rem; font-size: 0.8rem; color: #374151; background: #fff; }
  .dataTable-search               { display: flex; align-items: center; }
  .dataTable-search input         { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.375rem 0.75rem; font-size: 0.875rem; color: #374151; outline: none; transition: border-color 0.15s ease, box-shadow 0.15s ease; }
  .dataTable-search input:focus   { border-color: #0051d5; box-shadow: 0 0 0 3px rgba(0,81,213,0.12); }
  .dataTable-container            { overflow-x: auto; }
  .dataTable-table                { width: 100% !important; border-collapse: collapse; table-layout: auto; }
  .dataTable-table thead th       { padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; background: #f8fafc; border-bottom: 2px solid #e2e8f0; white-space: nowrap; cursor: pointer; user-select: none; }
  .dataTable-table thead th:hover { background: #f1f5f9; color: #374151; }
  .dataTable-table thead th.asc::after  { content: ' ↑'; font-size: 0.65rem; color: #0051d5; }
  .dataTable-table thead th.desc::after { content: ' ↓'; font-size: 0.65rem; color: #0051d5; }
  .dataTable-table tbody td       { padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; white-space: nowrap; }
  .dataTable-table tbody tr:last-child td { border-bottom: none; }
  .dataTable-table tbody tr:hover td { background: #eff6ff; }
  .dataTable-info                 { font-size: 0.8rem; color: #9ca3af; }
  .dataTable-pagination           { display: flex; gap: 0.25rem; list-style: none; margin: 0; padding: 0; }
  .dataTable-pagination li a      { display: inline-flex; align-items: center; justify-content: center; min-width: 1.75rem; height: 1.75rem; padding: 0 0.5rem; font-size: 0.8rem; border: 1px solid #e2e8f0; border-radius: 0.375rem; color: #374151; text-decoration: none; cursor: pointer; transition: all 0.15s ease; background: #fff; }
  .dataTable-pagination li a:hover { background: #eff6ff; border-color: #0051d5; color: #0051d5; }
  .dataTable-pagination li.active a { background: #0051d5; border-color: #0051d5; color: #fff; font-weight: 600; }
  .dataTable-pagination li.disabled a { opacity: 0.4; cursor: not-allowed; pointer-events: none; }

  /* Kolom Actions tetap rapat */
  .dataTable-table thead th:last-child,
  .dataTable-table tbody td:last-child { white-space: nowrap; width: 80px; text-align: center; }
</style>
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>
<div id="layoutSidenav_content">
    <main>
        <!-- Main page content-->
        <div class="container-xl px-4">
            <!-- User Management -->
            <div class="mt-5 d-flex align-items-center gap-2">
                <i class="text-dark fa-solid fa-users"></i>
                <h2 class="mb-0 text-dark">Users List</h2>
            </div>
            <hr class="mt-0 mb-3" />
            <div class="align-items-center d-flex justify-content-end">
                <div class="mb-3">
                    <a class="btn btn-sm btn-success text-light" href="<?= base_url('sekum/users/create'); ?>">
                        <i class="me-1" data-feather="user-plus"></i>
                        Add New User
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>NPM</th>
                                <th>Email</th>
                                <th>Group</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user->fullname ?></td>
                                    <td><?= $user->npm ?></td>
                                    <td><?= $user->email ?></td>
                                    <td><?= $user->name ?></td>
                                    <td>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2"
                                            href="<?= base_url('sekum/users/edit/' . $user->userid); ?>"><i data-feather="edit"></i></a>
                                        <form class="d-inline"
                                            action="<?= base_url('sekum/users/delete/' . $user->userid); ?>" method="post">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <small> <button type="submit" class="btn btn-datatable btn-icon btn-transparent-dark " aria-label="Delete User"><i
                                                        data-feather="trash-2"></i></button></small>
                                        </form>
                                        <!-- <a class="btn btn-datatable btn-icon btn-transparent-dark" href="#!"></a> -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
<?= $this->section('page_js'); ?>
<!-- [PAGE JS] Simple Datatables — hanya dimuat di halaman Daftar User ini -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" defer></script>
<script src="<?= base_url(); ?>/js/datatables/datatables-simple-demo.js?v=1.0" defer></script>
<?= $this->endSection(); ?>

<?= $this->endSection() ?>