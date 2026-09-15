<?php

/**
 * Penomoran Helper
 * 
 * Fungsi-fungsi terpusat untuk membangun nomor surat resmi BEM.
 * Ditempatkan di sini agar SekpelController dan SekumController
 * merujuk ke satu sumber tunggal — tidak perlu diubah di dua tempat.
 */

if (!function_exists('generate_nomor_surat')) {
    /**
     * Membangun nomor surat otomatis sesuai format SOP BEM Fasilkom.
     *
     * Format: [Urut]/TPA-[KodeProker]/BEM-FASILKOM/[RomawiBulan]/[Tahun]
     * Surat pertama selalu bernomor ISTIMEWA.
     *
     * @param  int|null $prokerId   ID Program Kerja yang bersangkutan
     * @return string               Nomor surat yang telah diformat
     */
    function generate_nomor_surat(?int $prokerId): string
    {
        if (!$prokerId) {
            return 'SURAT/TPA/BEM-FASILKOM/' . date('Y');
        }

        $suratModel  = new \App\Models\SuratModel();
        $currentYear = date('Y');

        // Hitung berapa surat yang sudah pernah di-Approve untuk proker ini di tahun berjalan
        $approvedCount = $suratModel
            ->where('proker_id', $prokerId)
            ->where('status', 'Approved')
            ->where('YEAR(updated_at)', $currentYear)
            ->countAllResults();

        // Surat pertama selalu diberi nomor istimewa
        if ($approvedCount === 0) {
            return 'ISTIMEWA';
        }

        // Surat ke-2 dst: urutan dimulai dari 001
        $seq = str_pad($approvedCount, 3, '0', STR_PAD_LEFT);

        $prokerModel = new \App\Models\ProgramKerjaModel();
        $proker      = $prokerModel->find($prokerId);
        $kodeProker  = $proker['kode_proker'] ?? ($proker['singkatan'] ?? 'PROKER');

        $romanMonth  = nomor_romawi_bulan((int) date('n'));

        return "{$seq}/TPA-{$kodeProker}/BEM-FASILKOM/{$romanMonth}/{$currentYear}";
    }
}

if (!function_exists('nomor_romawi_bulan')) {
    /**
     * Mengonversi angka bulan (1–12) ke angka Romawi.
     *
     * @param  int    $bulan   Angka bulan (1 = Januari, 12 = Desember)
     * @return string          Representasi Romawi
     */
    function nomor_romawi_bulan(int $bulan): string
    {
        $peta = [
            1  => 'I',    2  => 'II',   3  => 'III',
            4  => 'IV',   5  => 'V',    6  => 'VI',
            7  => 'VII',  8  => 'VIII', 9  => 'IX',
            10 => 'X',    11 => 'XI',   12 => 'XII',
        ];

        return $peta[$bulan] ?? 'I';
    }
}
