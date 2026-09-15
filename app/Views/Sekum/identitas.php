<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Kelola identitas utama Sekretaris Umum BEM FASILKOM UNSIKA. Perbarui biodata, identitas riwayat, serta spesimen tanda tangan asli untuk legitimasi di dokumen.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>
<div id="layoutSidenav_content">
    <main>
        <!-- Menggunakan container-xl agar lebar form proporsional (tidak kekecilan, tidak kebesaran) -->
        <div class="container-xl px-4 px-lg-5 mt-5 mb-5">
            
            <!-- Pesan Notifikasi -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 bg-success text-white mb-4" role="alert" style="border-radius: 0.75rem;">
                    <i class="fa-solid fa-circle-check me-2"></i><strong>Berhasil!</strong> <?= session()->getFlashdata('success'); ?>
                    <button type="button" class="btn-close btn-close-white" onclick="this.closest('.alert').remove()" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 bg-danger text-white mb-4" role="alert" style="border-radius: 0.75rem;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Gagal!</strong> <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="btn-close btn-close-white" onclick="this.closest('.alert').remove()" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 bg-danger text-white mb-4" role="alert" style="border-radius: 0.75rem;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Periksa kembali!</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        <?php foreach ((array)session()->getFlashdata('errors') as $e): ?>
                            <li><?= esc($e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close btn-close-white" onclick="this.closest('.alert').remove()" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- TATA LETAK ATAS BAWAH (1 Kolom Penuh) -->
            <div class="d-flex flex-column gap-4">
                
                <!-- Kotak Kontak Sekretariat -->
                <div class="card shadow-sm border-0" style="border-radius: 1rem;">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold text-dark text-uppercase mb-0" style="font-size: 0.85rem; letter-spacing: 0.8px;"><i class="fa-solid fa-address-book text-primary me-2"></i>Kontak Sekretariat</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= base_url('sekum/update-kop'); ?>" method="POST">
                            <?= csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-12 mb-2">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.75rem;">Alamat Fisik</label>
                                    <textarea class="form-control form-control-sm bg-light border-0" id="sekretariat" name="sekretariat" rows="2" style="resize: none;"><?= esc($kop_kontak['sekretariat'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.75rem;">Garis Darurat (Telp/WA)</label>
                                    <input class="form-control form-control-sm bg-light border-0" id="nohp" name="nohp" type="text" value="<?= esc($kop_kontak['nohp'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.75rem;">Alamat Surel (Email)</label>
                                    <input class="form-control form-control-sm bg-light border-0" id="email" name="email" type="email" value="<?= esc($kop_kontak['email'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.75rem;">URL Situs Web</label>
                                    <input class="form-control form-control-sm bg-light border-0" id="website" name="website" type="text" value="<?= esc($kop_kontak['website'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="mt-4 pt-2 text-end border-top">
                                <button class="btn btn-primary bg-primary text-white fw-bold shadow-sm px-4 mt-3" type="submit" style="letter-spacing: 0.5px; border-radius: 0.5rem;">
                                    <i class="fa-solid fa-cloud-arrow-up me-2"></i> SIMPAN KONTAK
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kotak Struktur Mahkota Kop Surat -->
                <div class="card shadow-sm border-0" style="border-radius: 1rem;">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold text-dark text-uppercase mb-0" style="font-size: 0.85rem; letter-spacing: 0.8px;"><i class="fa-solid fa-sitemap text-primary me-2"></i>Pengaturan Kop Surat</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($kop_texts as $item): ?>
                                <div class="p-2 bg-light rounded-3 border">
                                    <form action="<?= base_url('sekum/update-pejabat'); ?>" method="POST" class="d-flex align-items-center flex-wrap flex-sm-nowrap gap-3 mb-0">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="jabatan" value="<?= $item['jabatan']; ?>">
                                        
                                        <!-- Label Kolom -->
                                        <div class="px-2" style="min-width: 180px;">
                                            <span class="fw-bold text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;"><?= esc($item['jabatan']); ?></span>
                                        </div>
                                        
                                        <!-- Input Teks -->
                                        <div class="flex-grow-1 w-100">
                                            <input class="form-control bg-white border-0 shadow-sm" name="nama" type="text" value="<?= esc($item['nama']); ?>" required placeholder="Masukkan nama hirarki...">
                                        </div>
                                        
                                        <!-- Tombol -->
                                        <div>
                                            <button class="btn btn-dark bg-dark text-white fw-bold px-4 shadow-sm" type="submit" title="Simpan Baris Ini" style="border-radius: 0.5rem;">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Kotak Otoritas Penandatanganan Mutlak -->
                <div class="card shadow-sm border-0" style="border-radius: 1rem;">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold text-dark text-uppercase mb-0" style="font-size: 0.85rem; letter-spacing: 0.8px;"><i class="fa-solid fa-file-signature text-primary me-2"></i>Otoritas Penandatangan</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <?php foreach ($pejabat as $p): ?>
                                <div class="col-md-6">
                                    <!-- Kotak Petinggi -->
                                    <div class="p-4 bg-light rounded-3 border h-100 position-relative border-start-lg border-start-primary">
                                        <form action="<?= base_url('sekum/update-pejabat'); ?>" method="POST" class="d-flex flex-column h-100">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="jabatan" value="<?= $p['jabatan']; ?>">
                                            
                                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                                <span class="fw-bold text-dark text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;"><i class="fa-solid fa-user-check me-2 text-primary"></i><?= esc($p['jabatan']); ?></span>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label text-secondary fw-semibold bg-transparent" style="font-size: 0.75rem;">Nama Lengkap + Gelar</label>
                                                <input class="form-control bg-white border-0 shadow-sm" name="nama" type="text" value="<?= esc($p['nama']); ?>" required placeholder="Dr. Fulan M.Kom...">
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="form-label text-secondary fw-semibold bg-transparent" style="font-size: 0.75rem;">Nomor Induk (<?= $p['jabatan'] == 'Ketua BEM Fasilkom' ? 'NPM' : 'NIP/NIDN' ?>)</label>
                                                <?php if ($p['jabatan'] == 'Ketua BEM Fasilkom'): ?>
                                                    <input class="form-control bg-white border-0 shadow-sm" name="npm" type="text" placeholder="Wajib Diisi" value="<?= esc($p['npm']); ?>">
                                                <?php elseif ($p['jabatan'] == 'Dekan Fasilkom'): ?>
                                                    <input class="form-control bg-white border-0 shadow-sm" name="nip" type="text" placeholder="Wajib Diisi" value="<?= esc($p['nip']); ?>">
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="mt-auto">
                                                <button type="submit" style="width:100%; padding: 10px; background-color: #1e293b; color: #ffffff; border: none; border-radius: 0.5rem; font-weight: 700; letter-spacing: 0.5px; cursor: pointer;">
                                                    <i class="fa-solid fa-save me-1"></i> SIMPAN OTORITAS
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Upload TTD Ketua BEM (baris terpisah agar tidak tertindih) -->
                        <?php $ketuaBem = null; foreach ($pejabat as $p) { if ($p['jabatan'] === 'Ketua BEM Fasilkom') { $ketuaBem = $p; break; } } ?>
                        <?php if ($ketuaBem): ?>
                        <div class="mt-4 pt-4 border-top">
                            <p class="fw-bold text-dark text-uppercase mb-3" style="font-size: 0.85rem; letter-spacing: 0.8px;">
                                <i class="fa-solid fa-pen-nib text-primary me-2"></i>Tanda Tangan Ketua BEM (Surat Elektronik)
                            </p>
                            <div class="row align-items-center g-3">
                                <?php if (!empty($ketuaBem['ttd'])): ?>
                                <div class="col-auto">
                                    <div class="p-2 bg-light rounded border text-center" style="min-width: 120px;">
                                        <img src="<?= base_url('uploads/ttd/' . $ketuaBem['ttd']) ?>" alt="TTD Ketua BEM" style="max-height: 60px; max-width: 160px;">
                                        <div class="text-muted mt-1" style="font-size: 0.7rem;">Tersimpan</div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="col">
                                    <form action="<?= base_url('sekum/pengaturan/upload-ttd') ?>" method="POST" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="jabatan" value="Ketua BEM Fasilkom">
                                        <div class="input-group">
                                            <input type="file" class="form-control bg-light border-0 shadow-sm" name="ttd_file" accept="image/*" required>
                                            <button type="submit" style="background-color: #1e40af; color: #ffffff; border: none; padding: 8px 20px; font-weight: 700; border-radius: 0 0.4rem 0.4rem 0; cursor: pointer; white-space: nowrap;">
                                                <i class="fa-solid fa-upload me-1"></i> Upload TTD
                                            </button>
                                        </div>
                                        <div class="form-text">Format: PNG/JPG transparan, maks 1MB. Akan otomatis muncul di surat elektronik yang disetujui.</div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>


                <!-- ===================================================== -->
                <!-- PERSIAPAN DEMISIONER: Buat Akun Sekum Penerus           -->
                <!-- ===================================================== -->
                <div class="card shadow-sm border border-warning" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="d-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0"
                                 style="width:48px;height:48px;background:linear-gradient(135deg,#f59e0b,#d97706);">
                                <i class="fa-solid fa-user-plus text-white"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0" style="color:#92400e;">Langkah 1: Siapkan Akun Sekum Penerus</h5>
                                <div class="text-muted small">Lakukan ini SEBELUM menekan tombol Demisioner di bawah.</div>
                            </div>
                        </div>
                        <p class="text-muted mb-4" style="line-height:1.6;">
                            Daftarkan akun untuk Sekretaris Umum periode berikutnya. Akun ini akan langsung diikat ke periode baru yang Anda tentukan. Sekum baru cukup login dan mengisi biodatanya sendiri.
                        </p>



                        <form action="<?= base_url('sekum/penerus/sekum'); ?>" method="POST">
                            <?= csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold" style="font-size:.85rem;">Tahun Periode Baru</label>
                                    <input type="text" class="form-control bg-light border-0" name="tahun_periode_baru"
                                           placeholder="Contoh: 2025/2026" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold" style="font-size:.85rem;">Email Sekum Baru</label>
                                    <input type="email" class="form-control bg-light border-0" name="email_sekum_baru"
                                           placeholder="email@unsika.ac.id" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold" style="font-size:.85rem;">Password Sementara</label>
                                    <input type="text" class="form-control bg-light border-0" name="password_sekum_baru"
                                           placeholder="Min. 6 karakter" required>
                                    <div class="form-text">Sekum baru akan ganti password saat pertama login.</div>
                                </div>
                            </div>
                                <button type="submit" class="btn btn-warning bg-warning text-dark fw-bold px-4"
                                        style="border-radius:.6rem;">
                                    <i class="fa-solid fa-user-plus me-2"></i> Daftarkan Akun Sekum Penerus
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ===================================================== -->
                <!-- ZONA DEMISIONER: Eksekusi Penonaktifan Kepengurusan    -->
                <!-- ===================================================== -->
                <div class="card shadow-sm border border-danger bg-white mt-3 mb-5" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="d-flex align-items-center mb-3 text-danger">
                                    <i class="fa-solid fa-hourglass-end fs-1 me-3"></i>
                                    <div>
                                        <h5 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;">Langkah 2: Eksekusi Demisioner</h5>
                                        <div class="fw-semibold opacity-75">Penonaktifan Kepengurusan Periode Ini</div>
                                    </div>
                                </div>
                                <p class="text-danger opacity-75 mb-2" style="line-height: 1.6;">
                                    Tombol ini akan <strong>menonaktifkan seluruh akun Sekpel dan Sekum</strong> pada periode yang sedang berjalan. 
                                    <strong class="text-danger">Seluruh data surat dan program kerja tetap aman</strong> dan tidak akan dihapus.
                                </p>
                                <div class="alert alert-warning border-0 py-2 px-3 small">
                                    <i class="fa-solid fa-circle-info me-1"></i>
                                    <strong>Pastikan Anda sudah mendaftarkan Akun Sekum Penerus di atas terlebih dahulu!</strong>
                                </div>
                            </div>
                            <div class="col-md-5 border-start border-danger border-opacity-25 ps-md-4">
                                <form action="<?= base_url('sekum/demisioner'); ?>" method="POST" id="formDemisioner">
                                    <?= csrf_field(); ?>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-danger" style="font-size: 0.75rem;">Ketik <strong>"DEMISIONER"</strong> untuk mengkonfirmasi</label>
                                        <input type="text" class="form-control border-danger text-danger bg-white fw-bold shadow-sm text-center"
                                               name="konfirmasi_reset" id="inputKonfirmasiDemisioner"
                                               placeholder="DEMISIONER" autocomplete="off" required
                                               style="letter-spacing: 2px;">
                                    </div>
                                    <div class="form-check mb-4">
                                        <input class="form-check-input border-danger" type="checkbox" id="checkDemisioner" required>
                                        <label class="form-check-label text-danger opacity-75" for="checkDemisioner" style="font-size: 0.75rem; line-height: 1.4;">
                                            Saya memahami bahwa akun Sekpel dan Sekum periode ini akan dinonaktifkan, namun seluruh data surat tetap tersimpan.
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-danger bg-danger text-white fw-bold disabled w-100" id="btnDemisioner"
                                            onclick="return confirm('Konfirmasi terakhir: Jalankan Demisioner dan nonaktifkan kepengurusan periode ini?')"
                                            style="border-radius: 0.5rem; letter-spacing: 0.5px;">
                                        <i class="fa-solid fa-hourglass-end me-2"></i> JALANKAN DEMISIONER
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </main>
</div>

<!-- Script Pembuka Gembok Tombol Demisioner -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputConfirm = document.getElementById('inputKonfirmasiDemisioner');
        const btnDemisioner = document.getElementById('btnDemisioner');
        const checkbox = document.getElementById('checkDemisioner');

        function checkLock() {
            if (inputConfirm && inputConfirm.value === "DEMISIONER" && checkbox && checkbox.checked) {
                btnDemisioner.classList.remove('disabled');
            } else if (btnDemisioner) {
                btnDemisioner.classList.add('disabled');
            }
        }

        if (inputConfirm) inputConfirm.addEventListener('input', checkLock);
        if (checkbox) checkbox.addEventListener('change', checkLock);
    });
</script>

<?= $this->endSection() ?>
