<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Log aktivitas seluruh pengajuan surat di sistem JagoNyurat BEM FASILKOM UNSIKA.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>
<div id="layoutSidenav_content">
<section class="px-8 py-8 pb-12 max-w-5xl mx-auto">

    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="<?= base_url('sekum') ?>" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-blue-600 transition-colors mb-2">
                <i class="fa-solid fa-arrow-left text-[13px]"></i> Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900">Log Aktivitas</h1>
            <p class="text-sm text-slate-500 mt-1">Seluruh riwayat aktivitas surat — total <strong><?= $totalRows ?></strong> entri.</p>
        </div>

        <!-- Filter Bar -->
        <div class="flex items-center gap-2 flex-wrap">

            <?php
            $btnBase = 'px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-colors border';
            $inactiveClass = 'bg-white border-slate-200 text-slate-600';
            $activeColors = [
                'all'      => '#475569', // slate-600
                'pending'  => '#f59e0b', // amber-500
                'approved' => '#2563eb', // blue-600
                'rejected' => '#ef4444', // red-500
            ];
            $filters = [
                'all'      => ['label' => 'Semua',    'icon' => 'fa-list'],
                'pending'  => ['label' => 'Pending',  'icon' => 'fa-hourglass-half'],
                'approved' => ['label' => 'Disetujui','icon' => 'fa-circle-check'],
                'rejected' => ['label' => 'Revisi',   'icon' => 'fa-clock-rotate-left'],
            ];
            foreach ($filters as $key => $f):
                $isActive = ($statusFilter === $key);
            ?>
            <a href="<?= base_url('sekum/aktivitas?status=' . $key) ?>"
               class="<?= $btnBase ?> <?= $isActive ? 'text-white border-transparent shadow-sm' : $inactiveClass ?>"
               <?= $isActive ? 'style="background-color:' . $activeColors[$key] . ';"' : '' ?>>
                <i class="fa-solid <?= $f['icon'] ?> text-[13px]"></i>
                <?= $f['label'] ?>
            </a>
            <?php endforeach; ?>

        </div>


    </div>

    <!-- Activity List -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <?php if (empty($logs)): ?>
            <div class="py-20 text-center">
                <i class="fa-solid fa-inbox text-slate-300 text-5xl mb-3 block"></i>
                <p class="text-slate-400 font-medium">Tidak ada aktivitas ditemukan.</p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-slate-100">
                <?php foreach ($logs as $row):
                    $actColor = 'bg-slate-100 text-slate-500';
                    $actIcon  = 'fa-file-lines';
                    $badgeBg  = 'bg-slate-100 text-slate-600';
                    $badgeTxt = 'Draft';
                    if ($row['status'] === 'Pending')  {
                        $actColor = 'bg-orange-100 text-orange-600';
                        $actIcon  = 'fa-hourglass-half';
                        $badgeBg  = 'bg-orange-100 text-orange-700';
                        $badgeTxt = 'Pending';
                    } elseif ($row['status'] === 'Approved') {
                        $actColor = 'bg-blue-100 text-blue-600';
                        $actIcon  = 'fa-circle-check';
                        $badgeBg  = 'bg-blue-100 text-blue-700';
                        $badgeTxt = 'Disetujui';
                    } elseif ($row['status'] === 'Rejected') {
                        $actColor = 'bg-red-100 text-red-600';
                        $actIcon  = 'fa-clock-rotate-left';
                        $badgeBg  = 'bg-red-100 text-red-700';
                        $badgeTxt = 'Revisi';
                    }
                ?>
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
                    <!-- Icon -->
                    <div class="w-10 h-10 rounded-full <?= $actColor ?> flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid <?= $actIcon ?> text-[15px]"></i>
                    </div>
                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate"><?= esc($row['pembuat_surat']) ?></p>
                        <p class="text-xs text-slate-500 truncate"><?= esc($row['perihal']) ?></p>
                        <?php if (!empty($row['nama_proker'])): ?>
                        <p class="text-[10px] text-slate-400 mt-0.5">Kepanitiaan: <span class="font-semibold"><?= esc($row['nama_proker']) ?></span></p>
                        <?php endif; ?>
                    </div>
                    <!-- Badge Status -->
                    <span class="px-3 py-1 <?= $badgeBg ?> text-[10px] font-bold rounded-full uppercase flex-shrink-0">
                        <?= $badgeTxt ?>
                    </span>
                    <!-- Waktu -->
                    <div class="text-right flex-shrink-0 hidden sm:block">
                        <p class="text-[11px] font-semibold text-slate-500"><?= date('d M Y', strtotime($row['updated_at'])) ?></p>
                        <p class="text-[10px] text-slate-400"><?= date('H:i', strtotime($row['updated_at'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-400 font-medium">
                    Halaman <?= $currentPage ?> dari <?= $totalPages ?>
                </p>
                <div class="flex items-center gap-2">
                    <?php if ($currentPage > 1): ?>
                    <a href="<?= base_url('sekum/aktivitas?status=' . $statusFilter . '&page=' . ($currentPage - 1)) ?>"
                       class="px-3 py-1.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                        ← Prev
                    </a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <a href="<?= base_url('sekum/aktivitas?status=' . $statusFilter . '&page=' . $i) ?>"
                       class="px-3 py-1.5 text-sm font-semibold rounded-lg transition-colors <?= $i === $currentPage ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 bg-white border border-slate-200 hover:bg-slate-50' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= base_url('sekum/aktivitas?status=' . $statusFilter . '&page=' . ($currentPage + 1)) ?>"
                       class="px-3 py-1.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                        Next →
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</section>
</div>
<?= $this->endSection(); ?>
