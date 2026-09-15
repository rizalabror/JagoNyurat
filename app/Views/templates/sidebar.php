<?php
/**
 * templates/sidebar.php
 * Modern Tailwind sidebar — menampilkan menu berbeda berdasarkan role (Sekum/Sekpel).
 * Di-include oleh templates/index.php untuk semua halaman internal.
 * Mendukung: desktop collapse (icon-only) & mobile overlay toggle.
 */
$currentUrl = current_url();
?>

<aside id="mainSidebar"
       class="fixed left-0 top-0 bg-slate-50 flex flex-col py-6 z-50 border-r border-slate-100 shadow-sm overflow-hidden transition-all duration-300"
       style="width:16rem; padding-left:1rem; padding-right:1rem; height:100vh;">

  <!-- Logo & App Name -->
  <div class="mb-8 px-2 flex items-center gap-3 min-w-0">
    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20 flex-shrink-0">
      <i class="fa-solid fa-file-signature text-white text-[20px]"></i>
    </div>
    <div class="sidebar-text overflow-hidden transition-all duration-300">
      <div class="text-base font-black text-blue-700 leading-none whitespace-nowrap">JagoNyurat</div>
      <?php if (in_groups('Sekum')): ?>
        <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-1 whitespace-nowrap">Sekretariat Umum</div>
      <?php elseif (in_groups(['Sekpel', 'sekpel'])): ?>
        <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-1 whitespace-nowrap">Sekretaris Pelaksana</div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Navigation Links -->
  <nav class="flex-1 space-y-1 overflow-y-auto overflow-x-hidden">

    <?php
    $activeClass   = 'flex items-center gap-3 px-4 py-3 rounded-lg bg-white text-blue-600 shadow-sm font-bold transition-all duration-200 text-sm sidebar-nav-item';
    $inactiveClass = 'flex items-center gap-3 px-4 py-3 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-200/50 transition-all duration-200 text-sm font-semibold sidebar-nav-item';
    ?>

    <?php if (in_groups('Sekum')): ?>
      <!-- ============ MENU SEKUM ============ -->
      <?php $isDashboard = rtrim($currentUrl, '/') === rtrim(base_url('sekum'), '/'); ?>
      <a href="<?= base_url('sekum') ?>" class="<?= $isDashboard ? $activeClass : $inactiveClass ?>" title="Dashboard">
        <i class="fa-solid fa-chart-pie text-[20px] <?= $isDashboard ? 'text-blue-600' : 'text-slate-500' ?> flex-shrink-0"></i>
        <span class="sidebar-text whitespace-nowrap overflow-hidden">Dashboard</span>
      </a>

      <!-- User Management Dropdown -->
      <details class="group" <?= str_contains($currentUrl, 'sekum/users') ? 'open' : '' ?>>
        <summary class="flex items-center justify-between px-4 py-3 text-slate-500 hover:text-slate-900 hover:bg-slate-200/50 rounded-lg transition-all duration-200 list-none cursor-pointer font-semibold text-sm sidebar-nav-item" title="User Management">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-users text-[20px] flex-shrink-0"></i>
            <span class="sidebar-text whitespace-nowrap overflow-hidden">User Management</span>
          </div>
          <i class="fa-solid fa-chevron-down text-[16px] group-open:rotate-180 transition-transform duration-200 sidebar-text"></i>
        </summary>
        <div class="mt-1 ml-7 pl-4 border-l border-slate-200 space-y-1 py-1 sidebar-text">
          <?php $isUserList = str_contains($currentUrl, 'sekum/users') && !str_contains($currentUrl, 'create') && !str_contains($currentUrl, 'edit'); ?>
          <a href="<?= base_url('sekum/users') ?>"
             class="block px-3 py-2 text-sm rounded-md transition-colors <?= $isUserList ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-100' ?>">
            List User
          </a>
          <?php $isAddUser = str_contains($currentUrl, 'sekum/users/create'); ?>
          <a href="<?= base_url('sekum/users/create') ?>"
             class="block px-3 py-2 text-sm rounded-md transition-colors <?= $isAddUser ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-100' ?>">
            Add User
          </a>
        </div>
      </details>

      <?php $isAktivitas = str_contains($currentUrl, 'sekum/aktivitas'); ?>
      <a href="<?= base_url('sekum/aktivitas') ?>" class="<?= $isAktivitas ? $activeClass : $inactiveClass ?>" title="Log Aktivitas">
        <i class="fa-solid fa-clock-rotate-left text-[20px] <?= $isAktivitas ? 'text-blue-600' : 'text-slate-500' ?> flex-shrink-0"></i>
        <span class="sidebar-text whitespace-nowrap overflow-hidden">Log Aktivitas</span>
      </a>

      <?php $isPengaturan = str_contains($currentUrl, 'sekum/pengaturan') || str_contains($currentUrl, 'sekum/identitas'); ?>
      <a href="<?= base_url('sekum/pengaturan') ?>" class="<?= $isPengaturan ? $activeClass : $inactiveClass ?>" title="Pengaturan Sistem">
        <i class="fa-solid fa-gear text-[20px] <?= $isPengaturan ? 'text-blue-600' : 'text-slate-500' ?> flex-shrink-0"></i>
        <span class="sidebar-text whitespace-nowrap overflow-hidden">Pengaturan Sistem</span>
      </a>

    <?php elseif (in_groups(['Sekpel', 'sekpel'])): ?>
      <!-- ============ MENU SEKPEL ============ -->
      <?php $isDashboard = rtrim($currentUrl, '/') === rtrim(base_url('/'), '/') || rtrim($currentUrl, '/') === rtrim(base_url('sekpel'), '/'); ?>
      <a href="<?= base_url('/') ?>" class="<?= $isDashboard ? $activeClass : $inactiveClass ?>" title="Dashboard">
        <i class="fa-solid fa-chart-pie text-[20px] <?= $isDashboard ? 'text-blue-600' : 'text-slate-500' ?> flex-shrink-0"></i>
        <span class="sidebar-text whitespace-nowrap overflow-hidden">Dashboard</span>
      </a>

      <?php $isCreate = str_contains($currentUrl, 'surat/create'); ?>
      <a href="<?= base_url('sekpel/surat/create') ?>" class="<?= $isCreate ? $activeClass : $inactiveClass ?>" title="Buat Surat Baru">
        <i class="fa-solid fa-pen-to-square text-[20px] <?= $isCreate ? 'text-blue-600' : 'text-slate-500' ?> flex-shrink-0"></i>
        <span class="sidebar-text whitespace-nowrap overflow-hidden">Buat Surat Baru</span>
      </a>

      <?php $isProfil = str_contains($currentUrl, 'profil'); ?>
      <a href="<?= base_url('sekpel/profil/edit') ?>" class="<?= $isProfil ? $activeClass : $inactiveClass ?>" title="Edit Biodata">
        <i class="fa-solid fa-user-pen text-[20px] <?= $isProfil ? 'text-blue-600' : 'text-slate-500' ?> flex-shrink-0"></i>
        <span class="sidebar-text whitespace-nowrap overflow-hidden">Edit Biodata</span>
      </a>

    <?php endif; ?>
  </nav>

  <!-- Bottom Section: Logout + User Info -->
  <div id="sidebarBottom" class="mt-auto space-y-3 pt-4 border-t border-slate-200 flex-shrink-0">
    <a href="<?= base_url('logout') ?>"
       class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 text-sm font-semibold sidebar-nav-item"
       title="Logout">
      <i class="fa-solid fa-right-from-bracket text-[20px] flex-shrink-0"></i>
      <span class="sidebar-text whitespace-nowrap overflow-hidden">Logout</span>
    </a>
  </div>

</aside>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"></div>