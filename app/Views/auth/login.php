<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="description" content="Portal pintu masuk Sistem Administrasi Surat BEM FASILKOM UNSIKA. Masuk menggunakan akun terdaftar untuk memulai pengelolaan birokrasi proposal kepanitiaan."/>
<title>Login - Sistem Administrasi Surat BEM FASILKOM</title>
<link href="<?= base_url('css/tailwind.min.css') ?>" rel="stylesheet"/>
<!-- Preconnect Google Fonts: non-blocking agar tidak render-blocking -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap"></noscript>
<link href="<?= base_url('fontawesome/css/all.min.css') ?>" rel="stylesheet" />
<style>
    * { box-sizing: border-box; }
    body {
        margin: 0;
        background: #f1f5f9;
        font-family: 'Manrope', 'Inter', sans-serif;
        min-height: 100vh;
    }
    .button-gradient {
        background: linear-gradient(135deg, #0051d5 0%, #316bf3 100%);
    }
    /* ===== LAYOUT ===== */
    .login-wrap {
        display: flex;
        min-height: 100vh;
    }
    /* Left panel (desktop) */
    .login-left {
        display: flex;
        flex-direction: column;
        position: relative;
        width: 50%;
        background: linear-gradient(135deg, #00174b 0%, #0051d5 100%);
        overflow: hidden;
        padding: 3rem 3.5rem;
        justify-content: center;
        gap: 2.5rem;
        flex-shrink: 0;
    }
    /* Right panel */
    .login-right {
        flex: 1;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        overflow-y: auto;
    }
    /* Mobile branding header — hidden on desktop */
    .mobile-branding {
        display: none;
    }

    /* ===== MOBILE ===== */
    @media (max-width: 767px) {
        .login-wrap {
            flex-direction: column;
            min-height: 100vh;
        }
        /* Hide desktop left panel */
        .login-left {
            display: none;
        }
        /* Show mobile branding header */
        .mobile-branding {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #00174b 0%, #0051d5 100%);
            padding: 2rem 1.5rem;
            text-align: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }
        .mobile-branding::before {
            content: '';
            position: absolute;
            top: -4rem; left: -4rem;
            width: 16rem; height: 16rem;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            filter: blur(40px);
        }
        .mobile-branding::after {
            content: '';
            position: absolute;
            bottom: -3rem; right: -3rem;
            width: 12rem; height: 12rem;
            border-radius: 50%;
            background: rgba(96,165,250,0.12);
            filter: blur(50px);
        }
        .login-right {
            flex: 1;
            background: #f8fafc;
            padding: 1.75rem 1.25rem 2rem;
            align-items: center;
            justify-content: flex-start;
        }
    }
</style>
</head>
<body>

<!-- MOBILE BRANDING HEADER (only visible on mobile) -->
<div class="mobile-branding">
    <!-- Logo row -->
    <div style="position:relative; z-index:10; display:flex; align-items:center; gap:0.65rem;">
        <div style="width:-6rem; height:-6rem; background:rgba(255,255,255,0.12); backdrop-filter:blur(10px); border-radius:0.85rem; display:flex; align-items:center; justify-content:center; overflow:hidden;">
            <img src="<?= base_url('assets/img/logoOrmawa/LogoBEM.png') ?>" alt="Logo BEM" style="width:5rem; height:5rem; object-fit:contain;">
        </div>
        <span style="font-weight:700; color:white; font-size:1rem; text-transform:uppercase; letter-spacing:-0.01em;">BEM FASILKOM UNSIKA</span>
    </div>
    <!-- Title -->
    <div style="position:relative; z-index:10;">
        <p style="color:white; font-weight:800; font-size:1.25rem; margin:0; line-height:1.3;">Sistem Administrasi Surat</p>
        <p style="color:rgba(219,234,254,0.8); font-size:0.85rem; font-weight:300; margin:0.25rem 0 0 0; line-height:1.5;">Kelola dan ajukan surat kepanitiaan dengan mudah.</p>
    </div>
</div>

<main class="login-wrap">
<!-- Left Side (Visual & Branding) — Desktop only -->
<section class="login-left">
<!-- Decorative Elements -->
<div style="position:absolute; top:-6rem; left:-6rem; width:24rem; height:24rem; border-radius:50%; background:rgba(255,255,255,0.05); filter:blur(60px);"></div>
<div style="position:absolute; top:50%; right:-12rem; width:32rem; height:32rem; border-radius:50%; background:rgba(96,165,250,0.1); filter:blur(80px);"></div>
<!-- Logo -->
<div style="position:relative; z-index:10; display:flex; align-items:center; gap:0.75rem;">
    <img src="<?= base_url('assets/img/logoOrmawa/LogoBEM.png') ?>" alt="Logo BEM" style="width:5rem; height:5rem; object-fit:contain;">
    <span style="font-weight:700; color:white; letter-spacing:-0.01em; font-size:1.1rem; text-transform:uppercase;">BEM FASILKOM UNSIKA</span>
</div>
<!-- Headline -->
<div style="position:relative; z-index:10; max-width:28rem;">
    <h1 style="font-weight:900; font-size:2.25rem; color:white; line-height:1.2; margin:0 0 1rem 0;">
        Sistem Administrasi Surat BEM FASILKOM
    </h1>
    <p style="color:rgba(219,234,254,0.85); font-size:1rem; font-weight:300; line-height:1.7; margin:0;">
        Kelola dan ajukan surat kepanitiaan dengan mudah, cepat, dalam satu sistem.
    </p>
</div>
<!-- Abstract Shapes -->
<div style="position:absolute; top:25%; right:2.5rem; width:8rem; height:8rem; background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.08); border-radius:1.5rem; transform:rotate(12deg); opacity:0.5;"></div>
<div style="position:absolute; bottom:25%; left:5rem; width:12rem; height:12rem; background:rgba(255,255,255,0.04); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.06); border-radius:50%; transform:rotate(-12deg); opacity:0.25;"></div>
</section>

<!-- Right Side (Login Form) -->
<section class="login-right">
<div class="w-full max-w-md">
<!-- Login Card -->
<div class="bg-surface-container-lowest rounded-xl p-8 md:p-10 shadow-[0_20px_50px_-20px_rgba(0,81,213,0.1)] border border-outline-variant/10">
<div class="mb-10">
<h2 class="font-headline text-3xl font-bold text-on-surface mb-2">Selamat Datang</h2>
<p class="text-on-surface-variant text-sm">Silakan masuk ke portal JagoNyurat.</p>
</div>

<!-- Alert Error -->
<?php if (session('error') !== null) : ?>
    <div class="mb-6 bg-error-container text-on-error-container border border-error/20 p-4 rounded-xl text-sm font-medium">
        <?= session('error') ?>
    </div>
<?php elseif (session('errors') !== null) : ?>
    <div class="mb-6 bg-error-container text-on-error-container border border-error/20 p-4 rounded-xl text-sm font-medium">
        <?php if (is_array(session('errors'))) : ?>
            <ul class="list-disc list-inside">
            <?php foreach (session('errors') as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
            </ul>
        <?php else : ?>
            <?= session('errors') ?>
        <?php endif ?>
    </div>
<?php endif ?>

<?php if (session('message') !== null) : ?>
    <div class="mb-6 bg-primary-fixed text-on-primary-fixed border border-primary/20 p-4 rounded-xl text-sm font-medium">
        <?= session('message') ?>
    </div>
<?php endif ?>

<form action="<?= route_to('login') ?>" method="post" class="space-y-6">
<?= csrf_field() ?>

<!-- Email Field -->
<div style="margin-bottom: 1.25rem;">
    <label style="display:block; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#6b7280; margin-bottom:0.5rem;" for="email">Email</label>
    <div style="position:relative;">
        <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none; display:flex; align-items:center;">
            <i class="fa-solid fa-envelope" style="font-size:0.95rem;"></i>
        </span>
        <input style="width:100%; padding:0.75rem 1rem 0.75rem 2.75rem; background:#f3f4f6; border:2px solid transparent; border-radius:0.75rem; outline:none; font-size:0.95rem; color:#111827; transition:all .2s; box-sizing:border-box;"
               id="email" name="login" placeholder="Masukkan Email Anda" type="email"
               value="<?= old('login') ?>" required
               onfocus="this.style.borderColor='rgba(0,81,213,0.4)'; this.style.background='#fff';"
               onblur="this.style.borderColor='transparent'; this.style.background='#f3f4f6';"/>
    </div>
</div>

<!-- Password Field -->
<div style="margin-bottom: 1.25rem;">
    <label style="display:block; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#6b7280; margin-bottom:0.5rem;" for="password">Password</label>
    <div style="position:relative;">
        <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none; display:flex; align-items:center;">
            <i class="fa-solid fa-lock" style="font-size:0.95rem;"></i>
        </span>
        <input style="width:100%; padding:0.75rem 3rem 0.75rem 2.75rem; background:#f3f4f6; border:2px solid transparent; border-radius:0.75rem; outline:none; font-size:0.95rem; color:#111827; transition:all .2s; box-sizing:border-box;"
               id="password" name="password" placeholder="••••••••" type="password" required
               onfocus="this.style.borderColor='rgba(0,81,213,0.4)'; this.style.background='#fff';"
               onblur="this.style.borderColor='transparent'; this.style.background='#f3f4f6';"/>
        <button style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:0; display:flex; align-items:center;"
                type="button" aria-label="Toggle Password Visibility"
                onclick="const p=document.getElementById('password'); p.type=(p.type==='password')?'text':'password'; this.querySelector('i').className=(p.type==='text')?'fa-solid fa-eye-slash':'fa-solid fa-eye';">
            <i class="fa-solid fa-eye" style="font-size:1rem;"></i>
        </button>
    </div>
</div>

<!-- Login Button -->
<div class="pt-4 space-y-4">
<button class="button-gradient w-full py-3.5 rounded-xl text-white font-semibold flex items-center justify-center gap-2 shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 transition-all duration-200" type="submit">
<span>Masuk</span>
</button>
</div>
</form>

</div>
<!-- Footer -->
<footer class="mt-8 flex flex-col items-center gap-4">
<p class="text-[10px] text-outline uppercase tracking-[0.1em] text-center">
    © <?= date('Y') ?> JagoNyurat - BEM FASILKOM UNSIKA.
</p>
</footer>
</div>
<!-- Background subtle detail -->
<div class="absolute bottom-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-[80px] -z-10"></div>
<div class="absolute top-0 left-0 w-64 h-64 bg-tertiary-container/5 rounded-full blur-[80px] -z-10"></div>
</section>
</main>
</body>
</html>