<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Informasi detail Program Kerja Kepanitiaan mahasiswa FASILKOM UNSIKA. Tinjau riwayat surat dan progres kelancaran administrasi dari satu layar antarmuka rapi.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>

<div id="layoutSidenav_content">
<main>
<div class="container-xl px-4 pb-5">

    <!-- ═══════════════════════════════════════════════
         HERO HEADER — Detail Proker
         ═══════════════════════════════════════════════ -->
    <div style="
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%);
        border-radius: 1rem;
        padding: 2rem 2.5rem;
        margin-top: 1.5rem;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    ">
        <!-- Decorative blobs -->
        <div style="position:absolute;top:-50px;right:-50px;width:220px;height:220px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
        <div style="position:absolute;bottom:-70px;right:100px;width:160px;height:160px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
        <div style="position:absolute;top:30px;right:200px;width:80px;height:80px;background:rgba(255,255,255,0.03);border-radius:50%;"></div>

        <div style="position:relative;z-index:1;">
            <!-- Breadcrumb -->
            <nav style="margin-bottom:1rem;">
                <ol style="display:flex;align-items:center;gap:.5rem;list-style:none;padding:0;margin:0;">
                    <li>
                        <a href="<?= base_url('admin') ?>"
                           style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.8rem;font-weight:500;transition:color .15s;"
                           onmouseover="this.style.color='#fff'"
                           onmouseout="this.style.color='rgba(255,255,255,.7)'">
                            <i class="fa-solid fa-house me-1" style="font-size:.7rem;"></i>Dashboard Utama
                        </a>
                    </li>
                    <li style="color:rgba(255,255,255,.4);font-size:.75rem;">/</li>
                    <li style="color:#fff;font-size:.8rem;font-weight:600;">Arsip Laci Kepanitiaan</li>
                </ol>
            </nav>

            <!-- Title row + Statistik -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                <!-- Kiri: icon + nama proker -->
                <div style="display:flex;align-items:center;gap:1rem;">
                    <div style="
                        width:52px;height:52px;
                        background:rgba(255,255,255,.15);
                        border-radius:.875rem;
                        display:flex;align-items:center;justify-content:center;
                        flex-shrink:0;backdrop-filter:blur(8px);
                    ">
                        <i class="fa-solid fa-folder-open" style="color:#fff;font-size:1.25rem;"></i>
                    </div>
                    <div>
                        <div style="color:rgba(255,255,255,.65);font-size:.7rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:.2rem;">
                            Program Kerja Organisasi
                        </div>
                        <h1 style="color:#fff;font-family:'Manrope',sans-serif;font-size:1.5rem;font-weight:800;margin:0;">
                            <?= esc($proker['nama']) ?>
                        </h1>
                    </div>
                </div>

                <!-- Kanan: Statistik Mini -->
                <div style="
                    display:flex;gap:1.5rem;text-align:center;
                    background:rgba(255,255,255,.1);
                    backdrop-filter:blur(8px);
                    border:1px solid rgba(255,255,255,.15);
                    border-radius:.75rem;
                    padding:.875rem 1.5rem;
                ">
                    <div>
                        <div style="font-size:1.75rem;font-weight:800;color:#fbbf24;line-height:1;"><?= $stats['pending'] ?></div>
                        <div style="font-size:.6rem;font-weight:700;letter-spacing:1px;color:rgba(255,255,255,.6);text-transform:uppercase;margin-top:.25rem;">Pending</div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,.2);"></div>
                    <div>
                        <div style="font-size:1.75rem;font-weight:800;color:#f87171;line-height:1;"><?= $stats['revisi'] ?></div>
                        <div style="font-size:.6rem;font-weight:700;letter-spacing:1px;color:rgba(255,255,255,.6);text-transform:uppercase;margin-top:.25rem;">Revisi</div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,.2);"></div>
                    <div>
                        <div style="font-size:1.75rem;font-weight:800;color:#34d399;line-height:1;"><?= $stats['approved'] ?></div>
                        <div style="font-size:.6rem;font-weight:700;letter-spacing:1px;color:rgba(255,255,255,.6);text-transform:uppercase;margin-top:.25rem;">Disetujui</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Arsip Surat -->
    <div class="card shadow-sm" style="border-radius: 1rem; border: none;">
        <div class="card-header bg-white py-4 px-4 d-flex align-items-center justify-content-between" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem; border-bottom: 1px solid #f1f5f9;">
            <h5 class="mb-0 fw-bold" style="color: #1e293b; font-family: 'Inter', sans-serif;">
                <i class="fa-solid fa-bars-staggered me-2" style="color: #3b82f6;"></i> Konsolidasi Riwayat Surat
            </h5>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill"><i class="fa-solid fa-hashtag me-1"></i> Total: <?= count($suratList) ?> Dokumen</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-muted text-uppercase" style="background-color: #f8fafc; font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-3 fw-bold">No. Resi & Perihal</th>
                            <th class="py-3 fw-bold">Tahap Dokumen</th>
                            <th class="py-3 fw-bold">Pengaju (Sekpel)</th>
                            <th class="py-3 fw-bold">Tanggal Kirim</th>
                            <th class="text-end pe-4 py-3 fw-bold">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($suratList)): ?>
                            <?php foreach($suratList as $surat): 
                                $badgeClass = 'bg-secondary';
                                $statusText = $surat['status'];
                                
                                if($surat['status'] == 'Pending') { 
                                    $badgeClass = 'bg-warning text-dark'; 
                                }
                                if($surat['status'] == 'Rejected') { 
                                    $badgeClass = 'bg-danger text-white'; 
                                    $statusText = 'Perlu Revisi'; 
                                }
                                if($surat['status'] == 'Approved') { 
                                    $badgeClass = 'bg-success text-white'; 
                                    $statusText = 'Disetujui';
                                }
                                if($surat['status'] == 'Draft') { 
                                    $badgeClass = 'bg-light text-muted border'; 
                                    $statusText = 'Masih Disusun (Draft)';
                                }
                            ?>
                            <tr class="<?= $surat['status']=='Pending' ? 'shadow-sm z-1 position-relative bg-white' : '' ?>" style="transition: all 0.2s; cursor: default;">
                                <!-- Kolom Info Surat -->
                                <td class="ps-4 py-3">
                                    <div class="fw-bold mb-1" style="color: #334155; font-size: 0.95rem;"><?= esc($surat['perihal']) ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-fingerprint me-1 opacity-50"></i>
                                        <?= $surat['nomor_surat'] ? esc($surat['nomor_surat']) : '<span class="fst-italic text-warning opacity-75">Menulis Nomor Saat Approval</span>' ?>
                                    </div>
                                </td>
                                
                                <!-- Kolom Status -->
                                <td class="py-3">
                                    <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.70rem;">
                                        <i class="fa-solid <?= $surat['status']=='Approved' ? 'fa-check' : ($surat['status']=='Pending' ? 'fa-clock' : 'fa-circle-dot') ?> me-1"></i>
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                
                                <!-- Kolom Sekpel -->
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold me-2 text-white" style="width: 30px; height: 30px; font-size: 0.7rem; background-color: <?= '#' . substr(md5($surat['pembuat_surat'] ?? 'x'), 0, 6) ?>;">
                                            <?= strtoupper(substr($surat['pembuat_surat'] ?? '?', 0, 1)) ?>
                                        </div>
                                        <span class="fw-semibold text-secondary" style="font-size: 0.85rem;"><?= esc($surat['pembuat_surat']) ?></span>
                                    </div>
                                </td>
                                
                                <!-- Kolom Waktu -->
                                <td class="py-3">
                                    <div class="fw-semibold text-secondary" style="font-size: 0.85rem;"><?= date('d M Y', strtotime($surat['updated_at'])) ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem;"><i class="fa-regular fa-clock me-1"></i><?= date('H:i', strtotime($surat['updated_at'])) ?> WIB</div>
                                </td>
                                
                                <!-- Kolom Aksi -->
                                <td class="text-end pe-4 py-3">
                                    <?php if($surat['status'] == 'Pending'): ?>
                                        <a href="<?= base_url('sekum/surat/review/' . $surat['public_id']) ?>" class="btn btn-sm text-white fw-bold px-4 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #3b82f6, #2563eb); font-size: 0.8rem;">
                                            Tinjau <i class="fa-solid fa-arrow-right ms-1"></i>
                                        </a>
                                    <?php elseif($surat['status'] == 'Rejected'): ?>
                                        <button class="btn btn-sm fw-bold px-4 rounded-pill border-0" disabled style="font-size: 0.8rem; background-color: #fef3c7; color: #92400e; cursor: not-allowed;">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Sedang Direvisi Sekpel
                                        </button>
                                    <?php elseif($surat['status'] == 'Approved'): ?>
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button type="button" onclick="printSuratDirectly('<?= base_url('sekum/surat/print/' . $surat['public_id']) ?>')" class="btn btn-sm btn-outline-success fw-bold px-4 rounded-pill shadow-sm" style="font-size: 0.8rem;">
                                                <i class="fa-solid fa-print me-1"></i> Preview
                                            </button>
                                            <?php if(($surat['jenis_surat'] ?? '') === 'non_elektronik' && !empty($surat['file_surat_fisik'])): ?>
                                                <a href="<?= base_url('uploads/arsip_surat/' . $surat['file_surat_fisik']) ?>" target="_blank" class="btn btn-sm btn-success fw-bold px-3 rounded-pill shadow-sm" title="Lihat Arsip Fisik" style="font-size: 0.8rem;">
                                                    <i class="fa-solid fa-file-circle-check"></i> Arsip
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <!-- Ini Draft (Hanya Sekpel yang bisa lihat detail) -->
                                        <button class="btn btn-sm btn-light text-muted fw-bold px-4 rounded-pill border-0" disabled style="font-size: 0.8rem;">Dikunci (Draft)</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-5">
                                        <i class="fa-regular fa-folder-open text-muted opacity-25 mb-3" style="font-size: 4rem;"></i>
                                        <h5 class="fw-bold" style="color: #64748b;">Belum Ada Arsip Terekam</h5>
                                        <p class="text-muted small mb-0">Panitia acara ini belum mulai mengirimkan permohonan surat ke level Sekum.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</main>
</div>

<!-- Iframe Tersembunyi untuk fitur "Langsung Print" -->
<iframe id="hiddenPrintFrame" style="display:none;" title="Print Surat"></iframe>

<script>
function printSuratDirectly(url) {
    // Tampilkan indikator loading kecil agar user tahu proses sedang berjalan
    const toast = document.createElement('div');
    toast.id = 'printLoadingToast';
    toast.innerHTML = '<div style="position:fixed;bottom:20px;right:20px;background:#334155;color:#fff;padding:12px 24px;border-radius:8px;z-index:9999;box-shadow:0 4px 6px rgba(0,0,0,0.1);"><i class="fa-solid fa-spinner fa-spin me-2"></i> Menyiapkan dokumen...</div>';
    document.body.appendChild(toast);

    var frame = document.getElementById('hiddenPrintFrame');
    // Memuat halaman cetak ke dalam iframe
    frame.src = url;

    // Menghapus indikator setelah dimuat
    frame.onload = function() {
        setTimeout(function() {
            var existingToast = document.getElementById('printLoadingToast');
            if(existingToast) existingToast.remove();
        }, 1500);
    };
}
</script>

<?= $this->endSection(); ?>
