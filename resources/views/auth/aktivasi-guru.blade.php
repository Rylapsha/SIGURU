<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Aktivasi Akun Guru - SIGURU</title>
    <style>
        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef6ff;
            color: #1e293b;
            font-family: Arial, Helvetica, sans-serif;
        }

        .activation-container {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            width: 100%;
            max-width: 1180px;
            min-height: 680px;
            overflow: hidden;
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 20px 50px rgba(30, 64, 175, 0.15);
        }

        .school-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 680px;
            padding: 50px;
            color: #fff;
            background: linear-gradient(135deg, rgba(8, 47, 107, .94), rgba(30, 100, 190, .86));
        }

        .school-logo-area { display: flex; align-items: center; gap: 16px; margin-bottom: 45px; }
        .school-logo { width: 72px; height: 72px; padding: 6px; object-fit: contain; background: #fff; border-radius: 16px; }
        .school-name { font-size: 17px; font-weight: 700; line-height: 1.4; }
        .school-location { margin-top: 4px; font-size: 13px; opacity: .85; }
        .welcome-title { max-width: 520px; margin: 0 0 20px; font-size: 40px; line-height: 1.2; }
        .welcome-description { max-width: 500px; color: rgba(255,255,255,.92); font-size: 16px; line-height: 1.8; }
        .slogan { display: inline-block; margin-top: 28px; padding: 10px 18px; border: 1px solid rgba(255,255,255,.35); border-radius: 30px; background: rgba(255,255,255,.1); font-size: 14px; }
        .school-address { font-size: 13px; line-height: 1.7; color: rgba(255,255,255,.85); }

        .form-panel { display: flex; align-items: center; justify-content: center; padding: 60px 65px; background: #fff; }
        .form-wrapper { width: 100%; max-width: 410px; }
        .form-title { margin: 0 0 10px; color: #0f3b73; font-size: 30px; }
        .form-description { margin: 0 0 26px; color: #64748b; font-size: 14px; line-height: 1.7; }
        .form-group { margin-bottom: 17px; }
        .form-label { display: block; margin-bottom: 8px; color: #334155; font-size: 14px; font-weight: 600; }
        .form-input { width: 100%; height: 50px; padding: 0 15px; border: 1px solid #cbd5e1; border-radius: 12px; outline: none; background: #f8fafc; color: #1e293b; font-size: 14px; transition: .2s ease; }
        .form-input:focus { border-color: #2563eb; background: #fff; box-shadow: 0 0 0 4px rgba(37,99,235,.1); }
        .login-button { width: 100%; height: 52px; margin-top: 5px; border: 0; border-radius: 12px; background: #2563eb; color: #fff; font-size: 15px; font-weight: 700; cursor: pointer; box-shadow: 0 8px 20px rgba(37,99,235,.2); }
        .login-button:hover { background: #1d4ed8; }
        .login-link { display: block; margin-top: 18px; color: #2563eb; font-size: 13px; font-weight: 600; text-align: center; text-decoration: none; }
        .login-link:hover { text-decoration: underline; }
        .message { margin-bottom: 18px; padding: 12px 14px; border-radius: 10px; font-size: 13px; line-height: 1.5; }
        .message-error { border: 1px solid #fecaca; background: #fef2f2; color: #b91c1c; }
        .message-footer { margin-top: 25px; color: #cbd5e1; font-size: 11px; text-align: center; }

        @media (max-width: 900px) {
            body { padding: 20px; }
            .activation-container { grid-template-columns: 1fr; max-width: 620px; }
            .school-panel { min-height: 390px; padding: 40px; }
            .welcome-title { font-size: 34px; }
            .form-panel { padding: 48px 40px; }
        }

        @media (max-width: 600px) {
            body { padding: 0; background: #fff; }
            .activation-container { min-height: 100vh; border-radius: 0; box-shadow: none; }
            .school-panel { min-height: 350px; padding: 30px 25px; }
            .school-logo-area { margin-bottom: 28px; }
            .school-logo { width: 58px; height: 58px; }
            .school-name { font-size: 14px; }
            .welcome-title { font-size: 28px; }
            .welcome-description { font-size: 14px; line-height: 1.7; }
            .school-address { font-size: 11px; }
            .form-panel { padding: 40px 25px; }
            .form-title { font-size: 25px; }
        }
    </style>
</head>

<body>
    <main class="activation-container">
        <section class="school-panel">
            <div>
                <div class="school-logo-area">
                    <img src="{{ asset('asset/img/logo.png') }}" alt="Logo SMP Negeri 2 Purwakarta" class="school-logo">
                    <div>
                        <div class="school-name">Siguru - Sistem Informasi Direktori Guru</div>
                        <div class="school-location">SMP NEGERI 2 PURWAKARTA</div>
                    </div>
                </div>
                <h1 class="welcome-title">Aktivasi Akun Guru SIGURU</h1>
                <p class="welcome-description">Gunakan NIP dan kode aktivasi pribadi dari admin untuk membuat password akun Anda.</p>
                <div class="slogan">Berilmu &bull; Berkarakter &bull; Berprestasi</div>
            </div>
            <div class="school-address">SMP Negeri 2 Purwakarta<br>Purwakarta, Jawa Barat, Indonesia</div>
        </section>

        <section class="form-panel">
            <div class="form-wrapper">
                <h2 class="form-title">Aktivasi Akun</h2>
                <p class="form-description">Masukkan NIP dan kode aktivasi 5 karakter dari admin, lalu buat password baru.</p>

                @if ($errors->any())
                <div class="message message-error" role="alert">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('guru.activation.store') }}" autocomplete="off">
                    @csrf

                    <div class="form-group">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" id="nip" name="nip" class="form-input" value="{{ old('nip') }}" autocomplete="username" inputmode="numeric" required>
                    </div>

                    <div class="form-group">
                        <label for="kode_aktivasi" class="form-label">Kode Aktivasi</label>
                        <input type="text" id="kode_aktivasi" name="kode_aktivasi" class="form-input" value="" autocomplete="one-time-code" autocapitalize="characters" minlength="5" maxlength="5" pattern="[A-Za-z0-9]{5}" title="Masukkan tepat 5 huruf atau angka" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-input" autocomplete="new-password" minlength="8" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" autocomplete="new-password" minlength="8" required>
                    </div>

                    <button type="submit" class="login-button">Aktifkan Akun</button>
                </form>

                <a href="{{ route('login') }}" class="login-link">Kembali ke halaman login</a>
                <p class="message-footer">&copy; {{ date('Y') }} SMP Negeri 2 Purwakarta</p>
            </div>
        </section>
    </main>
</body>

</html>
