<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Beranda dasbor utama Sekretaris Pelaksana BEM FASILKOM UNSIKA. Pantau status surat tertunda, revisi, dan final dengan antarmuka yang cepat serta intuitif.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>
<div id="layoutSidenav_content">

<?php
    $count_draft    = $count_draft ?? 0;
    $count_pending  = $count_pending ?? 0;
    $count_rejected = $count_rejected ?? 0;
    $count_approved = $count_approved ?? 0;
?>

<section class="px-8 py-8 pb-12">

    <?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-xl font-semibold mb-6 shadow-sm border border-red-100 flex items-center gap-3">
        <i class="fa-solid fa-circle-exclamation text-xl"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl font-semibold mb-6 shadow-sm border border-green-100 flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-xl"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <!-- Bento Grid Top Section -->
    <div class="grid grid-cols-12 gap-6 mb-10">

        <!-- Welcome Hero Card -->
        <div class="col-span-12 lg:col-span-8 bg-gradient-to-br from-primary to-primary-container rounded-3xl p-8 relative overflow-hidden flex flex-col justify-between min-h-[280px] shadow-lg">
            <div class="relative z-10">
                <span class="inline-flex items-center px-3 py-1 bg-white/20 text-white text-[10px] font-bold rounded-full mb-4 backdrop-blur-sm tracking-wider">
                    KEPANITIAAN AKTIF: <?= esc(strtoupper($proker_info['nama'] ?? 'BELUM DIATUR')) ?>
                </span>
                <h2 class="text-3xl font-extrabold text-white mb-2">Selamat Datang, <?= user()->fullname ?? 'Sekpel' ?>!</h2>
                <p class="text-white/80 text-sm mb-6 leading-relaxed max-w-xl">
                    Dashboard administrasi surat menyurat Anda sudah siap. Kelola pengajuan, revisi, dan pantau status surat kepanitiaan Anda dengan efisien hari ini.
                </p>
                <div class="flex gap-4 flex-wrap">
                    <a href="<?= base_url('sekpel/surat/create') ?>" class="px-6 py-3 bg-white text-primary font-bold rounded-xl shadow-lg hover:bg-slate-50 transition-colors text-sm flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-lg"></i> Buat Surat Baru
                    </a>
                    <a href="<?= base_url('sekpel/profil/edit') ?>" class="px-6 py-3 bg-white/20 backdrop-blur-md border border-white/20 text-white font-bold rounded-xl hover:bg-white/30 transition-colors text-sm flex items-center gap-2">
                        <i class="fa-solid fa-user-pen"></i> Edit Biodata
                    </a>
                </div>
            </div>
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-10 bottom-0 w-60 h-60 bg-blue-400/20 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <!-- Stats Bento (4 mini cards) -->
        <div class="col-span-12 lg:col-span-4 grid grid-cols-2 gap-4">
            <!-- Draft -->
            <div class="bg-white p-5 rounded-2xl flex flex-col justify-between border border-slate-100 shadow-sm transition hover:-translate-y-1">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Draft</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1"><?= str_pad($count_draft, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <div class="mt-3 flex items-end gap-2 text-slate-500">
                    <i class="fa-solid fa-file-pen text-sm"></i>
                    <?php if($count_draft > 0): ?><span class="text-[10px] font-bold">Active</span><?php endif; ?>
                </div>
            </div>
            <!-- Pending -->
            <div class="bg-white p-5 rounded-2xl flex flex-col justify-between border border-slate-100 shadow-sm transition hover:-translate-y-1">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Pending</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1"><?= str_pad($count_pending, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <div class="mt-3 flex items-end gap-2 text-amber-500">
                    <i class="fa-solid fa-hourglass-half text-sm"></i>
                    <?php if($count_pending > 0): ?><span class="text-[10px] font-bold">Latest</span><?php endif; ?>
                </div>
            </div>
            <!-- Rejected -->
            <div class="bg-white p-5 rounded-2xl flex flex-col justify-between border border-slate-100 shadow-sm transition hover:-translate-y-1">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Ditolak</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1"><?= str_pad($count_rejected, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <div class="mt-3 flex items-end gap-2 text-red-500">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    <?php if($count_rejected > 0): ?><span class="text-[10px] font-bold">Action Req</span><?php endif; ?>
                </div>
            </div>
            <!-- Approved -->
            <div class="bg-white p-5 rounded-2xl flex flex-col justify-between border border-slate-100 shadow-sm transition hover:-translate-y-1">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Disetujui</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1"><?= str_pad($count_approved, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <div class="mt-3 flex items-end gap-2 text-blue-600">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <?php if($count_approved > 0): ?><span class="text-[10px] font-bold">Final</span><?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Table Section Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-slate-900">Riwayat Pengajuan Surat</h3>
            <p class="text-sm text-slate-500 font-medium">Kelola dan pantau status surat yang telah dikirimkan.</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider">NO</th>
                        <th class="px-6 py-4 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider">PERIHAL</th>
                        <th class="px-6 py-4 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider">PENERIMA</th>
                        <th id="th-waktu" onclick="sortTable('waktu')"
                            class="px-6 py-4 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider cursor-pointer select-none hover:text-blue-600 transition-colors group">
                            WAKTU DIBUAT
                            <span id="icon-waktu" class="ml-1 opacity-40 group-hover:opacity-100">⇅</span>
                        </th>
                        <th id="th-status" onclick="sortTable('status')"
                            class="px-6 py-4 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider text-center cursor-pointer select-none hover:text-blue-600 transition-colors group">
                            STATUS
                            <span id="icon-status" class="ml-1 opacity-40 group-hover:opacity-100">⇅</span>
                        </th>
                        <th class="px-6 py-4 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($surat_sekpel)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-inbox text-4xl mb-2 block"></i>
                                <p class="font-medium text-sm">Belum ada pengajuan surat.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach($surat_sekpel as $rs): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-extrabold text-slate-400"><?= str_pad($i++, 2, '0', STR_PAD_LEFT) ?></td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-800 break-words max-w-[200px]"><?= esc($rs['perihal']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-500 font-medium break-words max-w-[150px]"><?= esc($rs['tujuan_surat']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-500 font-medium" data-sort-waktu="<?= strtotime($rs['created_at']) ?>"><?= date('d M Y, H:i', strtotime($rs['created_at'])) ?></td>
                            <td class="px-6 py-4 text-center" data-sort-status="<?= $rs['status'] ?>">
                                <?php
                                    if ($rs['status'] === 'Draft') {
                                        echo '<span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full">DRAFT</span>';
                                    } elseif ($rs['status'] === 'Pending') {
                                        echo '<span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full">MENUNGGU</span>';
                                    } elseif ($rs['status'] === 'Rejected') {
                                        echo '<span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded-full">REVISI</span>';
                                    } elseif ($rs['status'] === 'Approved') {
                                        echo '<span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full">DISETUJUI</span>';
                                    }
                                ?>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <?php if($rs['status'] === 'Draft'): ?>
                                    <a href="<?= base_url('sekpel/surat/edit/'.$rs['public_id']) ?>" class="p-2 inline-block text-slate-400 hover:text-blue-600 transition-colors" title="Edit Draft">
                                        <i class="fa-solid fa-pen text-lg"></i>
                                    </a>
                                <?php elseif($rs['status'] === 'Pending'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-full border border-amber-200">
                                        <i class="fa-solid fa-paper-plane text-[9px]"></i> TERKIRIM
                                    </span>
                                <?php elseif($rs['status'] === 'Rejected'): ?>
                                    <a href="<?= base_url('sekpel/surat/edit/'.$rs['public_id']) ?>" class="p-2 inline-block text-red-400 hover:text-red-600 transition-colors" title="Perbaiki Revisi">
                                        <i class="fa-solid fa-pen text-lg"></i>
                                    </a>
                                <?php elseif($rs['status'] === 'Approved'): ?>
                                    <button type="button" onclick="printSuratInDashboard('<?= base_url('sekpel/surat/print/'.$rs['public_id']) ?>')" class="p-2 inline-block text-slate-400 hover:text-blue-600 transition-colors" title="Cetak / Download PDF">
                                        <i class="fa-solid fa-download text-lg"></i>
                                    </button>
                                    <?php if($rs['jenis_surat'] === 'non_elektronik'): ?>
                                        <?php if(empty($rs['file_surat_fisik'])): ?>
                                            <button type="button" onclick="openUploadModal('<?= $rs['public_id'] ?>')" class="p-2 inline-block text-amber-500 hover:text-amber-600 transition-colors" title="Upload Arsip Surat Fisik">
                                                <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                            </button>
                                        <?php else: ?>
                                            <a href="<?= base_url('uploads/arsip_surat/' . $rs['file_surat_fisik']) ?>" target="_blank" class="p-2 inline-block text-green-500 hover:text-green-600 transition-colors" title="Lihat Arsip Fisik">
                                                <i class="fa-solid fa-file-circle-check text-lg"></i>
                                            </a>
                                            <button type="button" onclick="openUploadModal('<?= $rs['public_id'] ?>')" class="p-2 inline-block text-slate-400 hover:text-slate-600 transition-colors" title="Update Arsip Fisik">
                                                <i class="fa-solid fa-rotate text-sm"></i>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50/50 flex justify-between items-center rounded-xl mt-4 border border-slate-100">
            <p class="text-xs text-slate-500 font-medium">Menampilkan <?= count($surat_sekpel) ?> dari <?= count($surat_sekpel) ?> pengajuan.</p>
        </div>
    </div>

</section>

<!-- Modal Upload Arsip -->
<div id="uploadArsipModal" class="fixed inset-0 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0" style="z-index: 9999;" aria-hidden="true">
    <div class="bg-white rounded-3xl w-full max-w-md mx-4 p-8 shadow-2xl transform scale-95 transition-transform duration-300 relative">
        <button type="button" onclick="closeUploadModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="mb-6 text-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-file-arrow-up text-2xl"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">Upload Arsip Surat Fisik</h3>
            <p class="text-sm text-slate-500 mt-2">Unggah hasil scan/foto surat non-elektronik yang sudah ditandatangani.</p>
        </div>
        <form action="<?= base_url('sekpel/surat/upload-arsip') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="surat_id" id="modal_surat_id" value="">
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih File (PDF, JPG, PNG)</label>
                <div class="relative">
                    <input type="file" name="arsip_surat" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-xl cursor-pointer">
                </div>
                <p class="text-xs text-slate-400 mt-2">Maksimal ukuran file: 5MB.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeUploadModal()" class="w-full py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl shadow-sm hover:bg-blue-700 transition-colors">Upload</button>
            </div>
        </form>
    </div>
</div>

</div>
<?= $this->endSection(); ?>

<?= $this->section('page_js'); ?>
<script>
    function printSuratInDashboard(url) {
        let iframe = document.getElementById('printIframeDashboard');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'printIframeDashboard';
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
        }
        iframe.src = url;
    }

    // =============================================
    // SORT TABLE — Status & Waktu Dibuat
    // =============================================
    const sortState = { waktu: null, status: null };
    const statusOrder = { 'Draft': 1, 'Pending': 2, 'Rejected': 3, 'Approved': 4 };

    function sortTable(kolom) {
        // Toggle arah sort
        if (sortState[kolom] === 'asc') {
            sortState[kolom] = 'desc';
        } else {
            sortState[kolom] = 'asc';
        }
        // Reset ikon kolom lainnya
        const lainnya = kolom === 'waktu' ? 'status' : 'waktu';
        sortState[lainnya] = null;
        document.getElementById('icon-' + lainnya).textContent = '⇅';
        document.getElementById('th-' + lainnya).classList.remove('text-blue-600');

        // Update ikon kolom aktif
        const icon = document.getElementById('icon-' + kolom);
        icon.textContent = sortState[kolom] === 'asc' ? '↑' : '↓';
        document.getElementById('th-' + kolom).classList.add('text-blue-600');

        // Ambil semua baris
        const tbody = document.querySelector('table tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr[class]'));

        rows.sort(function(a, b) {
            let valA, valB;
            if (kolom === 'waktu') {
                valA = parseInt(a.querySelector('[data-sort-waktu]')?.dataset.sortWaktu || 0);
                valB = parseInt(b.querySelector('[data-sort-waktu]')?.dataset.sortWaktu || 0);
                return sortState[kolom] === 'asc' ? valA - valB : valB - valA;
            } else {
                valA = statusOrder[a.querySelector('[data-sort-status]')?.dataset.sortStatus] || 99;
                valB = statusOrder[b.querySelector('[data-sort-status]')?.dataset.sortStatus] || 99;
                return sortState[kolom] === 'asc' ? valA - valB : valB - valA;
            }
        });

        // Render ulang & update nomor urut
        rows.forEach(function(row, idx) {
            const noCell = row.querySelector('td:first-child');
            if (noCell) noCell.textContent = String(idx + 1).padStart(2, '0');
            tbody.appendChild(row);
        });
    }

    // Modal Upload Script
    const uploadModal = document.getElementById('uploadArsipModal');
    const suratIdInput = document.getElementById('modal_surat_id');
    
    // Pindahkan modal langsung ke body agar menutupi seluruh layar (lepas dari wrapper parent)
    document.body.appendChild(uploadModal);
    
    function openUploadModal(uuid) {
        suratIdInput.value = uuid;
        uploadModal.classList.remove('hidden');
        uploadModal.classList.add('flex');
        // trigger reflow
        void uploadModal.offsetWidth;
        uploadModal.classList.remove('opacity-0');
        uploadModal.children[0].classList.remove('scale-95');
    }

    function closeUploadModal() {
        uploadModal.classList.add('opacity-0');
        uploadModal.children[0].classList.add('scale-95');
        setTimeout(() => {
            uploadModal.classList.remove('flex');
            uploadModal.classList.add('hidden');
            suratIdInput.value = '';
        }, 300);
    }
</script>


<?= $this->endSection(); ?>
