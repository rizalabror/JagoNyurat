<?php
/**
 * templates/topbar.php
 * Modern Tailwind topbar — hamburger toggle, breadcrumb, notifikasi lonceng, user info.
 * Di-include oleh templates/index.php untuk semua halaman internal.
 */
?>

<header id="mainTopbar"
        class="fixed top-0 right-0 h-14 bg-white/90 backdrop-blur-md
               flex justify-between items-center px-4 z-40 border-b border-slate-100 shadow-sm transition-all duration-300"
        style="width:calc(100% - 16rem);">

  <!-- Kiri: Hamburger + Breadcrumb -->
  <div class="flex items-center gap-3">
    <button id="sidebarToggle"
            class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors flex-shrink-0"
            aria-label="Toggle Sidebar">
      <i class="fa-solid fa-bars text-[18px]"></i>
    </button>
    <div class="flex items-center gap-2 text-slate-400">
      <i class="fa-solid fa-file-signature text-[18px]"></i>
      <span class="text-sm font-semibold text-slate-500">JagoNyurat</span>
      <span class="text-slate-300">/</span>
      <span class="text-sm text-slate-400">
        <?php
        if (in_groups('Sekum')) echo 'Sekretariat Umum';
        elseif (in_groups(['Sekpel', 'sekpel'])) echo 'Sekretaris Pelaksana';
        else echo 'Dashboard';
        ?>
      </span>
    </div>
  </div>

  <!-- Kanan: Lonceng Notifikasi + User info + Foto -->
  <div class="flex items-center gap-3">

    <!-- ============ NOTIFICATION BELL ============ -->
    <div class="relative" id="notifWrapper">
      <!-- Bell Button -->
      <button id="notifBtn"
              class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors relative"
              aria-label="Notifikasi">
        <i class="fa-solid fa-bell text-[18px]"></i>
        <!-- Badge jumlah unread -->
        <span id="notifBadge"
              class="hidden absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center leading-none">
          0
        </span>
      </button>

      <!-- Dropdown Notifikasi -->
      <div id="notifDropdown"
           class="hidden absolute right-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden">
        <!-- Header Dropdown -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
          <h4 class="text-sm font-extrabold text-slate-900">Notifikasi</h4>
          <button id="notifMarkAll"
                  class="text-[11px] font-bold text-blue-600 hover:text-blue-800 transition-colors">
            Tandai Semua Dibaca
          </button>
        </div>
        <!-- List Notifikasi -->
        <div id="notifList" class="max-h-72 overflow-y-auto divide-y divide-slate-50">
          <p class="text-center text-sm text-slate-400 py-8">Memuat...</p>
        </div>
        <!-- Footer -->
        <div class="px-4 py-3 border-t border-slate-100 text-center">
          <p class="text-[11px] text-slate-400">Notifikasi tersimpan 30 hari terakhir</p>
        </div>
      </div>
    </div>
    <!-- ============ END NOTIFICATION BELL ============ -->

    <div class="text-right hidden sm:block">
      <p class="text-xs font-semibold text-slate-700 leading-none">
        <?= user() ? esc(user()->fullname) : 'User' ?>
      </p>
      <p class="text-[10px] text-slate-400 mt-1">
        <?php
        if (in_groups('Sekum')) echo 'Sekretariat Umum';
        elseif (in_groups(['Sekpel', 'sekpel'])) echo 'Sekretaris Pelaksana';
        ?>
      </p>
    </div>

    <?php
    $fotoProfil = (user() && !empty(user()->user_image)) ? user()->user_image : null;
    $fotoSrc    = $fotoProfil
                  ? base_url('assets/img/profiles/' . $fotoProfil)
                  : base_url('assets/img/default-avatar.png');
    ?>
    <img alt="Foto Profil"
         class="w-9 h-9 rounded-full object-cover border-2 border-slate-200 shadow-sm bg-slate-100"
         src="<?= $fotoSrc ?>"
         onerror="this.src='<?= base_url('assets/img/default-avatar.png') ?>'" />
  </div>

</header>

<!-- ============================================================ -->
<!-- NOTIFICATION JS — fetch list, badge, mark read              -->
<!-- ============================================================ -->
<script>
(function () {
  const btn        = document.getElementById('notifBtn');
  const dropdown   = document.getElementById('notifDropdown');
  const badge      = document.getElementById('notifBadge');
  const list       = document.getElementById('notifList');
  const markAllBtn = document.getElementById('notifMarkAll');
  const baseUrl    = '<?= base_url() ?>';

  const typeIcon = {
    'new_surat'      : { icon: 'fa-file-lines',        color: 'text-orange-500' },
    'surat_approved' : { icon: 'fa-circle-check',      color: 'text-blue-500'   },
    'surat_rejected' : { icon: 'fa-clock-rotate-left', color: 'text-red-500'    },
    'info'           : { icon: 'fa-bell',               color: 'text-slate-400'  },
  };

  function timeAgo(dateStr) {
    const diff = Math.floor((Date.now() - new Date(dateStr).getTime()) / 1000);
    if (diff < 60)  return diff + 'd lalu';
    if (diff < 3600) return Math.floor(diff / 60) + 'mnt lalu';
    if (diff < 86400) return Math.floor(diff / 3600) + 'jam lalu';
    return Math.floor(diff / 86400) + 'hr lalu';
  }

  function fetchNotif() {
    fetch(baseUrl + '/notifikasi/list', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(r => r.json())
      .then(data => {
        // Update badge
        if (data.unread > 0) {
          badge.classList.remove('hidden');
          badge.textContent = data.unread > 9 ? '9+' : data.unread;
        } else {
          badge.classList.add('hidden');
        }

        // Render list
        if (!data.items || data.items.length === 0) {
          list.innerHTML = '<p class="text-center text-sm text-slate-400 py-8">Tidak ada notifikasi</p>';
          return;
        }

        list.innerHTML = data.items.map(n => {
          const meta  = typeIcon[n.type] || typeIcon['info'];
          const unreadClass = n.is_read == 0 ? 'bg-blue-50/60' : '';
          return `<div class="flex gap-3 px-4 py-3 hover:bg-slate-50 transition-colors cursor-pointer ${unreadClass} notif-item"
                       data-id="${n.id}" data-ref="${n.reference_id || ''}">
            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid ${meta.icon} text-[13px] ${meta.color}"></i>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs font-semibold text-slate-800 leading-snug">${n.message}</p>
              <p class="text-[10px] text-slate-400 mt-0.5">${timeAgo(n.created_at)}</p>
            </div>
            ${n.is_read == 0 ? '<span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1"></span>' : ''}
          </div>`;
        }).join('');

        // Attach click handlers
        list.querySelectorAll('.notif-item').forEach(el => {
          el.addEventListener('click', function () {
            const id  = this.dataset.id;
            const ref = this.dataset.ref;
            fetch(baseUrl + '/notifikasi/baca/' + id, {
              method: 'POST',
              headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': getCsrf() }
            }).then(() => fetchNotif());
          });
        });
      })
      .catch(() => {
        list.innerHTML = '<p class="text-center text-sm text-slate-400 py-6">Gagal memuat notifikasi</p>';
      });
  }

  function getCsrf() {
    return '<?= csrf_hash() ?>';
  }

  // Toggle dropdown
  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    dropdown.classList.toggle('hidden');
    if (!dropdown.classList.contains('hidden')) fetchNotif();
  });

  // Tutup dropdown saat klik luar
  document.addEventListener('click', function (e) {
    if (!document.getElementById('notifWrapper').contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });

  // Tandai semua dibaca
  markAllBtn.addEventListener('click', function () {
    fetch(baseUrl + '/notifikasi/baca-semua', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': getCsrf() }
    }).then(() => fetchNotif());
  });

  // Polling otomatis tiap 30 detik untuk update badge
  fetchNotif();
  setInterval(fetchNotif, 30000);
})();
</script>