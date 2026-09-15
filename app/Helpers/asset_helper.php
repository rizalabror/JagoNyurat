<?php

/**
 * asset_helper.php
 *
 * Helper kustom untuk manajemen aset modular di CodeIgniter 4.
 * Di-load otomatis melalui BaseController.
 *
 * Fungsi:
 *   - pushOnce($key, $content) : Mencetak konten HTML hanya SATU kali,
 *     meskipun fungsi/komponen dipanggil berkali-kali dalam satu request.
 *     Mencegah duplikasi tag <script> atau <link> yang tidak disengaja.
 *
 * Contoh Penggunaan di View:
 *   <?php pushOnce('tinymce', view('components/tinymce_config')); ?>
 */

if (!function_exists('pushOnce')) {
    /**
     * Cetak konten (string HTML) hanya satu kali per key unik.
     *
     * @param string $key     Identifier unik untuk konten ini (misal: 'tinymce', 'datatables')
     * @param string $content Konten HTML/JS yang akan dicetak
     */
    function pushOnce(string $key, string $content): void
    {
        static $loaded = [];

        if (!isset($loaded[$key])) {
            $loaded[$key] = true;
            echo $content;
        }
    }
}
