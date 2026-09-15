<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tampilan cetak halaman surat PDF untuk program kepanitiaan BEM FASILKOM UNSIKA. Pratinjau langsung format akhir surat resmi sebelum dokumen diekspor utuh.">
    <title>Cetak Surat - <?= esc($surat['nama_proker']) ?></title>
    <style>
    /* ============================================================
     * SEKSI 1 — RESET & SETTING KERTAS
     * Kertas F4 (Folio): 21.5cm × 33cm
     * ============================================================ */
    * {
        box-sizing: border-box;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    @page { size: 215mm 330mm; margin: 0; }
    body {
        font-family: "Times New Roman", Times, serif;
        font-size: 12pt;
        margin: 0; padding: 0;
        background-color: #ffffff;
        display: flex; flex-direction: column; align-items: center;
    }

    /* --- Hanya tampilkan di mesin print, sembunyikan di layar browser --- */
    @media screen {
        body {
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        body::before {
            content: "Membuka dialog pencetakan...";
            font-family: sans-serif;
            font-weight: bold;
            font-size: 1.2rem;
            color: #6c757d;
        }
        #kertas, .kertas-lampiran, .no-print {
            display: none !important;
        }
    }

    /* --- Kertas Surat Utama --- */
    #kertas {
        width: 21.5cm; min-height: 33cm;
        background-color: #ffffff;
        padding: 0 2.54cm 2.54cm 2.54cm;
        box-shadow: none;
        margin-bottom: 0; position: relative;
    }

    /* --- Kertas Lampiran (halaman 2+) --- */
    .kertas-lampiran {
        width: 21.5cm; min-height: 33cm;
        background-color: #ffffff;
        box-shadow: none;
        margin-bottom: 0; overflow: hidden;
        position: relative; page-break-before: always;
    }
    .kertas-lampiran table, .kertas-lampiran .table {
        max-width: 100% !important;
        word-wrap: break-word; overflow-wrap: break-word;
        margin-left: auto; margin-right: auto;
    }
    .kertas-lampiran td, .kertas-lampiran th {
        overflow-wrap: break-word; word-break: break-word;
    }
    .kertas-lampiran table { border-collapse: collapse !important; margin-top: 10px; }
    .kertas-lampiran table th,
    .kertas-lampiran table td {
        /* Border tidak dipaksa — mengikuti inline style dari TinyMCE */
        padding: 3px 5px !important;
        vertical-align: middle;
        font-size: 11pt; line-height: inherit;
    }
    .kertas-lampiran table th { font-weight: bold; }
    .kertas-lampiran table td p,
    .kertas-lampiran table th p { margin: 0 !important; padding: 0 !important; line-height: inherit; }

    @media print {
        body { background: none; margin: 0; padding: 0; }
        #kertas {
            width: 21.5cm; min-height: 33cm;
            margin: 0 !important; box-shadow: none !important;
            zoom: 1 !important; -moz-transform: none !important; border: none !important;
        }
        .kertas-lampiran {
            width: 21.5cm; margin: 0 !important;
            box-shadow: none !important; page-break-before: always;
        }
        .no-print { display: none !important; }
    }


    /* ============================================================
     * SEKSI 2 — KOP SURAT
     * ============================================================ */
    .kop-surat {
        position: relative; margin-top: 1.3cm;
        text-align: center; padding: 0; z-index: 10;
    }
    .kop-surat img {
        width: 3cm; height: 3cm; float: left;
        margin-left: 7px; margin-top: 15px;
    }
    .kop-text {
        display: inline-block; text-align: center;
        width: calc(100% - 3.2cm); vertical-align: middle;
        position: relative; margin-left: -40px;
    }
    .kop-text h1 {
        font-size: 14pt; font-weight: normal; margin: 0;
        font-family: 'Times New Roman', Times, serif;
        text-transform: uppercase; line-height: 1.2;
    }
    .kop-text h2 {
        font-size: 14pt; margin: 0; font-weight: bold;
        font-family: 'Times New Roman', Times, serif;
        text-transform: uppercase; line-height: 1.2;
    }
    .kop-surat .contact {
        font-size: 11.2pt; color: #3c78d8;
        margin: 0 0 5pt 0; font-style: italic; font-weight: normal;
        text-align: center; font-family: 'Times New Roman', Times, serif;
        line-height: 1.2; padding-left: 0;
    }
    .kop-surat .contact:last-child { padding-left: 20px; }
    .garis-kop {
        border-top: 2.25pt solid #000; border-bottom: 0.75pt solid #000;
        height: 3.5pt; margin-top: 5px; width: 100%;
        margin-left: auto; margin-right: auto; margin-bottom: 15px; clear: both;
    }
    #surat-elektronik {
        position: absolute; top: -0.9cm; right: -1.7cm;
        font-family: "Times New Roman", Times, serif; font-size: 11pt;
        width: 4.572cm; height: 0.762cm; border: 1pt solid black;
        background-color: transparent; text-align: center; line-height: 0.762cm; z-index: 20;
    }
    /* Bagian @media print sebelumnya dihapus agar pengaturan manual posisi label elektronik bisa berjalan */

    /* ============================================================
     * SEKSI 3 — ISI SURAT UTAMA
     * ============================================================ */
    .isi-surat {
        position: relative; margin: 0; width: 100%;
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt; line-height: 1.15;
    }
    .tanggal-box {
        position: absolute; top: -15px; right: 0;
        text-align: right; line-height: normal; z-index: 5;
    }
    .info-table {
        width: auto; border-collapse: collapse;
        margin-bottom: 18pt; line-height: 1.1;
    }
    .info-table td { vertical-align: top; padding: 0; font-size: 12pt; }
    .info-table .label { width: 90pt; }
    .info-table .colon { width: 12pt; text-align: center; padding: 0 2pt; }
    .tujuan-box { margin: 9pt 0; line-height: 1.1; }
    .tujuan-box p { margin: 0; }
    .salam { margin: 18pt 0; line-height: 1.1; font-weight: bold; font-style: italic; }
    .paragraf {
        margin: 0 !important; margin-bottom: 4pt !important; padding: 0 !important;
        text-align: justify; text-indent: 1.27cm; line-height: 1.15 !important;
    }
    .paragraf p { margin: 0; margin-bottom: 4pt; }
    .paragraf p:last-child { margin-bottom: 0 !important; }
    u { text-underline-offset: 1.3pt; text-decoration-thickness: 0.5pt; }

    /* ============================================================
     * SEKSI 4 — RINCIAN KEGIATAN
     * ============================================================ */
    .rincian-kegiatan { margin: 9pt 0 9pt 1.27cm; line-height: 1.1; }
    .rincian-kegiatan table { border-collapse: collapse; }
    .rincian-kegiatan td { vertical-align: top; padding: 2pt 0; }
    .rincian-kegiatan .r-label { width: 65pt; }
    .rincian-kegiatan .r-colon { width: 10pt; text-align: center; }

    /* ============================================================
     * SEKSI 5 — AREA TANDA TANGAN
     * ============================================================ */
    .signature-table {
        width: 100%; margin-top: 6pt;
        border-collapse: collapse; line-height: 1.0;
    }
    .signature-table td { vertical-align: top; padding: 0; }
    .signature-table p { margin: 0 !important; padding: 2pt 0; }
    .signature-wrapper { page-break-inside: avoid; width: 100%; }
    .signature-space {
        height: 40pt; position: relative;
        display: flex; justify-content: center; align-items: center;
    }
    .signature-space img { width: 3cm; height: 1.5cm; object-fit: contain; z-index: 1; }
    .cap-panpel {
        position: absolute; width: 3cm; height: 0.85cm;
        z-index: 999 !important;
        mix-blend-mode: normal !important;
        pointer-events: none; user-select: none;
        top: 0px; left: -50px;
    }
    .cap-bem {
        position: absolute; 
        width: 4cm !important; height: auto !important;
        z-index: 999 !important;
        mix-blend-mode: normal !important;
        object-fit: contain !important;
        pointer-events: none; user-select: none;
        top: -44px !important; left: -60px !important;
    }
    .ttd-bem {
    width: 4cm !important;    /* UBAH INI untuk lebar TTD */
    height: 2cm !important;   /* UBAH INI untuk tinggi TTD */
    object-fit: contain !important;
}

    /* ============================================================
     * SEKSI 6 — LAMPIRAN (konten TinyMCE)
     * ============================================================ */
    .isi-lampiran {
        margin: 0; width: 100%;
        font-family: 'Times New Roman', Times, serif; font-size: 12pt;
    }
    table.kertas-lampiran td p.lampiran-label {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt; font-weight: bold !important; font-style: italic;
        text-align: left;
        margin-top: -10px !important; margin-bottom: 24px !important;
        margin-left: 0px !important; padding-left: 0 !important; display: block;
    }
    .lampiran-content {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt; line-height: 1.15;
    }
    .lampiran-content p,
    .lampiran-content h1, .lampiran-content h2,
    .lampiran-content h3, .lampiran-content h4,
    .lampiran-content span {
        line-height: 1.15; margin-top: 0; margin-bottom: 4.2pt;
        margin-left: 0 !important; margin-right: 0 !important; padding: 0;
    }
    /* Kecualikan margin untuk elemen di dalam tabel lampiran */
    .lampiran-content table p,
    .lampiran-content table span {
        margin-bottom: 0 !important;
    }
    .lampiran-content table,
    .lampiran-content table p,
    .lampiran-content table span,
    .lampiran-content table td { line-height: 1.15 !important; }
    .lampiran-content p[style*="text-indent"] { text-indent: 0 !important; }
    .lampiran-content table {
        border-collapse: collapse;
        width: 100% !important; max-width: 100% !important;
        margin: 0 !important;
        table-layout: auto;
    }
    .lampiran-content table tr { page-break-inside: avoid; }
    .lampiran-content table td,
    .lampiran-content table th {
        vertical-align: middle;
        padding: 3pt 5pt;
        text-align: inherit; /* ikuti alignment dari td induk */
    }
    .lampiran-content table td p,
    .lampiran-content table th p,
    .lampiran-content table td span,
    .lampiran-content table th span {
        text-align: inherit; /* p/span di dalam sel ikut alignment td */
    }
    /* Tabel tanpa border (kelas khusus atau table-no-border) */
    .lampiran-content table.table-no-border,
    .lampiran-content table.table-no-border[border],
    .lampiran-content table.table-no-border[border="1"] {
        border: none !important;
    }
    .lampiran-content table.table-no-border td,
    .lampiran-content table.table-no-border th,
    .lampiran-content table.table-no-border[border] td,
    .lampiran-content table.table-no-border[border] th,
    .lampiran-content table.table-no-border[border="1"] td,
    .lampiran-content table.table-no-border[border="1"] th {
        border: none !important; padding: 2pt 6pt;
    }
    /* Tabel dengan border dari TinyMCE (attribute border="1") — tampilkan saat cetak */
    .lampiran-content table[border="1"] td,
    .lampiran-content table[border="1"] th {
        border: 0.75pt solid #000 !important;
    }
    /* Tabel dengan kelas .table-border eksplisit */
    .lampiran-content table.table-border td,
    .lampiran-content table.table-border th {
        border: 1pt solid #000 !important;
    }
    @media print {
        .lampiran-content table[border="1"] td,
        .lampiran-content table[border="1"] th {
            border: 0.75pt solid #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* ============================================================
     * SEKSI 7 — TOMBOL CETAK (tidak tercetak)
     * ============================================================ */
    .btn-floating {
        position: fixed; bottom: 30px; right: 30px;
        background: linear-gradient(135deg, #1a73e8, #0d47a1);
        color: white; border: none; padding: 14px 22px;
        border-radius: 50px; font-size: 15px; cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        text-decoration: none; font-family: sans-serif; letter-spacing: 0.3px;
    }
    .btn-floating:hover { background: linear-gradient(135deg, #1557b0, #0a3880); }

    <?php if (isset($is_preview) && $is_preview): ?>
    @media screen {
        body { background-color: #ffffff !important; overflow-x: hidden; padding: 0; }
        #kertas, .kertas-lampiran {
            box-shadow: none !important;
            margin: 0 auto 0 auto !important;
        }
        .btn-floating { display: none !important; }
    }
    <?php endif; ?>
    </style>
</head>
<body>

<?php if (!isset($is_preview) || !$is_preview): ?>
<!-- Tombol cetak manual disembunyikan karena auto-print aktif -->
<button class="no-print btn-floating" id="dl-btn" onclick="startPrint()" style="display:none;">📥 Download PDF</button>
<?php endif; ?>


<!-- ============================================================
     HALAMAN 1: SURAT UTAMA
============================================================ -->
<div id="kertas">

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <?php if (($surat['jenis_surat'] ?? '') === 'elektronik'): ?>
        <div id="surat-elektronik">SURAT ELEKTRONIK</div>
        <?php endif; ?>
        <img src="<?= base_url('assets/img/logoOrmawa/LogoBEM.png') ?>" alt="Logo BEM FASILKOM UNSIKA">
        <div class="kop-text">
            <h1><?= str_replace('SAINS, DAN', 'SAINS,<br>DAN', $kop_extra['baris1'] ?? 'KEMENTERIAN PENDIDIKAN TINGGI, SAINS,<br>DAN TEKNOLOGI') ?></h1>
            <h1><?= $kop_extra['baris2'] ?? 'UNIVERSITAS SINGAPERBANGSA KARAWANG' ?></h1>
            <h2><?= $kop_extra['baris3'] ?? 'BADAN EKSEKUTIF MAHASISWA' ?></h2>
            <h2><?= $kop_extra['baris4'] ?? 'FAKULTAS ILMU KOMPUTER' ?></h2>
            <h2>PANITIA PELAKSANA</h2>
            <h2><strong><?= strtoupper(esc($surat['nama_proker'])) ?></strong></h2>
        </div>
        <div style="clear: both;"></div>
        <div style="width: 100%; text-align: center;">
            <p class="contact" style="margin-top:5px; margin-bottom: 2px;">Sekretariat: <?= esc($bem['sekretariat'] ?? '-') ?> Hp: <?= esc($bem['nohp'] ?? '-') ?></p>
            <p class="contact">Email: <?= esc($bem['email'] ?? '-') ?>&nbsp; Website: <?= esc($bem['website'] ?? '-') ?></p>
        </div>
        <div class="garis-kop"></div>
    </div>

    <!-- ISI SURAT -->
    <div class="isi-surat">

        <!-- Tanggal (kanan atas) -->
        <div class="tanggal-box">
            <?= date('d', strtotime($surat['updated_at'])) . ' ' . [
                'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April',
                'May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus',
                'September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
            ][date('F', strtotime($surat['updated_at']))] . ' ' . date('Y', strtotime($surat['updated_at'])) ?>
        </div>

        <!-- Nomor / Lampiran / Perihal -->
        <table class="info-table">
            <tr>
                <td class="label">Nomor</td>
                <td class="colon">:</td>
                <td><?= esc($surat['nomor_surat'] ?: '.../TPA-.../BEM-FASILKOM/.../' . date('Y')) ?></td>
            </tr>
            <tr>
                <td class="label">Lampiran</td>
                <td class="colon">:</td>
                <td><?= esc($surat['lampiran'] ?: '-') ?></td>
            </tr>
            <tr>
                <td class="label">Perihal</td>
                <td class="colon">:</td>
                <td><strong><em><u><?= esc($surat['perihal']) ?></u></em></strong></td>
            </tr>
        </table>

        <!-- Tujuan -->
        <div class="tujuan-box">
            <p>Yth.</p>
            <p><strong><?= nl2br(esc($surat['tujuan_surat'])) ?></strong></p>
            <p>di</p>
            <p style="padding-left: 1.27cm;"><?= esc($surat['tempat_tujuan']) ?></p>
        </div>

        <div class="salam">Assalamu'alaikum Wr. Wb.</div>

        <!-- Paragraf Pembuka -->
        <?php
            // Render paragraf pembuka — bisa berisi HTML dari TinyMCE (bold/italic)
            // ✅ FIX #8 (XSS): Izinkan HANYA tag HTML yang aman untuk formatting dokumen
            $allowedTags = '<p><br><strong><em><u><s><span><ul><ol><li><table><thead><tbody><tr><td><th><colgroup><col><a><h1><h2><h3><h4><h5><h6><blockquote><div><hr><img>';
            $isiParagraf = trim($surat['isi_paragraf'] ?? '');
            if (str_contains($isiParagraf, '<')) {
                // Bersihkan tag p kosong di akhir yang sering ditambahkan TinyMCE secara otomatis
                $isiParagraf = preg_replace('/(<p>&nbsp;<\/p>)+$/', '', $isiParagraf);
                // Hapus tag script, iframe, object, embed, dan atribut event handler (onXxx=)
                $isiParagraf = preg_replace('/<(script|iframe|object|embed|form|input|button)(\s[^>]*)?>[\s\S]*?<\/\1>/i', '', $isiParagraf);
                $isiParagraf = preg_replace('/\s(on\w+)\s*=\s*[\'"[^\'">]*[\'"]/i', '', $isiParagraf);
                echo '<div class="paragraf">' . strip_tags($isiParagraf, $allowedTags) . '</div>';
            } else {
                // Konten teks biasa (lama) — split per baris
                foreach (explode("\n", $isiParagraf) as $baris) {
                    if (trim($baris) !== '') echo '<div class="paragraf">' . esc(trim($baris)) . '</div>';
                }
            }
        ?>

        <!-- Paragraf Isi (opsional, sebelum rincian) -->
        <?php if (!empty(trim(strip_tags($surat['paragraf_isi'] ?? '')))): ?>
            <?php
                $allowedTags = '<p><br><strong><em><u><s><span><ul><ol><li><table><thead><tbody><tr><td><th><colgroup><col><a><h1><h2><h3><h4><h5><h6><blockquote><div><hr><img>';
                $paragrafIsi = trim($surat['paragraf_isi'] ?? '');
                if (str_contains($paragrafIsi, '<')) {
                    $paragrafIsi = preg_replace('/(<p>&nbsp;<\/p>)+$/', '', $paragrafIsi);
                    $paragrafIsi = preg_replace('/<(script|iframe|object|embed|form|input|button)(\s[^>]*)?>[\s\S]*?<\/\1>/i', '', $paragrafIsi);
                    $paragrafIsi = preg_replace('/\s(on\w+)\s*=\s*[\'"[^\'">]*[\'"]/i', '', $paragrafIsi);
                    echo '<div class="paragraf">' . strip_tags($paragrafIsi, $allowedTags) . '</div>';
                } else {
                    foreach (explode("\n", $paragrafIsi) as $baris) {
                        if (trim($baris) !== '') echo '<div class="paragraf">' . esc(trim($baris)) . '</div>';
                    }
                }
            ?>
        <?php endif; ?>

        <!-- Parsing isi_lampiran JSON (dibutuhkan di bagian ini dan halaman lampiran bawah) -->
        <?php
            $lampiranArray    = [];
            $sembunyikanTabel = false;
            if (!empty(trim(strip_tags($surat['isi_lampiran'] ?? '')))) {
                $dec = json_decode($surat['isi_lampiran'], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($dec)) {
                    if (isset($dec['pages'])) {
                        $lampiranArray    = $dec['pages'];
                        $sembunyikanTabel = $dec['sembunyikan_tabel'] ?? false;
                    } else {
                        $lampiranArray = $dec;
                    }
                } else {
                    $lampiranArray = [$surat['isi_lampiran']];
                }
            }
        ?>

        <!-- Rincian Kegiatan (bisa disembunyikan via toggle) -->
        <?php if (!$sembunyikanTabel): ?>
        <div class="rincian-kegiatan">
            <table style="width: auto;">
                <?php
                    $hariMap  = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
                    $bulanMap = ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'];

                    $tsMulai    = strtotime($surat['tanggal_kegiatan_mulai']);
                    $tsAkhir    = strtotime($surat['tanggal_kegiatan_selesai']);
                    $isMultiDay = ($surat['tanggal_kegiatan_selesai'] && date('Y-m-d', $tsMulai) !== date('Y-m-d', $tsAkhir));

                    if (!$isMultiDay):
                        $hariMulai    = $hariMap[date('l', $tsMulai)];
                        $tglMulaiIndo = strtr(date('d F Y', $tsMulai), $bulanMap);
                ?>
                <tr>
                    <td class="r-label" style="vertical-align: top;">Hari, tanggal</td>
                    <td class="r-colon" style="vertical-align: top;">:</td>
                    <td style="vertical-align: top;"><?= $hariMulai . ', ' . $tglMulaiIndo ?></td>
                </tr>
                <?php else:
                    $hariMulai = $hariMap[date('l', $tsMulai)];
                    $hariAkhir = $hariMap[date('l', $tsAkhir)];
                    $dM = date('d',$tsMulai); $mM = date('F',$tsMulai); $yM = date('Y',$tsMulai);
                    $dA = date('d',$tsAkhir); $mA = date('F',$tsAkhir); $yA = date('Y',$tsAkhir);

                    if ($yM === $yA && $mM === $mA) {
                        $tglGabung = $dM . ' &ndash; ' . $dA . ' ' . $bulanMap[$mA] . ' ' . $yA;
                    } elseif ($yM === $yA) {
                        $tglGabung = $dM . ' ' . $bulanMap[$mM] . ' &ndash; ' . $dA . ' ' . $bulanMap[$mA] . ' ' . $yA;
                    } else {
                        $tglGabung = $dM . ' ' . $bulanMap[$mM] . ' ' . $yM . ' &ndash; ' . $dA . ' ' . $bulanMap[$mA] . ' ' . $yA;
                    }
                ?>
                <tr>
                    <td class="r-label" style="vertical-align: top;">Hari</td>
                    <td class="r-colon" style="vertical-align: top;">:</td>
                    <td style="vertical-align: top;"><?= $hariMulai . ' &ndash; ' . $hariAkhir ?></td>
                </tr>
                <tr>
                    <td class="r-label" style="vertical-align: top;">Tanggal</td>
                    <td class="r-colon" style="vertical-align: top;">:</td>
                    <td style="vertical-align: top;"><?= $tglGabung ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="r-label" style="vertical-align: top;">Waktu</td>
                    <td class="r-colon" style="vertical-align: top;">:</td>
                    <td style="vertical-align: top;">
                        <?php
                            $wMulai  = $surat['waktu_kegiatan_mulai'];
                            $wMulaiF = preg_match('/^\d{1,2}:\d{2}/', $wMulai)
                                       ? date('H.i', strtotime($wMulai)) . ' WIB' : esc($wMulai);
                            $wSelesai  = $surat['waktu_kegiatan_selesai'] ?? null;
                            $wSelesaiF = '';
                            if (!empty($wSelesai)) {
                                $wSelesaiF = preg_match('/^\d{1,2}:\d{2}/', $wSelesai)
                                             ? date('H.i', strtotime($wSelesai)) . ' WIB' : esc($wSelesai);
                            }
                            echo $wMulaiF;
                            if (!empty($wSelesaiF)) echo ' &ndash; ' . $wSelesaiF;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="r-label" style="vertical-align: top;">Tempat</td>
                    <td class="r-colon" style="vertical-align: top;">:</td>
                    <td style="vertical-align: top;"><?= esc($surat['tempat_kegiatan']) ?></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Paragraf Penutup -->
        <?php
            $allowedTags = '<p><br><strong><em><u><s><span><ul><ol><li><table><thead><tbody><tr><td><th><colgroup><col><a><h1><h2><h3><h4><h5><h6><blockquote><div><hr><img>';
            $penutupParagraf = trim($surat['penutup_paragraf'] ?? '');
            if (str_contains($penutupParagraf, '<')) {
                $penutupParagraf = preg_replace('/(<p>&nbsp;<\/p>)+$/', '', $penutupParagraf);
                $penutupParagraf = preg_replace('/<(script|iframe|object|embed|form|input|button)(\s[^>]*)?>[\s\S]*?<\/\1>/i', '', $penutupParagraf);
                $penutupParagraf = preg_replace('/\s(on\w+)\s*=\s*[\'"[^\'">]*[\'"]/i', '', $penutupParagraf);
                echo '<div class="paragraf">' . strip_tags($penutupParagraf, $allowedTags) . '</div>';
            } else {
                foreach (explode("\n", $penutupParagraf) as $baris) {
                    if (trim($baris) !== '') echo '<div class="paragraf">' . esc(trim($baris)) . '</div>';
                }
            }
        ?>

        <div class="salam">Wassalamu'alaikum Wr. Wb.</div>

        <!-- TANDA TANGAN -->
        <?php
            $isElektronik = ($surat['jenis_surat'] ?? '') === 'elektronik';
            // $isApproved: TTD & stempel hanya muncul jika surat sudah disetujui Sekum.
            // Mode preview (is_preview=true) → dianggap belum approved.
            $isApproved   = !($is_preview ?? false) && ($surat['status'] ?? '') === 'Approved';
            $namaKetua    = !empty($proker['nama_ketua_pelaksana']) ? $proker['nama_ketua_pelaksana'] : '( ......................................... )';
            $npmKetua     = !empty($proker['npm_ketua_pelaksana'])  ? 'NPM: ' . $proker['npm_ketua_pelaksana'] : '';
            $ttdKetua     = !empty($proker['ttd_ketua_pelaksana'])  ? $proker['ttd_ketua_pelaksana']  : null;
            $namaSekpel   = !empty($surat['nama_pembuat'])          ? $surat['nama_pembuat'] : '( ......................................... )';
            $npmSekpelRaw = $surat['npm'] ?? $surat['npm_pembuat'] ?? null;
            $npmSekpel    = !empty($npmSekpelRaw) ? 'NPM: ' . $npmSekpelRaw : '';
            $ttdSekpel    = !empty($proker['ttd_sekpel']) ? $proker['ttd_sekpel'] : null;
            // TTD Ketua BEM dari tabel identity (hanya surat elektronik)
            $ttdKetuaBem  = ($isElektronik && !empty($ttd_bem['ttd'])) ? $ttd_bem['ttd'] : null;
        ?>
        <div class="signature-wrapper" style="margin-top: 0px;">
            <table class="signature-table" style="width: 100%;">

                <!-- Baris 1: Ketua Pelaksana (kiri) & Sekretaris Pelaksana (kanan) -->
                <tr>
                    <td style="width: 50%; text-align: left;">
                        <p><strong>Ketua Pelaksana</strong></p>
                        <div class="signature-space" style="justify-content: flex-start; position: relative;">
                            <?php if ($isElektronik && $isApproved && $ttdKetua): ?>
                                <img src="<?= base_url('uploads/ttd/' . $ttdKetua) ?>" alt="TTD Ketua" style="margin-left: 15px; position: relative;">
                            <?php endif; ?>
                            <?php if ($isElektronik && $isApproved): ?>
                                <img class="cap-panpel" src="<?= base_url('assets/img/stempel/cap-panpel.png') ?>" alt="Cap Panpel">
                            <?php endif; ?>
                        </div>
                        <p><strong><u><?= esc($namaKetua) ?></u></strong><br><?= esc($npmKetua) ?></p>
                    </td>
                    <td style="width: 50%; text-align: right; vertical-align: top;">
                        <div style="display: inline-block; text-align: left;">
                            <p><strong>Sekretaris Pelaksana</strong></p>
                            <div class="signature-space" style="justify-content: flex-start;">
                                <?php if ($isElektronik && $isApproved && $ttdSekpel): ?>
                                    <img src="<?= base_url('uploads/ttd/' . $ttdSekpel) ?>" alt="TTD Sekpel" style="margin-left: 0;">
                                <?php endif; ?>
                            </div>
                            <p><strong><u><?= esc($namaSekpel) ?></u></strong><br><?= esc($npmSekpel) ?></p>
                        </div>
                    </td>
                </tr>

                <!-- Baris 2: Mengetahui/Menyetujui (adaptif — muncul jika ada) -->
                <?php if (!empty($ttd_bem) || !empty($ttd_dekan)): ?>
                <tr>
                    <?php if (!empty($ttd_bem) && !empty($ttd_dekan)): ?>
                        <!-- Kasus: keduanya ada — Dekan kiri, Ketua BEM kanan -->
                        <td style="width: 50%; text-align: left; vertical-align: top;">
                            <br><br>
                            <p>Mengetahui,</p>
                            <p><strong><?= esc(str_ireplace('Fakultas Ilmu Komputer', 'Fasilkom', $ttd_dekan['jabatan'])) ?></strong></p>
                            <div class="signature-space"></div>
                            <p><strong><u><?= esc($ttd_dekan['nama']) ?></u></strong><br>NIP. <?= esc($ttd_dekan['nip']) ?></p>
                        </td>
                        <td style="width: 50%; text-align: right; vertical-align: top;">
                            <br><br>
                            <div style="display: inline-block; text-align: left;">
                                <p>Menyetujui,</p>
                                <p><strong><?= esc(str_ireplace('Fakultas Ilmu Komputer', 'Fasilkom', $ttd_bem['jabatan'])) ?></strong></p>
                                <div class="signature-space" style="justify-content: flex-start; position: relative;">
                                    <?php if ($isElektronik && $isApproved): ?>
                                        <img class="cap-bem" src="<?= base_url('assets/img/stempel/cap-ketum-bem.png') ?>" alt="Cap BEM">
                                    <?php endif; ?>
                                    <?php if ($ttdKetuaBem && $isApproved): ?>
                                        <img class="ttd-bem" src="<?= base_url('uploads/ttd/' . $ttdKetuaBem) ?>" alt="TTD Ketua BEM" style="position: relative;">
                                    <?php endif; ?>
                                </div>
                                <p><strong><u><?= esc($ttd_bem['nama']) ?></u></strong><br>NPM: <?= esc($ttd_bem['npm']) ?></p>
                            </div>
                        </td>

                    <?php else: ?>
                        <!-- Kasus: hanya satu — ditampilkan di tengah -->
                        <td colspan="2" style="text-align: center; vertical-align: top;">
                            <br><br>
                            <div style="display: inline-block; text-align: left;">
                                <?php if (!empty($ttd_dekan)): ?>
                                    <p>Mengetahui,</p>
                                    <p><strong><?= esc(str_ireplace('Fakultas Ilmu Komputer', 'Fasilkom', $ttd_dekan['jabatan'])) ?></strong></p>
                                    <div class="signature-space"></div>
                                    <p><strong><u><?= esc($ttd_dekan['nama']) ?></u></strong><br>NIP. <?= esc($ttd_dekan['nip']) ?></p>
                                <?php elseif (!empty($ttd_bem)): ?>
                                    <p>Menyetujui,</p>
                                    <p><strong><?= esc(str_ireplace('Fakultas Ilmu Komputer', 'Fasilkom', $ttd_bem['jabatan'])) ?></strong></p>
                                    <div class="signature-space" style="justify-content: flex-start; position: relative;">
                                        <?php if ($isElektronik && $isApproved): ?>
                                            <img class="cap-bem" src="<?= base_url('assets/img/stempel/cap-ketum-bem.png') ?>" alt="Cap BEM">
                                        <?php endif; ?>
                                        <?php if ($ttdKetuaBem && $isApproved): ?>
                                            <img class="ttd-bem" src="<?= base_url('uploads/ttd/' . $ttdKetuaBem) ?>" alt="TTD Ketua BEM" style="position: relative;">
                                        <?php endif; ?>
                                    </div>
                                    <p><strong><u><?= esc($ttd_bem['nama']) ?></u></strong><br>NPM: <?= esc($ttd_bem['npm']) ?></p>
                                <?php endif; ?>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endif; ?>

            </table>
        </div>

    </div><!-- /.isi-surat -->
</div><!-- /#kertas -->


<!-- ============================================================
     HALAMAN 2+: LAMPIRAN
     - Menggunakan <table> agar KOP otomatis berulang di tiap halaman
     - Hanya render jika konten lampiran tidak kosong
============================================================ -->
<?php foreach ($lampiranArray as $idx => $lampHtml): ?>
<?php
    $cleanCheck = trim(str_replace(['&nbsp;', '<p></p>', '<p><br></p>', "\xc2\xa0", "\n", "\r"], '', strip_tags($lampHtml, '<img><table><tr><td><th><tbody><thead>')));
    if (!empty($cleanCheck)):
?>
<table class="kertas-lampiran" style="width: 21.5cm; margin: 0 auto 24px auto; background-color: #ffffff; box-shadow: 0 4px 20px rgba(0,0,0,0.35); border-collapse: collapse; border: none;">
    <thead style="display: table-header-group;">
        <tr>
            <td style="border: none !important; padding: 0 2.54cm !important;">
                <!-- KOP SURAT LAMPIRAN (sama struktur dengan surat utama) -->
                <div class="kop-surat">
                    <?php if (($surat['jenis_surat'] ?? '') === 'elektronik'): ?>
                    <div id="surat-elektronik">SURAT ELEKTRONIK</div>
                    <?php endif; ?>
                    <img src="<?= base_url('assets/img/logoOrmawa/LogoBEM.png') ?>" alt="Logo BEM FASILKOM UNSIKA">
                    <div class="kop-text">
                        <h1><?= str_replace('SAINS, DAN', 'SAINS,<br>DAN', $kop_extra['baris1'] ?? 'KEMENTERIAN PENDIDIKAN TINGGI, SAINS,<br>DAN TEKNOLOGI') ?></h1>
                        <h1><?= $kop_extra['baris2'] ?? 'UNIVERSITAS SINGAPERBANGSA KARAWANG' ?></h1>
                        <h2><?= $kop_extra['baris3'] ?? 'BADAN EKSEKUTIF MAHASISWA' ?></h2>
                        <h2><?= $kop_extra['baris4'] ?? 'FAKULTAS ILMU KOMPUTER' ?></h2>
                        <h2>PANITIA PELAKSANA</h2>
                        <h2><strong><?= strtoupper(esc($surat['nama_proker'])) ?></strong></h2>
                    </div>
                    <div style="clear: both;"></div>
                    <div style="width: 100%; text-align: center;">
                        <p class="contact" style="margin-top:5px; margin-bottom: 2px;">Sekretariat: <?= esc($bem['sekretariat'] ?? '-') ?> Hp: <?= esc($bem['nohp'] ?? '-') ?></p>
                        <p class="contact">Email: <?= esc($bem['email'] ?? '-') ?>&nbsp; Website: <?= esc($bem['website'] ?? '-') ?></p>
                    </div>
                    <div class="garis-kop"></div>
                </div>
            </td>
        </tr>
    </thead>
    <tbody style="display: table-row-group;">
        <tr>
            <td style="border: none !important; padding: 0 2.54cm 0 2.54cm !important; vertical-align: top;">
                <div class="isi-lampiran">
                    <?php
                        // Ambil baris teks pertama sebagai sub-judul lampiran.
                        // Langkah 1: Ubah tag blok menjadi newline agar teks tiap paragraf terpisah.
                        $subTitle = '';
                        $textOnly = preg_replace('/<\/(p|h[1-6]|li|div|tr|td|th|br)>/i', "\n", $lampHtml);
                        $textOnly = strip_tags($textOnly);
                        $textOnly = html_entity_decode($textOnly, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        foreach (explode("\n", $textOnly) as $line) {
                            if (($trimmed = trim($line)) !== '' && $trimmed !== "\xc2\xa0") {
                                $subTitle = " <span style='font-weight: normal !important;'>(" . ucwords(strtolower($trimmed)) . ")</span>";
                                break;
                            }
                        }
                    ?>
                    <p class="lampiran-label">Lampiran <?= $idx + 1 ?><?= $subTitle ?></p>
                    <div class="lampiran-content" style="margin-top: 12pt;">
                        <?php
                            // ✅ FIX #8 (XSS Lampiran): Bersihkan script/event handler dari konten lampiran
                            $allowedTagsLampiran = '<p><br><strong><em><u><s><span><ul><ol><li><table><thead><tbody><tr><td><th><colgroup><col><a><h1><h2><h3><h4><h5><h6><blockquote><div><hr><img>';
                            $safeLampHtml = preg_replace('/<(script|iframe|object|embed|form|input|button)(\s[^>]*)?>[\s\S]*?<\/\1>/i', '', $lampHtml);
                            $safeLampHtml = preg_replace('/\s(on\w+)\s*=\s*[\'"[^\'">]*[\'"]/i', '', $safeLampHtml);
                            echo strip_tags($safeLampHtml, $allowedTagsLampiran);
                        ?>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
    <tfoot style="display: table-footer-group;">
        <tr>
            <!-- Margin bawah permanen 2.54cm di tiap potongan halaman -->
            <td style="height: 2.54cm; border: none !important; padding: 0 !important;"></td>
        </tr>
    </tfoot>
</table>
<?php endif; ?>
<?php endforeach; ?>


<!-- ============================================================
     SCRIPT PRINT 
============================================================ -->
<?php if (!isset($is_preview) || !$is_preview): ?>
<script>
    function startPrint() {
        var btn = document.getElementById('dl-btn');
        if (btn) btn.style.display = 'none';
        window.print();
    }
    
    // Auto-print saat halaman selesai dimuat
    window.addEventListener('load', function() {
        setTimeout(startPrint, 500);
    });

    // Kembali otomatis ke dashboard/halaman sebelumnya setelah dialog print ditutup
    window.addEventListener('afterprint', function() {
        setTimeout(function() {
            // Hanya jalankan auto-back/close jika BUKAN dipanggil lewat iframe (dashboard script)
            if (window.self === window.top) {
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    window.close();
                }
            }
        }, 500);
    });
</script>
<?php endif; ?>

</body>
</html>
