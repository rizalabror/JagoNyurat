<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Beranda pusat kendali Sekretaris Umum BEM FASILKOM UNSIKA. Tinjau masuknya draft surat mahasiswa, berikan persetujuan persuratan, dan pantau pengurus aktif.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>
<div id="layoutSidenav_content">

<?php
    $total_surat = is_array($allSurat) ? count($allSurat) : 0;
    $active_proker_count = 0;
    foreach ($grouped_proker as $p) {
        if ($p['stats']['pending'] > 0 || $p['stats']['revisi'] > 0) $active_proker_count++;
    }
    $recent_surat = array_slice($allSurat ?? [], 0, 5);
?>

<div class="px-8 py-8 pb-12 space-y-6">

    <?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-xl font-semibold shadow-sm border border-red-100 flex items-center gap-3">
        <i class="fa-solid fa-circle-exclamation text-xl"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl font-semibold shadow-sm border border-green-100 flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-xl"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <!-- Bento Grid Top Section -->
    <div class="grid grid-cols-12 gap-6">

        <!-- Hero Welcome Card -->
        <div class="col-span-12 lg:col-span-8 bg-gradient-to-br from-primary to-primary-container rounded-3xl p-8 relative overflow-hidden flex flex-col justify-between min-h-[280px] shadow-lg">
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full backdrop-blur-sm">System Ready</span>
                <h2 class="text-3xl font-extrabold text-white mt-4 leading-tight">Selamat Datang,<br/><?= user()->fullname ?? 'Sekum' ?>!</h2>
                <p class="text-blue-100 mt-2 max-w-md text-sm">Pantau dan kelola seluruh pengajuan surat dari berbagai kepanitiaan BEM FASILKOM secara real-time.</p>
            </div>
            <div class="relative z-10 flex gap-3 mt-6 flex-wrap">
                <button onclick="document.getElementById('section-proker').scrollIntoView({behavior: 'smooth'})" class="bg-white text-primary px-6 py-3 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-slate-50 transition-colors shadow-sm">
                    <i class="fa-solid fa-file-signature text-lg"></i> Review Surat
                </button>
                <a href="<?= base_url('sekum/pengaturan') ?>" class="bg-white/20 backdrop-blur-md text-white border border-white/20 px-6 py-3 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-white/30 transition-colors">
                    <i class="fa-solid fa-gear"></i> Pengaturan
                </a>
            </div>
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-10 bottom-0 w-60 h-60 bg-blue-400/20 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <!-- Stats Bento (4 mini cards) -->
        <div class="col-span-12 lg:col-span-4 grid grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-2xl flex flex-col justify-between border border-slate-100 shadow-sm">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Total Surat</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1"><?= $total_surat ?></h3>
                </div>
                <div class="mt-3 flex items-end gap-2 text-blue-600">
                    <i class="fa-solid fa-file-lines text-sm"></i>
                    <span class="text-[10px] font-bold">All record</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl flex flex-col justify-between border border-slate-100 shadow-sm">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Pending</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1"><?= $count_pending ?? 0 ?></h3>
                </div>
                <div class="mt-3 flex items-end gap-2 text-amber-500">
                    <i class="fa-solid fa-hourglass-half text-sm"></i>
                    <span class="text-[10px] font-bold">Menunggu</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl flex flex-col justify-between border border-slate-100 shadow-sm">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Revisi</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1"><?= $count_revisi ?? 0 ?></h3>
                </div>
                <div class="mt-3 flex items-end gap-2 text-red-500">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    <span class="text-[10px] font-bold">Perlu aksi</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl flex flex-col justify-between border border-slate-100 shadow-sm">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Approved</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1"><?= $count_approved ?? 0 ?></h3>
                </div>
                <div class="mt-3 flex items-end gap-2 text-blue-600">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span class="text-[10px] font-bold">Disetujui</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row: Activity Feed + Departmental Volume -->
    <div class="grid grid-cols-12 gap-6">

        <!-- Activity Feed -->
        <div class="col-span-12 lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-100 flex flex-col shadow-sm">
            <div class="mb-5">
                <h3 class="font-extrabold text-base text-slate-900">Aktivitas Terbaru</h3>
            </div>
            <div class="overflow-y-auto max-h-72 space-y-4 pr-1">
                <?php if (empty($recent_surat)): ?>
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada aktivitas</p>
                <?php else: ?>
                    <?php foreach ($recent_surat as $rs): ?>
                    <div class="flex gap-3 items-start">
                        <?php
                            $actColor = 'bg-slate-100 text-slate-500';
                            $actIcon  = 'fa-file-lines';
                            if ($rs['status'] === 'Pending')  { $actColor = 'bg-orange-100 text-orange-600'; $actIcon = 'fa-hourglass-half'; }
                            if ($rs['status'] === 'Rejected') { $actColor = 'bg-red-100 text-red-600'; $actIcon = 'fa-clock-rotate-left'; }
                            if ($rs['status'] === 'Approved') { $actColor = 'bg-blue-100 text-blue-600'; $actIcon = 'fa-circle-check'; }
                        ?>
                        <div class="w-9 h-9 rounded-full <?= $actColor ?> flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid <?= $actIcon ?> text-[16px]"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate"><?= esc($rs['pembuat_surat']) ?></p>
                            <p class="text-xs text-slate-500 truncate"><?= esc($rs['perihal']) ?></p>
                            <p class="text-[10px] text-slate-400 mt-0.5 uppercase font-bold tracking-widest"><?= date('d M Y, H:i', strtotime($rs['updated_at'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Tampilkan Semua — di bawah list -->
            <a href="<?= base_url('sekum/aktivitas') ?>"
               class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-center gap-2 text-[12px] font-bold text-blue-600 hover:text-blue-800 transition-colors">
                Tampilkan Semua <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        <!-- ============================================================
             PROGRES SURAT PER KEPANITIAAN — ApexCharts Horizontal Bar
             ============================================================ -->
        <div class="col-span-12 lg:col-span-8 bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
            <div class="mb-6">
                <h3 class="font-extrabold text-lg text-slate-900">Progres Surat per Kepanitiaan</h3>
                <p class="text-sm text-slate-500 mt-1">Pemantauan status pengajuan surat dari setiap kepanitiaan</p>
            </div>
            <div id="apexChartStatistikSurat"></div>
        </div>
    </div>

    <!-- Committee Review Grid -->
    <div id="section-proker" class="scroll-mt-24">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-extrabold text-lg text-slate-900">Review per Kepanitiaan</h3>
                <p class="text-sm text-slate-500 mt-1">Kartu surat yang perlu ditindaklanjuti diurutkan berdasarkan prioritas.</p>
            </div>
        </div>

        <?php if (empty($grouped_proker)): ?>
            <div class="bg-white rounded-3xl border border-dashed border-slate-300 p-20 text-center">
                <i class="fa-solid fa-inbox text-slate-300 text-6xl mb-4 block"></i>
                <h3 class="text-lg font-bold text-slate-700">Belum ada surat masuk</h3>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($grouped_proker as $proker):
                    $isActive = ($proker['stats']['pending'] > 0 || $proker['stats']['revisi'] > 0);
                ?>
                <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden flex flex-col shadow-sm <?= !$isActive ? 'opacity-70' : '' ?>">
                    <!-- Card Header -->
                    <div class="p-6 bg-slate-50/50 border-b border-slate-100 flex justify-between items-start">
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-900 leading-tight"><?= esc($proker['nama']) ?></h3>
                        </div>
                        <div class="flex gap-4 text-center flex-shrink-0">
                            <div>
                                <p class="text-lg font-black <?= $proker['stats']['pending'] > 0 ? 'text-orange-600' : 'text-slate-300' ?> leading-none"><?= $proker['stats']['pending'] ?></p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter mt-1">Pending</p>
                            </div>
                            <div>
                                <p class="text-lg font-black <?= $proker['stats']['revisi'] > 0 ? 'text-red-600' : 'text-slate-300' ?> leading-none"><?= $proker['stats']['revisi'] ?></p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter mt-1">Revisi</p>
                            </div>
                            <div>
                                <p class="text-lg font-black <?= $proker['stats']['approved'] > 0 ? 'text-blue-600' : 'text-slate-300' ?> leading-none"><?= $proker['stats']['approved'] ?></p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter mt-1">Approved</p>
                            </div>
                        </div>
                    </div>

                    <!-- Surat List -->
                    <div class="p-6 flex-1">
                        <?php if ($isActive): ?>
                            <div class="space-y-3">
                                <?php foreach ($proker['surat_list'] as $surat):
                                    if ($surat['status'] !== 'Pending' && $surat['status'] !== 'Rejected') continue;
                                    $isPending = $surat['status'] === 'Pending';
                                    $iconBg   = $isPending ? 'bg-orange-100' : 'bg-red-50';
                                    $iconClr  = $isPending ? 'text-orange-600' : 'text-red-600';
                                    $badgeBg  = $isPending ? 'bg-orange-100 text-orange-600' : 'bg-red-100 text-red-600';
                                    $badgeTxt = $isPending ? 'Pending' : 'Revisi';
                                ?>
                                <div class="bg-slate-50 rounded-2xl p-4 flex items-center justify-between hover:bg-slate-100 transition-colors">
                                    <div class="flex items-center gap-4 min-w-0">
                                        <div class="w-11 h-11 <?= $iconBg ?> rounded-xl flex items-center justify-center <?= $iconClr ?> flex-shrink-0">
                                            <i class="fa-solid <?= $isPending ? 'fa-file-lines' : 'fa-clock-rotate-left' ?> text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-900 truncate"><?= esc($surat['perihal']) ?></p>
                                            <p class="text-[10px] text-slate-500">Oleh <span class="font-bold"><?= esc($surat['pembuat_surat']) ?></span></p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 <?= $badgeBg ?> text-[10px] font-bold rounded-full uppercase flex-shrink-0 ml-2"><?= $badgeTxt ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="border border-dashed border-slate-200 rounded-2xl py-10 flex flex-col items-center justify-center text-center">
                                <i class="fa-solid fa-clipboard-check text-slate-300 text-4xl mb-2"></i>
                                <p class="text-sm font-medium text-slate-400">Semua surat telah diproses</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Card Action -->
                    <div class="p-6 pt-0 flex items-center gap-4">
                        <?php
                            $firstReviewPublicId = '';
                            foreach ($proker['surat_list'] as $sl) {
                                if ($sl['status'] === 'Pending' || $sl['status'] === 'Rejected') {
                                    $firstReviewPublicId = $sl['public_id'];
                                    break;
                                }
                            }
                        ?>
                        <?php if ($isActive && !empty($firstReviewPublicId)): ?>
                            <a href="<?= base_url('sekum/surat/review/'.$firstReviewPublicId) ?>" class="flex-1 bg-blue-600 text-white font-bold text-sm py-3.5 rounded-xl shadow-sm hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                                <i class="fa-solid fa-file-signature text-[18px]"></i> Review Surat
                            </a>
                        <?php else: ?>
                            <button class="flex-1 bg-slate-100 text-slate-400 font-bold text-sm py-3.5 rounded-xl cursor-not-allowed flex items-center justify-center gap-2" disabled>
                                <i class="fa-solid fa-clipboard-check text-[18px]"></i> Tidak Ada Review
                            </button>
                        <?php endif; ?>
                        <a href="<?= base_url('sekum/kepanitiaan/'.$proker['public_id']) ?>" class="text-slate-500 font-bold text-sm hover:text-blue-600 transition-colors px-4 py-3.5 whitespace-nowrap">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div><!-- /Content Canvas -->
</div>
<?= $this->endSection(); ?>

<?= $this->section('page_js'); ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
(function () {
    const labels   = <?= $chartLabels ?>;
    const pending  = <?= $chartPending ?>;
    const rejected = <?= $chartRejected ?>;
    const approved = <?= $chartApproved ?>;

    if (labels.length === 0) {
        document.querySelector('#apexChartStatistikSurat').innerHTML =
            '<div class="flex flex-col items-center justify-center h-48 text-slate-400">' +
            '<i class="fa-solid fa-chart-bar text-4xl mb-3"></i>' +
            '<p class="text-sm font-medium">Belum ada data kepanitiaan</p>' +
            '</div>';
        return;
    }

    const dynamicHeight = Math.max(220, labels.length * 55);

    const options = {
        chart: {
            type: 'bar', height: dynamicHeight,
            fontFamily: 'Inter, sans-serif',
            toolbar: { show: false },
            animations: { enabled: true, speed: 600 }
        },
        plotOptions: {
            bar: { horizontal: true, borderRadius: 4, barHeight: '60%', dataLabels: { position: 'top' } }
        },
        series: [
            { name: 'Butuh Review',    data: pending  },
            { name: 'Direvisi Sekpel', data: rejected },
            { name: 'Disetujui',       data: approved }
        ],
        colors: ['#f59e0b', '#ef4444', '#3b82f6'],
        yaxis: {
            labels: { style: { fontSize: '11px', fontWeight: 600, colors: '#475569' }, maxWidth: 160 }
        },
        xaxis: {
            categories: labels,
            labels: {
                style: { fontSize: '11px', colors: '#94a3b8' },
                formatter: function(val) { return Number.isInteger(val) ? val : ''; }
            },
            axisBorder: { show: false }, axisTicks: { show: false }
        },
        legend: {
            show: true, position: 'top', horizontalAlign: 'right',
            fontSize: '12px', fontWeight: 600,
            markers: { size: 8, shape: 'square' },
            labels: { colors: '#475569' }
        },
        tooltip: {
            shared: true, intersect: false,
            y: { formatter: function(val) { return val + ' surat'; } }
        },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: false } } },
        dataLabels: { enabled: false }
    };

    new ApexCharts(document.querySelector('#apexChartStatistikSurat'), options).render();
})();
</script>
<?= $this->endSection(); ?>
