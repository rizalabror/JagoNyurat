<?= $this->extend('templates/index'); ?>

<?= $this->section('meta_description'); ?>
Sesuaikan kembali profil kepanitiaan dan detail kontak Anda. Ubah kata sandi, perbarui tanda tangan Ketua Pelaksana langsung via dasbor pembaruan.
<?= $this->endSection(); ?>

<?= $this->section('page-content'); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 pb-5">

            <!-- ═══════════════════════════════════════════
                 HERO HEADER — Modern gradient page header
                 ═══════════════════════════════════════════ -->
            <div style="
                background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 40%, #2563eb 100%);
                border-radius: 1rem;
                padding: 2rem 2.5rem;
                margin-top: 1.5rem;
                margin-bottom: 2rem;
                position: relative;
                overflow: hidden;
            ">
                <!-- Decorative circle bg -->
                <div style="
                    position: absolute; top: -40px; right: -40px;
                    width: 200px; height: 200px;
                    background: rgba(255,255,255,0.06);
                    border-radius: 50%;
                "></div>
                <div style="
                    position: absolute; bottom: -60px; right: 80px;
                    width: 150px; height: 150px;
                    background: rgba(255,255,255,0.04);
                    border-radius: 50%;
                "></div>

                <div style="position: relative; z-index: 1;">
                    <!-- Breadcrumb -->
                    <nav style="margin-bottom: 1rem;">
                        <ol style="display: flex; align-items: center; gap: 0.5rem; list-style: none; padding: 0; margin: 0;">
                            <li>
                                <a href="<?= base_url('sekpel') ?>"
                                   style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.8rem; font-weight: 500; transition: color 0.15s;"
                                   onmouseover="this.style.color='#fff'"
                                   onmouseout="this.style.color='rgba(255,255,255,0.7)'">
                                    <i class="fa-solid fa-house me-1" style="font-size: 0.7rem;"></i>Dashboard
                                </a>
                            </li>
                            <li style="color: rgba(255,255,255,0.4); font-size: 0.75rem;">/</li>
                            <li style="color: #fff; font-size: 0.8rem; font-weight: 600;">Ubah Profil</li>
                        </ol>
                    </nav>

                    <!-- Title row -->
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="
                            width: 52px; height: 52px;
                            background: rgba(255,255,255,0.15);
                            border-radius: 0.875rem;
                            display: flex; align-items: center; justify-content: center;
                            flex-shrink: 0;
                            backdrop-filter: blur(8px);
                        ">
                            <i class="fa-solid fa-user-pen" style="color: #fff; font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <h1 style="color: #fff; font-family: 'Inter', sans-serif; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.25rem;">
                                Edit Biodata Kepanitiaan
                            </h1>
                            <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; margin: 0;">
                                Perbarui data diri, program kerja, dan spesimen tanda tangan Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════
                 MAIN CONTENT
                 ═══════════════════════════════════════════ -->
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger mb-4" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success mb-4" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i><?= session()->getFlashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger mb-4" role="alert">
                            <ul class="mb-0 ps-3">
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- FORM CARD -->
                    <div class="card shadow-sm mb-5">
                        <!-- Card header dengan info hint -->
                        <div style="
                            background: linear-gradient(to right, #f8fafc, #fff);
                            padding: 1.25rem 1.5rem;
                            border-bottom: 1px solid #e2e8f0;
                            display: flex; align-items: center; gap: 0.75rem;
                        ">
                            <div style="
                                width: 36px; height: 36px;
                                background: #eff6ff;
                                border-radius: 0.625rem;
                                display: flex; align-items: center; justify-content: center;
                                flex-shrink: 0;
                            ">
                                <i class="fa-solid fa-clipboard-user" style="color: #2563eb; font-size: 0.9rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">Form Pembaruan Data</div>
                                <div style="font-size: 0.775rem; color: #64748b;">
                                    <i class="fa-solid fa-circle-info me-1" style="color: #3b82f6;"></i>
                                    Kosongkan Kata Sandi Baru dan form TTD jika tidak ingin mengubahnya.
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <form action="<?= base_url('sekpel/profil/update') ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>

                                <!-- ─── Seksi A: Ganti Sandi ─── -->
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin: 1.5rem 0 1rem;" >
                                    <div style="
                                        background: linear-gradient(135deg, #6366f1, #818cf8);
                                        color: #fff; font-size: 0.7rem; font-weight: 700;
                                        letter-spacing: 1px; border-radius: 6px;
                                        padding: 0.25rem 0.6rem; text-transform: uppercase;
                                        flex-shrink: 0;
                                    ">A</div>
                                    <h6 style="margin: 0; color: #334155; font-weight: 700; font-size: 0.9rem;">Ganti Sandi <span style="color:#94a3b8; font-weight:400;">(Opsional)</span></h6>
                                    <div style="flex:1; height:1px; background: #e2e8f0;"></div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label" for="password">Kata Sandi Baru</label>
                                        <input class="form-control" id="password" type="password" name="password" placeholder="Kosongkan jika tak diubah" />
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label" for="pass_confirm">Konfirmasi Sandi Baru</label>
                                        <input class="form-control" id="pass_confirm" type="password" name="pass_confirm" placeholder="Ketik ulang password baru" />
                                    </div>
                                </div>

                                <!-- ─── Seksi B: Identitas Sekpel ─── -->
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin: 1.5rem 0 1rem;">
                                    <div style="
                                        background: linear-gradient(135deg, #0ea5e9, #38bdf8);
                                        color: #fff; font-size: 0.7rem; font-weight: 700;
                                        letter-spacing: 1px; border-radius: 6px;
                                        padding: 0.25rem 0.6rem; text-transform: uppercase;
                                        flex-shrink: 0;
                                    ">B</div>
                                    <h6 style="margin: 0; color: #334155; font-weight: 700; font-size: 0.9rem;">Identitas Sekretaris Pelaksana</h6>
                                    <div style="flex:1; height:1px; background: #e2e8f0;"></div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label" for="fullname">Nama Lengkap (Sekpel)</label>
                                        <input class="form-control" id="fullname" type="text" name="fullname" value="<?= old('fullname', user()->fullname) ?>" required />
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label" for="npm">NPM (Sekpel)</label>
                                        <input class="form-control" id="npm" type="text" name="npm" value="<?= old('npm', user()->npm) ?>" required />
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label" for="ttd_sekpel">Ganti Tanda Tangan Sekpel <span style="color:#94a3b8;">(Opsional: .png / .jpg)</span></label>
                                        <input class="form-control" type="file" id="ttd_sekpel" name="ttd_sekpel" accept=".png,.jpg,.jpeg" />
                                        <?php if(isset($proker['ttd_sekpel'])): ?>
                                            <div class="form-text"><i class="fa-regular fa-image me-1"></i>File TTD saat ini: <a href="<?= base_url('uploads/ttd/'.$proker['ttd_sekpel']) ?>" target="_blank" style="color: #2563eb;">Lihat Gambar</a></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- ─── Seksi C: Profil Program Kerja ─── -->
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin: 1.5rem 0 1rem;">
                                    <div style="
                                        background: linear-gradient(135deg, #10b981, #34d399);
                                        color: #fff; font-size: 0.7rem; font-weight: 700;
                                        letter-spacing: 1px; border-radius: 6px;
                                        padding: 0.25rem 0.6rem; text-transform: uppercase;
                                        flex-shrink: 0;
                                    ">C</div>
                                    <h6 style="margin: 0; color: #334155; font-weight: 700; font-size: 0.9rem;">Profil Program Kerja</h6>
                                    <div style="flex:1; height:1px; background: #e2e8f0;"></div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label" for="kode_proker">Kode Program Kerja</label>
                                        <input class="form-control" id="kode_proker" type="text" name="kode_proker" value="<?= old('kode_proker', $proker['kode_proker']) ?>" required />
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label" for="tahun_aktif">Tahun Pelaksanaan</label>
                                        <input class="form-control" id="tahun_aktif" type="number" name="tahun_aktif" min="2020" max="2100" value="<?= old('tahun_aktif', $proker['tahun_aktif']) ?>" required />
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label" for="nama_proker">Nama Kepanitiaan Lengkap</label>
                                        <input class="form-control" id="nama_proker" type="text" name="nama_proker" value="<?= old('nama_proker', $proker['nama']) ?>" required />
                                    </div>
                                </div>

                                <!-- ─── Seksi D: Identitas Ketua Pelaksana ─── -->
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin: 1.5rem 0 1rem;">
                                    <div style="
                                        background: linear-gradient(135deg, #f59e0b, #fbbf24);
                                        color: #fff; font-size: 0.7rem; font-weight: 700;
                                        letter-spacing: 1px; border-radius: 6px;
                                        padding: 0.25rem 0.6rem; text-transform: uppercase;
                                        flex-shrink: 0;
                                    ">D</div>
                                    <h6 style="margin: 0; color: #334155; font-weight: 700; font-size: 0.9rem;">Identitas Ketua Pelaksana</h6>
                                    <div style="flex:1; height:1px; background: #e2e8f0;"></div>
                                </div>
                                <div class="row gx-3 mb-4">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label" for="nama_ketua_pelaksana">Nama Ketua Pelaksana (Ketuplak)</label>
                                        <input class="form-control" id="nama_ketua_pelaksana" type="text" name="nama_ketua_pelaksana" value="<?= old('nama_ketua_pelaksana', $proker['nama_ketua_pelaksana']) ?>" required />
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label" for="npm_ketua_pelaksana">NPM Ketua Pelaksana</label>
                                        <input class="form-control" id="npm_ketua_pelaksana" type="text" name="npm_ketua_pelaksana" value="<?= old('npm_ketua_pelaksana', $proker['npm_ketua_pelaksana']) ?>" required />
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label" for="ttd_ketua">Ganti Tanda Tangan Ketuplak <span style="color:#94a3b8;">(Opsional: .png / .jpg)</span></label>
                                        <input class="form-control" type="file" id="ttd_ketua" name="ttd_ketua" accept=".png,.jpg,.jpeg" />
                                        <?php if(isset($proker['ttd_ketua_pelaksana'])): ?>
                                            <div class="form-text"><i class="fa-regular fa-image me-1"></i>File TTD saat ini: <a href="<?= base_url('uploads/ttd/'.$proker['ttd_ketua_pelaksana']) ?>" target="_blank" style="color: #2563eb;">Lihat Gambar</a></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- ─── Submit Button ─── -->
                                <div style="padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                                    <button class="btn btn-primary" type="submit" style="padding: 0.625rem 2rem; letter-spacing: 0.3px;">
                                        <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan Biodata
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>

<?= $this->endSection(); ?>
