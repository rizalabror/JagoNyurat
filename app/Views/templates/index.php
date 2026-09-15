<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <?php $metaDesc = $this->renderSection('meta_description'); ?>
  <meta name="description" content="<?= !empty($metaDesc) ? esc($metaDesc) : 'Sistem Administrasi Surat BEM FASILKOM UNSIKA. Platform digital untuk kelola birokrasi persuratan kepanitiaan secara cepat, rapi, dan terstruktur.' ?>" />
  <title>JagoNyurat</title>

  <!-- [GLOBAL CSS] Tailwind — satu-satunya CSS framework yang digunakan -->
  <link href="<?= base_url('css/tailwind.min.css') ?>" rel="stylesheet" />
  <!-- [LCP OPTIMASI] Preload Font Awesome Core (Solid) agar merender ikon super cepat -->
  <link rel="preload" href="<?= base_url('fontawesome/webfonts/fa-solid-900.woff2') ?>" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?= base_url('fontawesome/webfonts/fa-regular-400.woff2') ?>" as="font" type="font/woff2" crossorigin>

  <!-- [GLOBAL CSS] Font Awesome — self-hosted -->
  <link href="<?= base_url('fontawesome/css/all.min.css') ?>" rel="stylesheet" />

  <!-- [GLOBAL CSS] Google Fonts — preconnect -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <!-- Menggunakan &display=swap agar text langsung muncul tanpa menunggu font selesai terunduh (swap) -->
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
  
  <!-- [NON-BLOCKING] Material Symbols sangat rawan memblokir render karena berat, load async menggunakan trik media=print -> all -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"></noscript>

  <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>/assets/img/jagoNyuratBG.png" />

  <!-- [PAGE CSS] Injeksi CSS spesifik per-halaman (isi dari view masing-masing) -->
  <?= $this->renderSection('page_css'); ?>

  <!-- Override: netralkan konflik Bootstrap SB Admin flex agar tidak bentrok dengan Tailwind pl-64 -->
  <style>
    body { background-color: #f8fafc; }
    #layoutSidenav_content { min-width: 0; width: 100%; }
    .sidenav { display: none !important; }
    #layoutSidenav { display: block !important; }
    .topnav { display: none !important; }
  </style>
</head>

<body>
  <!-- ============================================================ -->
  <!-- Modern Tailwind Sidebar (role-aware, lihat templates/sidebar) -->
  <!-- ============================================================ -->
  <?= $this->include('templates/sidebar'); ?>

  <!-- ============================================================ -->
  <!-- Main Content Area: digeser 256px dari kiri (lebar sidebar)  -->
  <!-- ============================================================ -->
  <div id="mainContent" class="min-h-screen bg-slate-50 transition-all duration-300" style="padding-left:16rem;">

    <!-- Modern Tailwind Topbar -->
    <?= $this->include('templates/topbar'); ?>

    <!-- Page Content: pt-14 untuk kompensasi tinggi topbar fixed -->
    <div class="pt-14">
      <?= $this->renderSection('page-content'); ?>
    </div>

  </div>

  <!-- ============================================================ -->
  <!-- [GLOBAL JS] Non-critical, semuanya pakai "defer" agar tidak  -->
  <!-- render-blocking dan halaman langsung tampil ke pengguna      -->
  <!-- ============================================================ -->

  <!-- Feather Icons: ikon SVG ringan, self-hosted lebih baik tapi CDN ok karena kecil -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" defer></script>
  <!-- Bootstrap JS dihapus — tidak lagi dibutuhkan setelah migrasi full Tailwind -->
  <!-- data-bs-dismiss diganti dengan vanilla JS onclick di view masing-masing -->
  <script src="<?= base_url(); ?>/js/scripts.js?v=1.0" defer></script>

  <!-- ============================================================ -->
  <!-- [SIDEBAR TOGGLE JS] Inline agar berjalan sebelum page_js   -->
  <!-- Mendukung: desktop collapse (icon-only) & mobile overlay    -->
  <!-- ============================================================ -->
  <script>
  (function () {
    const SIDEBAR_KEY    = 'jn_sidebar_collapsed';
    const SIDEBAR_W_FULL = '16rem';   // 256px = w-64
    const SIDEBAR_W_ICON = '4.5rem';  // icon-only

    const sidebar       = document.getElementById('mainSidebar');
    const topbar        = document.getElementById('mainTopbar');
    const content       = document.getElementById('mainContent');
    const toggleBtn     = document.getElementById('sidebarToggle');
    const overlay       = document.getElementById('sidebarOverlay');
    const sidebarBottom = document.getElementById('sidebarBottom');
    const texts         = document.querySelectorAll('.sidebar-text');
    const navItems      = document.querySelectorAll('.sidebar-nav-item');

    function isMobile() {
      return window.innerWidth < 768;
    }

    // ── Desktop: collapse / expand ─────────────────────────────────
    function setDesktopState(collapsed) {
      if (collapsed) {
        sidebar.style.width         = SIDEBAR_W_ICON;
        sidebar.style.paddingLeft   = '0.625rem';
        sidebar.style.paddingRight  = '0.625rem';
        topbar.style.width          = 'calc(100% - ' + SIDEBAR_W_ICON + ')';
        content.style.paddingLeft   = SIDEBAR_W_ICON;
        texts.forEach(el => { el.style.opacity = '0'; el.style.width = '0'; el.style.overflow = 'hidden'; });
        navItems.forEach(el => el.style.justifyContent = 'center');
      } else {
        sidebar.style.width         = SIDEBAR_W_FULL;
        sidebar.style.paddingLeft   = '1rem';
        sidebar.style.paddingRight  = '1rem';
        topbar.style.width          = 'calc(100% - ' + SIDEBAR_W_FULL + ')';
        content.style.paddingLeft   = SIDEBAR_W_FULL;
        texts.forEach(el => { el.style.opacity = ''; el.style.width = ''; el.style.overflow = ''; });
        navItems.forEach(el => el.style.justifyContent = '');
      }
      sidebar.dataset.collapsed = collapsed ? 'true' : 'false';
      localStorage.setItem(SIDEBAR_KEY, collapsed ? '1' : '0');
    }

    // ── Mobile: full-width slide in/out (WITH overlay) ────────────────
    function openMobile() {
      // Tampilkan sidebar full dari kiri
      sidebar.style.transform    = 'translateX(0)';
      sidebar.style.width        = SIDEBAR_W_FULL;
      sidebar.style.paddingLeft  = '1rem';
      sidebar.style.paddingRight = '1rem';
      topbar.style.width         = '100%';
      topbar.style.left          = '0';
      content.style.paddingLeft  = '0';
      // Tampilkan teks
      texts.forEach(el => { el.style.opacity = ''; el.style.width = ''; el.style.overflow = ''; });
      navItems.forEach(el => el.style.justifyContent = '');
      overlay.classList.remove('hidden'); // Munculkan overlay
    }

    function closeMobile() {
      sidebar.style.transform    = 'translateX(-100%)';
      topbar.style.width         = '100%';
      topbar.style.left          = '0';
      content.style.paddingLeft  = '0';
      overlay.classList.add('hidden');
    }

    function applyMobileState() {
      // Mobile default: sidebar tersembunyi, topbar full width
      sidebar.style.width        = SIDEBAR_W_FULL;
      sidebar.style.paddingLeft  = '1rem';
      sidebar.style.paddingRight = '1rem';
      sidebar.style.transform    = 'translateX(-100%)';
      topbar.style.width         = '100%';
      topbar.style.left          = '0';
      content.style.paddingLeft  = '0';
      texts.forEach(el => { el.style.opacity = ''; el.style.width = ''; el.style.overflow = ''; });
      navItems.forEach(el => el.style.justifyContent = '');
    }

    // ── Init ───────────────────────────────────────────────────────
    function init() {
      if (isMobile()) {
        applyMobileState();
      } else {
        sidebar.style.transform = '';
        topbar.style.left       = '';
        const collapsed = localStorage.getItem(SIDEBAR_KEY) === '1';
        setDesktopState(collapsed);
      }
    }

    // ── Toggle Button ──────────────────────────────────────────────
    toggleBtn.addEventListener('click', function () {
      if (isMobile()) {
        const isOpen = sidebar.style.transform === 'translateX(0px)'
                    || sidebar.style.transform === 'translateX(0)';
        isOpen ? closeMobile() : openMobile();
      } else {
        const isCollapsed = sidebar.dataset.collapsed === 'true';
        setDesktopState(!isCollapsed);
      }
    });

    // ── Overlay click closes mobile sidebar ────────────────────────
    overlay.addEventListener('click', closeMobile);

    // ── Auto-expand sidebar when clicking submenu in collapsed mode ─
    document.querySelectorAll('details.group summary').forEach(summary => {
      summary.addEventListener('click', function(e) {
        if (!isMobile() && sidebar.dataset.collapsed === 'true') {
          e.preventDefault(); // Cegah toggle asli
          setDesktopState(false); // Expand sidebar
          this.parentElement.open = true; // Buka submenu
        }
      });
    });

    // ── Resize guard ───────────────────────────────────────────────
    let resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        if (isMobile()) {
          applyMobileState();
        } else {
          overlay.classList.add('hidden');
          const collapsed = localStorage.getItem(SIDEBAR_KEY) === '1';
          sidebar.style.transform = '';
          topbar.style.left       = '';
          setDesktopState(collapsed);
        }
      }, 100);
    });

    init();
  })();
  </script>

  <!-- ============================================================ -->
  <!-- [PAGE JS] Injeksi skrip spesifik per-halaman                 -->
  <!-- Datatables, TinyMCE, dll. TIDAK dimuat di sini secara global -->
  <!-- Masing-masing view mendaftarkan asetnya sendiri di sini      -->
  <!-- ============================================================ -->
  <?= $this->renderSection('page_js'); ?>
</body>

</html>