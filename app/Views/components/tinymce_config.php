
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
/**
 * tinymce_config.php
 * 
 * Komponen konfigurasi editor teks TinyMCE terpusat.
 * Di-include oleh: Sekpel/Surat/create.php dan Sekpel/Surat/edit.php
 * 
 * Untuk menambah/mengubah template lampiran (TOR, Rundown, Berita Acara, dll),
 * cukup edit file ini saja — tidak perlu menyentuh file form secara langsung.
 */

// Inisialisasi TinyMCE
function initTinyMCE(selector = '.tinymce-lampiran') {
    tinymce.init({
        selector: selector,
        height: 400,
        menubar: false,
        plugins: 'table lists template searchreplace wordcount fullscreen charmap advlist nonbreaking',
        toolbar: [
            'template | undo redo | fontfamily fontsize | bold italic underline strikethrough | superscript subscript | forecolor backcolor',
            'alignleft aligncenter alignright alignjustify | lineheight | bullist numlist | outdent indent | table charmap hr | searchreplace removeformat fullscreen'
        ],
        nonbreaking_force_tab: true,
        font_size_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt',
        font_family_formats: 'Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
        lineheight_formats: '1 1.15 1.5 2 2.5 3',
        content_style: `
            body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.15; }
            p { margin: 0; margin-bottom: 4.2pt; }
            table { border-collapse: collapse; }
            table p { margin-bottom: 0 !important; }
            /* Pastikan teks dalam sel mengikuti alignment dari <td> (bukan override ke kiri) */
            table td, table th { text-align: inherit; }
            table td p, table th p, table td span, table th span { text-align: inherit; }
            table.table-no-border, table.table-no-border td, table.table-no-border th {
                border: none !important;
                padding: 2px 6px;
            }
        `,
        table_default_attributes: { border: '1' },
        table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
        table_sizing_mode: 'relative',
        table_class_list: [
            { title: 'Normal (Bertepi)',   value: '' },
            { title: 'Tanpa Garis/Border', value: 'table-no-border' }
        ],
        // ================================================================
        // TEMPLATE LAMPIRAN
        // Tambahkan template baru di sini dengan menyalin blok { title, description, content }
        // ================================================================
        templates: [
            {
                title: 'Template Term of Reference (TOR)',
                description: 'Format struktur baku TOR Kepanitiaan',
                content: `
<p style="text-align: center;"><strong><em>TERM OF REFERENCE</em> (TOR)</strong></p>
<p style="text-align: center;"><strong>LATIHAN KETERAMPILAN MANAJEMEN MAHASISWA</strong></p>
<p style="text-align: center;"><strong>(LKMM) 202X</strong></p>
<p style="text-align: center;"><strong>BADAN EKSEKUTIF MAHASISWA</strong></p>
<p style="text-align: center;"><strong>FAKULTAS ILMU KOMPUTER</strong></p>
<p style="text-align: center;"><strong>UNIVERSITAS SINGAPERBANGSA KARAWANG</strong></p>
<p>&nbsp;</p>
<table class="table-no-border" style="width: 100%; border-collapse: collapse; border: none;">
    <tbody>
        <tr>
            <td style="width: 20%; border: none;">Pembicara</td>
            <td style="width: 5%; border: none; text-align: center;">:</td>
            <td style="width: 75%; border: none;">[Masukkan Nama &amp; Gelar Pembicara] / [Instansi]</td>
        </tr>
        <tr>
            <td style="width: 20%; border: none;">Materi</td>
            <td style="width: 5%; border: none; text-align: center;">:</td>
            <td style="width: 75%; border: none;">[Tuliskan Judul Materi yang Akan Dibawakan]</td>
        </tr>
    </tbody>
</table>
<p>&nbsp;</p>
<p><strong>A.&nbsp;&nbsp; Gambaran Umum</strong></p>
<p style="text-align: justify; text-indent: 1.25cm;">[Tuliskan gambaran umum dan latar belakang mengenai penyelenggaraan kegiatan atau materi. Jelaskan mengapa kegiatan ini dirancang dan peranannya...]</p>
<p>&nbsp;</p>
<p><strong>B.&nbsp;&nbsp; Bentuk Acara</strong></p>
<p style="text-align: justify; text-indent: 1.25cm;">[Tuliskan gambaran pelaksanaan sesinya. Misal: Sesi acara ini berupa pemaparan materi, sesi tanya jawab/diskusi, dan lain-lain...]</p>
<p>&nbsp;</p>
<p><strong>C.&nbsp;&nbsp; Peserta</strong></p>
<p style="text-align: justify; text-indent: 1.25cm;">[Tuliskan peserta yang mengikuti agenda ini. Misal: Peserta terdiri dari Mahasiswa/i Fakultas Ilmu Komputer Angkatan XXXX...]</p>
<p>&nbsp;</p>
<p><strong>D.&nbsp;&nbsp; Target dan Arahan Materi</strong></p>
<p style="text-align: justify; text-indent: 1.25cm;">Yang akan dibahas dalam materi ini yaitu:</p>
<ol style="margin-left: 0.8cm; padding-left: 0;">
    <li>[Poin 1 materi utama]</li>
    <li>[Poin 2 landasan materi]</li>
    <li>[Poin 3 hasil sasaran]</li>
</ol>
<p>&nbsp;</p>
<p><strong>E.&nbsp;&nbsp; Tujuan Umum</strong></p>
<p style="text-align: justify; text-indent: 1.25cm;">[Tuliskan tujuan umum diberikannya materi atau diselenggarakannya agenda ini bagi masa depan peserta...]</p>
<p>&nbsp;</p>
<p><strong>F.&nbsp;&nbsp; Acara</strong></p>
<p style="text-align: justify; text-indent: 1.25cm;">[Gambarkan bagaimana konsep sesi acara berjalan serta pembagian alokasi waktunya secara keseluruhan...]</p>
<p>&nbsp;</p>
<p><strong>G.&nbsp;&nbsp; Waktu dan Tempat</strong></p>
<p style="text-align: justify; text-indent: 1.25cm;">Adapun waktu dan tempat pelaksanaan kegiatan ini adalah:</p>
<table class="table-no-border" style="width: 100%; border-collapse: collapse; border: none; margin-left: 1.25cm;">
    <tbody>
        <tr>
            <td style="width: 25%; border: none;">Hari, tanggal</td>
            <td style="width: 5%; border: none; text-align: center;">:</td>
            <td style="width: 70%; border: none;">[Hari, DD MMMM YYYY]</td>
        </tr>
        <tr>
            <td style="width: 25%; border: none;">Waktu</td>
            <td style="width: 5%; border: none; text-align: center;">:</td>
            <td style="width: 70%; border: none;">[00.00] WIB &ndash; Selesai</td>
        </tr>
        <tr>
            <td style="width: 25%; border: none;">Tempat</td>
            <td style="width: 5%; border: none; text-align: center;">:</td>
            <td style="width: 70%; border: none;">[Nama Tempat / Ruangan Pelaksanaan]</td>
        </tr>
        <tr>
            <td style="width: 25%; border: none; vertical-align: top;">Kedatangan</td>
            <td style="width: 5%; border: none; text-align: center; vertical-align: top;">:</td>
            <td style="width: 70%; border: none; text-align: justify;">Diharap pemateri dapat hadir tepat waktu minimal 30 menit sudah berada di tempat pelaksanaan agar dapat menyampaikan materi sesuai agenda.</td>
        </tr>
        <tr>
            <td style="width: 25%; border: none; vertical-align: top;">Keterlambatan</td>
            <td style="width: 5%; border: none; text-align: center; vertical-align: top;">:</td>
            <td style="width: 70%; border: none; text-align: justify;">Apabila pemateri mengalami keterlambatan, jadwal penyampaian materi akan mengalami penyesuaian yang memengaruhi jalannya seluruh rangkaian kegiatan.</td>
        </tr>
    </tbody>
</table>
                `
            },
            {
                title: 'Template Susunan Acara (Rundown)',
                description: 'Format dasar bagan tabel Rundown Acara',
                content: `
<table style="width: 100%; border-collapse: collapse; text-align: center; line-height: 1" border="1">
    <thead>
        <tr>
            <th style="padding: 5px;">Waktu</th>
            <th style="padding: 5px;">Durasi</th>
            <th style="padding: 5px;">Kegiatan</th>
            <th style="padding: 5px;">Penanggung Jawab</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding: 5px; vertical-align: middle;">08.00 &ndash; 08.15</td>
            <td style="padding: 5px; vertical-align: middle;">15"</td>
            <td style="padding: 5px; text-align: left;">Registrasi Peserta</td>
            <td style="padding: 5px;">Divisi Acara</td>
        </tr>
        <tr>
            <td style="padding: 5px; vertical-align: middle;">08.15 &ndash; 08.30</td>
            <td style="padding: 5px; vertical-align: middle;">15"</td>
            <td style="padding: 5px; text-align: left;">Pembukaan Acara Formal</td>
            <td style="padding: 5px;">Master of Ceremony</td>
        </tr>
    </tbody>
</table>
<p>&nbsp;</p>
                `
            },
            {
                title: 'Template Berita Acara (BA) Formatur',
                description: 'Format baku Lampiran Berita Acara Rapat Formatur',
                content: `
<p class="lampiran-label"><strong>Lampiran 1</strong> <em>(Berita Acara Rapat Formatur)</em></p>
<p>&nbsp;</p>
<p style="text-align: center;"><strong>BERITA ACARA RAPAT FORMATUR</strong></p>
<p>&nbsp;</p>
<p style="text-align: justify; text-indent: 1.25cm;"><em>Bismillahirrohmanirrahim.</em></p>
<p style="text-align: justify; text-indent: 1.25cm;">Pada Hari <strong>[Hari]</strong>, Tanggal <strong>[DD Bulan]</strong>, Tahun <strong>[YYYY]</strong>, bertempat secara <em>offline</em> di <em>[Tempat / Ruangan]</em>, telah dilaksanakan rapat formatur yang menghasilkan keputusan bahwa mahasiswa dengan keterangan di bawah ini:</p>
<p>&nbsp;</p>
<table class="table-no-border" style="width: 100%; border-collapse: collapse; border: none; margin-left: 1.25cm;">
    <tbody>
        <tr>
            <td style="width: 25%; border: none;">Nama</td>
            <td style="width: 5%; border: none; text-align: center;">:</td>
            <td style="width: 70%; border: none;">[Nama Lengkap Terpilih]</td>
        </tr>
        <tr>
            <td style="width: 25%; border: none;">NPM</td>
            <td style="width: 5%; border: none; text-align: center;">:</td>
            <td style="width: 70%; border: none;">[NPM Terpilih]</td>
        </tr>
        <tr>
            <td style="width: 25%; border: none;">Semester</td>
            <td style="width: 5%; border: none; text-align: center;">:</td>
            <td style="width: 70%; border: none;">[Sem. Saat Ini, Cth: IV (Empat)]</td>
        </tr>
        <tr>
            <td style="width: 25%; border: none;">Tahun Masuk</td>
            <td style="width: 5%; border: none; text-align: center;">:</td>
            <td style="width: 70%; border: none;">[Tahun Angkatan]</td>
        </tr>
    </tbody>
</table>
<p>&nbsp;</p>
<p style="text-align: justify; text-indent: 1.25cm;"><em>Terpilih sebagai ketua pelaksana kegiatan</em> <strong>[Nama Kegiatan/Acara] [Tahun] Fakultas Ilmu Komputer Universitas Singaperbangsa Karawang.</strong></p>
<p style="text-align: justify; text-indent: 1.25cm;">Demikian berita acara ini dibuat dengan sebenarnya dan untuk digunakan dalam kebutuhan permohonan surat keputusan kepanitiaan.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table class="table-no-border" style="width: 100%; border-collapse: collapse; border: none;">
    <tbody>
        <tr>
            <td style="width: 60%; border: none;"></td>
            <td style="width: 40%; border: none; text-align: left;">
                <p>Karawang, [DD Bulan YYYY]</p>
                <p><strong>Ketua Pelaksana</strong></p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p><strong><u>[Nama Ketua Pelaksana]</u></strong><br />NPM: [NPM Ketua]</p>
            </td>
        </tr>
    </tbody>
</table>
`
            }
        ],
        promotion: false,
        branding: false,
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });

            // Perbaikan: terapkan alignment ke sel tabel yang terseleksi
            // (termasuk saat Ctrl+A → semua isi editor terseleksi)
            editor.on('ExecCommand', function (e) {
                var alignMap = {
                    JustifyLeft: 'left',
                    JustifyCenter: 'center',
                    JustifyRight: 'right',
                    JustifyFull: 'justify'
                };
                if (!alignMap[e.command]) return;
                var align = alignMap[e.command];

                // 1. Cari sel yang diseleksi via drag (TinyMCE beri atribut data-mce-selected)
                var cells = Array.from(editor.dom.select('td[data-mce-selected], th[data-mce-selected]'));

                // 2. Jika tidak ada (misal Ctrl+A), cari semua sel dalam jangkauan seleksi
                if (!cells.length) {
                    var rng = editor.selection.getRng();
                    var ancestor = rng.commonAncestorContainer;
                    if (ancestor.nodeType === 3) ancestor = ancestor.parentNode;
                    if (ancestor && ancestor.querySelectorAll) {
                        cells = Array.from(ancestor.querySelectorAll('td, th'));
                    }
                }

                if (cells.length) {
                    cells.forEach(function (cell) {
                        // Set langsung ke <td>/<th>
                        editor.dom.setStyle(cell, 'text-align', align);
                        // Set juga ke <p> di dalamnya
                        Array.from(cell.querySelectorAll('p')).forEach(function (p) {
                            editor.dom.setStyle(p, 'text-align', align);
                        });
                    });
                    editor.undoManager.add();
                }
            });
        }
    });
}

// Editor minimal khusus paragraf surat (bold, italic, underline saja)
function initTinyMCEParagraf(selector = '.tinymce-paragraf') {
    tinymce.init({
        selector: selector,
        height: 160,
        menubar: false,
        plugins: [],
        toolbar: 'bold italic underline | removeformat',
        statusbar: false,
        promotion: false,
        branding: false,
        content_style: `
            body {
                font-family: 'Times New Roman', Times, serif;
                font-size: 12pt;
                line-height: 1.15;
                margin: 6px 10px;
            }
            p { margin: 0; margin-bottom: 4.2pt; }
            table p { margin-bottom: 0 !important; }
        `,
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
}
document.addEventListener('DOMContentLoaded', function() {
    initTinyMCE();
    initTinyMCEParagraf();

// --- Logic Tambah Halaman Lampiran ---
document.getElementById('btnAddLampiran').addEventListener('click', function() {
    const container = document.getElementById('lampiranContainer');
    const count = container.querySelectorAll('.lampiran-item').length + 1;
    const newId = 'lampiranEditor_' + new Date().getTime();

    const newItem = document.createElement('div');
    newItem.className = 'lampiran-item mb-4 shadow-sm border rounded';
    newItem.innerHTML = `
        <div class="d-flex justify-content-between align-items-center bg-light px-3 py-2 border-bottom">
            <span class="fw-bold text-secondary lampiran-title" style="font-size:13px;">Halaman ${count}</span>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-lampiran py-0 px-2"><i class="fa-solid fa-trash me-1"></i>Hapus Halaman</button>
        </div>
        <textarea id="${newId}" name="isi_lampiran[]" class="tinymce-lampiran"></textarea>
    `;

    container.appendChild(newItem);
    initTinyMCE('#' + newId);

    newItem.querySelector('.btn-remove-lampiran').addEventListener('click', function() {
        if (confirm('Yakin ingin menghapus halaman lampiran ini?')) {
            tinymce.remove('#' + newId);
            newItem.remove();
            updateLampiranTitles();
        }
    });
});

function updateLampiranTitles() {
    const items = document.querySelectorAll('#lampiranContainer .lampiran-item');
    items.forEach((item, index) => {
        const titleSpan = item.querySelector('.lampiran-title');
        if (titleSpan) titleSpan.textContent = 'Halaman ' + (index + 1);
    });
}

// --- Live Preview Surat ---
document.getElementById('btnPreviewSurat').addEventListener('click', function () {
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Menyusun Kertas PDF...';
    btn.disabled = true;

    if (typeof tinymce !== 'undefined') tinymce.triggerSave();

    const formData = new FormData(document.getElementById('formSurat'));

    fetch('<?= base_url('/sekpel/surat/preview') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.text();
    })
    .then(html => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        const printFrame = document.createElement('iframe');
        printFrame.style.display = 'none';
        document.body.appendChild(printFrame);

        const blob = new Blob([html], { type: 'text/html' });
        const blobUrl = URL.createObjectURL(blob);

        printFrame.onload = function () {
            URL.revokeObjectURL(blobUrl);
            setTimeout(() => {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
                setTimeout(() => document.body.removeChild(printFrame), 2000);
            }, 500);
        };
        printFrame.src = blobUrl;
    })
    .catch(err => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Gagal menyusun preview surat. Error: ' + err.message);
    });
});
});
</script>
