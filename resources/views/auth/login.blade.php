<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Direktori Guru SMP Negeri 2 Purwakarta</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            background: #eef6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            color: #1e293b;
        }

        .login-container {
            width: 100%;
            max-width: 1180px;
            min-height: 680px;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(30, 64, 175, 0.15);
        }

        /* =========================
           PANEL KIRI
        ========================= */

        .school-panel {
            position: relative;
            min-height: 680px;
            background-image: linear-gradient(135deg,
                rgba(8, 47, 107, 0.88),
                rgba(30, 100, 190, 0.70)),
            url('{{ asset(' images/sekolah.jpg') }}');
            background-size: cover;
            background-position: center;
            color: #ffffff;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .school-content {
            position: relative;
            z-index: 2;
        }

        .school-logo-area {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 45px;
        }

        .school-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            background: #ffffff;
            border-radius: 16px;
            padding: 6px;
        }

        .school-name {
            font-size: 17px;
            font-weight: 700;
            line-height: 1.4;
        }

        .school-location {
            font-size: 13px;
            opacity: 0.85;
            margin-top: 4px;
        }

        .welcome-title {
            font-size: 40px;
            line-height: 1.2;
            font-weight: 700;
            margin-bottom: 20px;
            max-width: 520px;
        }

        .welcome-description {
            font-size: 16px;
            line-height: 1.8;
            max-width: 500px;
            color: rgba(255, 255, 255, 0.92);
        }

        .slogan {
            display: inline-block;
            margin-top: 28px;
            padding: 10px 18px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 30px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.10);
        }

        .school-address {
            position: relative;
            z-index: 2;
            font-size: 13px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.85);
            max-width: 450px;
        }

        /* =========================
           PANEL KANAN
        ========================= */

        .form-panel {
            padding: 60px 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
        }

        .form-wrapper {
            width: 100%;
            max-width: 410px;
        }

        .form-logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
            margin-bottom: 24px;
        }

        .form-title {
            font-size: 30px;
            font-weight: 700;
            color: #0f3b73;
            margin-bottom: 10px;
        }

        .form-description {
            font-size: 14px;
            line-height: 1.7;
            color: #64748b;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            height: 52px;
            padding: 0 16px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            color: #1e293b;
            font-size: 14px;
            outline: none;
            transition: 0.2s ease;
        }

        .form-input:focus {
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
        }

        .password-input {
            padding-right: 52px;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            color: #64748b;
            font-size: 13px;
            padding: 5px;
        }

        .toggle-password:hover {
            color: #2563eb;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin: 5px 0 25px;
            font-size: 13px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            cursor: pointer;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        .forgot-password {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.20);
        }

        .login-button:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 27px 0;
            color: #94a3b8;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .register-button {
            width: 100%;
            height: 50px;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .register-button:hover {
            background: #dbeafe;
        }

        .register-button:disabled {
            cursor: not-allowed;
            opacity: 0.65;
        }

        .registered-note {
            text-align: center;
            font-size: 12px;
            line-height: 1.6;
            color: #94a3b8;
            margin-top: 20px;
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            font-size: 11px;
            color: #cbd5e1;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {
            body {
                padding: 20px;
            }

            .login-container {
                grid-template-columns: 1fr;
                max-width: 620px;
            }

            .school-panel {
                min-height: 450px;
                padding: 40px;
            }

            .welcome-title {
                font-size: 34px;
            }

            .form-panel {
                padding: 50px 40px;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 0;
                background: #ffffff;
            }

            .login-container {
                min-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }

            .school-panel {
                min-height: 400px;
                padding: 30px 25px;
            }

            .school-logo-area {
                margin-bottom: 30px;
            }

            .school-logo {
                width: 58px;
                height: 58px;
            }

            .school-name {
                font-size: 14px;
            }

            .welcome-title {
                font-size: 28px;
            }

            .welcome-description {
                font-size: 14px;
                line-height: 1.7;
            }

            .slogan {
                font-size: 12px;
            }

            .school-address {
                font-size: 11px;
            }

            .form-panel {
                padding: 40px 25px;
            }

            .form-title {
                font-size: 25px;
            }

            .form-options {
                align-items: flex-start;
                flex-direction: column;
            }

            .forgot-password {
                margin-left: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        <!-- =========================
             PANEL KIRI
        ========================== -->
        <section class="school-panel">

            <div class="school-content">

                <div class="school-logo-area">

                    <img
                        src="{{ asset('asset/img/logo.png') }}"
                        alt="Logo SMP Negeri 2 Purwakarta"
                        class="school-logo">

                    <div>
                        <div class="school-name">
                            Siguru - Sistem Informasi Direktori Guru
                        </div>

                        <div class="school-location">
                            SMP NEGERI 2 PURWAKARTA
                        </div>
                    </div>

                </div>

                <h1 class="welcome-title">
                    Selamat Datang di Sistem Informasi Direktori Guru (Siguru)
                </h1>

                <div class="slogan">
                    Berilmu • Berkarakter • Berprestasi
                </div>

            </div>

            <div class="school-address">
                SMP Negeri 2 Purwakarta<br>
                Purwakarta, Jawa Barat, Indonesia
            </div>

        </section>


        <!-- =========================
             PANEL KANAN
        ========================== -->
        <section class="form-panel">

            <div class="form-wrapper">


                <h2 class="form-title">
                    Masuk ke Akun Anda
                </h2>

                <p class="form-description">
                    Silakan masuk menggunakan akun yang telah
                    terdaftar untuk mengakses Direktori Guru.
                </p>

                @if ($errors->any())
                <div style="
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #3b0000;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 20px;
        font-size: 13px;">
                    {{ $errors->first() }}
                </div>
                @endif

                <!--
                    Tahap ini hanya tampilan frontend.
                    Backend login belum dibuat.
                -->
                <form method="POST" action="{{ route('login.process') }}">

                    @csrf

                    <div class="form-group">

                        <label for="username" class="form-label">
                            Username
                        </label>

                        <input
                            type="text"
                            id="nip"
                            name="nip"
                            class="form-input"
                            placeholder="Masukkan username"
                            value="{{ old('nip') }}"
                            autocomplete="username"
                            required>

                    </div>


                    <div class="form-group">

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="input-wrapper">

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input password-input"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required>

                            <button
                                type="button"
                                class="toggle-password"
                                id="togglePassword"
                                aria-label="Tampilkan password">
                                Tampilkan
                            </button>

                        </div>

                    </div>


                    <div class="form-options">

                        <label class="remember">

                            <input
                                type="checkbox"
                                name="remember">

                            <span>Ingat Saya</span>

                        </label>

                    </div>


                    <button
                        type="submit"
                        class="login-button">
                        Masuk
                    </button>

                </form>

                <p class="footer-text">
                    © {{ date('Y') }} SMP Negeri 2 Purwakarta
                </p>

            </div>

        </section>

    </div>


    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function() {

            if (passwordInput.type === 'password') {

                passwordInput.type = 'text';
                togglePassword.textContent = 'Sembunyikan';
                togglePassword.setAttribute(
                    'aria-label',
                    'Sembunyikan password'
                );

            } else {

                passwordInput.type = 'password';
                togglePassword.textContent = 'Tampilkan';
                togglePassword.setAttribute(
                    'aria-label',
                    'Tampilkan password'
                );

            }

        });
    </script>

</body>

</html>