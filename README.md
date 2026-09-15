# JagoNyurat

Sistem administrasi surat elektronik berbasis web untuk kepanitiaan BEM FASILKOM UNSIKA. Aplikasi ini mempermudah proses pembuatan, pengajuan, review, dan pengesahan surat secara digital.

Terdapat dua peran utama:
- **Sekretaris Pelaksana (Sekpel)** — membuat dan mengajukan surat
- **Sekretaris Umum (Sekum)** — mereview, menyetujui, dan mengelola semua surat

Surat yang disetujui dapat langsung diunduh sebagai **PDF berkop surat** dengan **tanda tangan digital**.

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend Framework | CodeIgniter 4 (PHP 8.1+) |
| Autentikasi & Otorisasi | Myth/Auth 1.2 |
| Database | MySQL / MariaDB |
| PDF Generator | DomPDF 3.x |
| CSS | Tailwind CSS 3.4 + Bootstrap 5.2 (hybrid) |
| Rich Text Editor | TinyMCE 6 (CDN) |
| Icons | Material Symbols Outlined + FontAwesome 6 |
| Fonts | Manrope & Inter (Google Fonts) |
| Build Tool CSS | Tailwind CLI (npx) |

---

## Prasyarat

- **PHP** >= 8.1 dengan ekstensi: `mysqli`, `intl`, `mbstring`, `json`, `fileinfo`
- **Composer** >= 2.x
- **Node.js** >= 18.x dan **npm** (untuk kompilasi Tailwind CSS)
- **MySQL** >= 5.7 atau **MariaDB** >= 10.4
- Web server: **XAMPP / Laragon / php spark serve**

---

## Setup & Quick Start

### 1. Clone & Install Dependensi

```bash
git clone https://github.com/rizalabror/JagoNyurat.git
cd JagoNyurat

composer install
npm install
```

### 2. Konfigurasi Environment

```bash
# Windows
copy .env.example .env

# Linux / Mac
cp .env.example .env
```

Edit file `.env` sesuai konfigurasi lokal (lihat bagian [Environment Variables](#environment-variables)).

### 3. Buat Database & Import

Buat database baru di MySQL/phpMyAdmin, lalu import:

```bash
mysql -u root -p nama_database_anda < jago_nyurat.sql
```

> File `jago_nyurat.sql` sudah berisi struktur tabel, data grup, dan akun default siap pakai.

Atau jalankan migrasi (jika tidak menggunakan SQL dump):

```bash
php spark migrate
```

### 4. Kompilasi Tailwind CSS

```bash
npx tailwindcss -i public/css/tailwind-input.css -o public/css/tailwind.min.css --minify
```

> **Wajib dijalankan** setiap kali ada perubahan class Tailwind baru di file PHP/JS. Tanpa langkah ini, tampilan bisa rusak.

### 5. Jalankan Server

```bash
php spark serve
```

Buka di browser: `http://localhost:8080`

---

## Environment Variables

Salin dari `.env.example` lalu sesuaikan:

```env
# ─── Environment ──────────────────────────────────────────────
CI_ENVIRONMENT = development        # development | production

# ─── App ──────────────────────────────────────────────────────
app.baseURL = 'http://localhost:8080/'  # Wajib diisi dengan benar, termasuk trailing slash

# ─── Database ─────────────────────────────────────────────────
database.default.hostname = 127.0.0.1
database.default.database = jago_nyurat   # Nama database yang sudah dibuat
database.default.username = root          # Username MySQL
database.default.password =              # Password MySQL (kosong jika XAMPP default)
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

---

## Akun Default (dari SQL Dump)

Setelah import `jago_nyurat.sql`, tersedia akun siap pakai berikut:

| Role | Email Login | Password |
|---|---|---|
| **Sekum** | `2210631250009@student.unsika.ac.id` | *(lihat di SQL dump / tanya pengelola)* |
| **Sekpel** | `pkkmb2026@gmail.com` | *(lihat di SQL dump / tanya pengelola)* |

### Membuat Akun Baru

Akun baru **hanya bisa dibuat oleh Sekum** melalui menu **Manajemen User** di dashboard Sekum. Tidak ada halaman registrasi publik.

---

## Alur Kerja Surat

```
[Sekpel] Buat Surat
    │
    ▼
[Sekpel] Ajukan Surat → status: "Diajukan"
    │
    ▼
[Sekum] Terima notifikasi → Review surat
    │
    ├── Setujui → status: "Disetujui" → PDF siap diunduh
    │
    └── Tolak  → status: "Ditolak" → Sekpel dapat merevisi & ajukan ulang
```

### Detail Setiap Langkah

1. **Login** sebagai Sekpel
2. **Lengkapi Profil** — wajib sebelum bisa akses menu (nama, NPM, tanda tangan, data kepanitiaan). Dijaga oleh `ProfileCompletionFilter`.
3. **Buat Surat** — isi form surat dengan TinyMCE editor, pilih program kerja, lampirkan file jika perlu
4. **Ajukan Surat** — klik tombol "Ajukan", surat masuk ke antrian Sekum
5. **Sekum Review** — login sebagai Sekum, lihat daftar surat masuk, buka detail, setujui atau tolak
6. **Download PDF** — jika disetujui, Sekpel/Sekum dapat unduh surat sebagai PDF berkop + TTD digital

---

## Struktur Direktori Penting

```
app/
├── Config/
│   ├── Routes.php          # Definisi semua route + filter role
│   └── Filters.php         # Registrasi RoleFilter & ProfileCompletionFilter
├── Controllers/
│   ├── SekpelController.php  # Logika pembuatan & pengajuan surat (Sekpel)
│   ├── SekumController.php   # Logika review, approval, user management (Sekum)
│   └── ProfileController.php # Onboarding & edit profil Sekpel
├── Filters/
│   ├── RoleFilter.php                # Proteksi route berdasarkan role Myth/Auth
│   └── ProfileCompletionFilter.php   # Redirect jika Sekpel belum lengkapi profil
├── Models/
│   ├── SuratModel.php        # Model utama surat
│   ├── LampiranSuratModel.php
│   └── ProgramKerjaModel.php
├── Views/
│   ├── templates/          # Layout utama (sidebar + topbar + wrapper)
│   ├── Sekpel/             # View halaman Sekpel
│   ├── Sekum/              # View halaman Sekum
│   ├── Profile/            # Halaman onboarding & edit profil
│   └── auth/               # Halaman login (standalone, tanpa template)
public/
├── css/
│   ├── tailwind-input.css  # Entry point Tailwind (sumber kompilasi) — edit di sini
│   └── tailwind.min.css    # Output CSS di browser — JANGAN edit manual
├── uploads/ttd/            # File tanda tangan yang diupload (PNG/JPG)
└── assets/img/             # Logo, avatar default, stempel
jago_nyurat.sql             # Dump database lengkap (termasuk data default)
tailwind.config.js          # Konfigurasi Tailwind (warna custom Material Design 3)
.env.example                # Template environment — salin ke .env
```

---

## Cara Menjalankan

### Development

```bash
php spark serve

# Watch CSS (auto-compile saat ada perubahan Tailwind):
npx tailwindcss -i public/css/tailwind-input.css -o public/css/tailwind.min.css --watch
```

Jalankan keduanya di terminal terpisah.

### Production

1. Set `CI_ENVIRONMENT = production` di `.env`
2. Set `app.baseURL` ke URL domain yang sebenarnya (dengan trailing slash)
3. Kompilasi CSS final:
   ```bash
   npx tailwindcss -i public/css/tailwind-input.css -o public/css/tailwind.min.css --minify
   ```
4. Arahkan **document root** web server ke folder `public/`
5. Pastikan `writable/` dapat ditulis:
   ```bash
   chmod -R 755 writable/
   ```

---

## Catatan Penting untuk Developer

### TinyMCE (Editor Konten Surat)
Editor dimuat **hanya via komponen `tinymce_config.php`** yang di-include di halaman yang membutuhkan editor.  
**Jangan** menambahkan script TinyMCE di `templates/index.php` — akan menyebabkan double-load dan editor tidak muncul.

### CSS Hybrid (Tailwind + Bootstrap)
- **Layout shell** (sidebar, topbar, wrapper) → menggunakan **Tailwind**
- **Konten dalam** (form, tabel, modal) → menggunakan **Bootstrap 5**
- Ada potensi bentrok pada tag `h1`–`h6`, `button`, dan `a`. Gunakan class Tailwind eksplisit untuk override jika diperlukan.

### Upload Tanda Tangan
File TTD disimpan di `public/uploads/ttd/`. Pastikan direktori ini:
- Dapat ditulis oleh web server
- **Tidak** di-ignore oleh `.gitignore` jika perlu di-backup (saat ini ter-ignore)

### Folder `writable/`
Digunakan CodeIgniter untuk cache, log, dan session. Harus memiliki izin tulis. **Jangan commit** isinya ke Git.

---

## Troubleshooting

### ❌ Tampilan CSS rusak / class Tailwind tidak muncul
```bash
npx tailwindcss -i public/css/tailwind-input.css -o public/css/tailwind.min.css --minify
```
Pastikan `tailwind.config.js` mencakup path semua file PHP/JS yang menggunakan class Tailwind.

### ❌ Error `Class "Myth\Auth\..." not found`
```bash
composer install
```
Atau:
```bash
composer dump-autoload
```

### ❌ TinyMCE editor tidak muncul
- Pastikan koneksi internet aktif (TinyMCE dimuat via CDN)
- Pastikan tidak ada script TinyMCE duplikat di `templates/index.php`
- Buka DevTools browser → cek tab Console untuk error JS

### ❌ Error `Unable to connect to the database`
- Periksa konfigurasi database di file `.env`
- Pastikan service MySQL/MariaDB sudah berjalan (XAMPP: klik Start pada MySQL)
- Pastikan nama database sudah dibuat dan sesuai dengan `.env`

### ❌ Halaman menampilkan `404 Not Found` setelah deploy
- Pastikan `app.baseURL` di `.env` sudah diisi dengan benar (termasuk trailing slash `/`)
- Pastikan document root web server mengarah ke folder `public/`, bukan root proyek
- Aktifkan `mod_rewrite` jika menggunakan Apache

### ❌ Sekpel langsung diredirect ke halaman profil setelah login
Ini perilaku normal. Sekpel **wajib melengkapi profil** (nama, NPM, tanda tangan, data kepanitiaan) sebelum bisa mengakses menu lainnya. Ini dijaga oleh `ProfileCompletionFilter`.

---

## Kontribusi

1. Fork repository ini
2. Buat branch baru: `git checkout -b fitur/nama-fitur`
3. Commit perubahan: `git commit -m "feat: deskripsi singkat"`
4. Push ke branch: `git push origin fitur/nama-fitur`
5. Buat Pull Request ke branch `main`

### Konvensi Commit

| Prefix | Digunakan untuk |
|---|---|
| `feat:` | Fitur baru |
| `fix:` | Perbaikan bug |
| `chore:` | Pemeliharaan, update dependensi |
| `refactor:` | Refaktor kode tanpa mengubah fungsi |
| `docs:` | Update dokumentasi |

---

## Lisensi

Proyek ini dibuat untuk keperluan internal BEM FASILKOM UNSIKA.  
Hak cipta © 2025–2026 Rizal Abror Munir.
