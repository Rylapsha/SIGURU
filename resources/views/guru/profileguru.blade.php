<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('asset/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('asset/css/sb-admin-2.min.css') }}" rel="stylesheet">


    <title>SIGURU - Profile</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f5f7fb;
            color: #172033;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR

        .sidebar {
            width: 245px;
            min-height: 100vh;
            background: #102b50;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            padding: 25px 15px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 5px 12px 30px;
            font-size: 19px;
            font-weight: bold;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: #f5c542;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #102b50;
            font-size: 20px;
        }

        .brand-text {
            line-height: 1.2;
        }

        .brand-text small {
            display: block;
            font-size: 11px;
            font-weight: normal;
            opacity: 0.7;
            margin-top: 3px;
        }

        .menu {
            list-style: none;
        }

        .menu li {
            margin-bottom: 7px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #dce6f4;
            text-decoration: none;
            padding: 13px 15px;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .menu a.active {
            background: #1677e8;
            color: white;
        }

        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 17px;
        }

        .logout-section {
            position: absolute;
            bottom: 25px;
            left: 15px;
            right: 15px;
        }

        .logout-section a {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #dce6f4;
            text-decoration: none;
            padding: 13px 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .logout-section a:hover {
            background: rgba(255, 255, 255, 0.08);
        } */


        /* MAIN */

        .main-content {
            margin-left: 245px;
            width: calc(100% - 245px);
            min-height: 100vh;
        }


        /* TOPBAR */

        .topbar {
            height: 68px;
            background: white;
            border-bottom: 1px solid #e8edf4;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 35px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-photo {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            background: #e8eef7;
        }

        .user-placeholder {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e8eef7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #55708f;
        }

        .user-info strong {
            display: block;
            font-size: 13px;
            color: #26344a;
        }

        .user-info span {
            font-size: 11px;
            color: #8793a5;
        }


        /* CONTENT */

        .content {
            padding: 32px 35px 40px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 7px;
            color: #172033;
        }

        .page-description {
            font-size: 13px;
            color: #8994a6;
            margin-bottom: 28px;
        }


        /* PROFILE */

        .profile-card {
            background: white;
            border: 1px solid #e8edf4;
            border-radius: 12px;
            padding: 30px;
            max-width: 900px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 25px;
            padding-bottom: 25px;
            margin-bottom: 25px;
            border-bottom: 1px solid #edf0f5;
        }

        .profile-photo {
            width: 105px;
            height: 105px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e7f0ff;
        }

        .profile-placeholder {
            width: 105px;
            height: 105px;
            border-radius: 50%;
            background: #e8f2ff;
            color: #1677e8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
        }

        .profile-header h2 {
            font-size: 22px;
            color: #26344a;
            margin-bottom: 7px;
        }

        .profile-header p {
            font-size: 13px;
            color: #8994a6;
        }


        /* INFORMATION */

        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #edf0f5;
            border-radius: 9px;
            padding: 17px;
        }

        .info-label {
            font-size: 11px;
            color: #8994a6;
            margin-bottom: 7px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #26344a;
        }


        @media (max-width: 700px) {

            .sidebar {
                width: 190px;
            }

            .main-content {
                margin-left: 190px;
                width: calc(100% - 190px);
            }

            .profile-info {
                grid-template-columns: 1fr;
            }

        }
    </style>

</head>

<body>

    <div class="wrapper">


        <!-- SIDEBAR

        <aside class="sidebar">

            <div class="brand">

                <div class="brand-icon">
                    🎓
                </div>

                <div class="brand-text">
                    Direktori Guru
                    <small>SIGURU</small>
                </div>

            </div>


            <ul class="menu">

                <li>

                    <a href="{{ route('dashboard') }}">

                        <span class="menu-icon">⌂</span>

                        Dashboard

                    </a>

                </li>


                <li>

                    <a href="{{ route('profil') }}" class="active">

                        <span class="menu-icon">♙</span>

                        Profile

                    </a>

                </li>

            </ul>


            <div class="logout-section">

                <a href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                    <span class="menu-icon">⇥</span>

                    Logout

                </a>


                <form id="logout-form"
                    action="{{ route('logout') }}"
                    method="POST"
                    style="display: none;">

                    @csrf

                </form>

            </div>

        </aside> -->

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="{{ route('dashboard') }}">

                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-book"></i>
                </div>

                <div class="sidebar-brand-text mx-3">
                    SIGURU
                </div>

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Dashboard -->
            <li class="nav-item active">

                <a class="nav-link" href="{{ route('dashboard') }}">

                    <i class="fas fa-fw fa-tachometer-alt"></i>

                    <span>Dashboard</span>

                </a>

            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Menu -->
            <div class="sidebar-heading">
                Menu Guru
            </div>

            <!-- Dokumen -->
            <li class="nav-item">

                <a class="nav-link" href="#">

                    <i class="fas fa-fw fa-folder-open"></i>

                    <span>Dokumen Saya</span>

                </a>

            </li>

            <!-- Supervisi -->
            <li class="nav-item">

                <a class="nav-link" href="#">

                    <i class="fas fa-fw fa-clipboard-check"></i>

                    <span>Supervisi</span>

                </a>

            </li>

            <!-- Agenda -->
            <li class="nav-item">

                <a class="nav-link" href="#">

                    <i class="fas fa-fw fa-calendar-alt"></i>

                    <span>Agenda</span>

                </a>

            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Logout -->
            <li class="nav-item">

                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">

                    <i class="fas fa-fw fa-sign-out-alt"></i>

                    <span>Logout</span>

                </a>

            </li>

            <!-- Sidebar Toggler -->
            <div class="text-center d-none d-md-inline">

                <button class="rounded-circle border-0" id="sidebarToggle"></button>

            </div>

        </ul>
        <!-- End of Sidebar -->


        <!-- MAIN CONTENT -->

        <main class="main-content">


            <!-- TOPBAR

            <header class="topbar">

                <div class="topbar-right">

                    <div class="user-profile">

                        @if($guru->foto)

                        <img
                            src="{{ asset('storage/' . $guru->foto) }}"
                            class="user-photo"
                            alt="Foto Profil">

                        @else

                        <div class="user-placeholder">
                            ♙
                        </div>

                        @endif


                        <div class="user-info">

                            <strong>
                                {{ $guru->nama_lengkap }}
                            </strong>

                            <span>
                                Guru
                            </span>

                        </div>

                    </div>

                </div>

            </header> -->


            <!-- CONTENT -->

            <section class="content">


                <h1 class="page-title">
                    Profile
                </h1>

                <p class="page-description">
                    Informasi profil guru yang sedang login.
                </p>


                <!-- PROFILE CARD -->

                <div class="profile-card">


                    <div class="profile-header">


                        @if($guru->foto)

                        <img
                            src="{{ asset('storage/' . $guru->foto) }}"
                            class="profile-photo"
                            alt="Foto {{ $guru->nama_lengkap }}">

                        @else

                        <div class="profile-placeholder">
                            ♙
                        </div>

                        @endif


                        <div>

                            <h2>
                                {{ $guru->nama_lengkap }}
                            </h2>

                            <p>
                                Guru
                            </p>

                        </div>


                    </div>


                    <!-- INFORMATION -->

                    <div class="profile-info">


                        <div class="info-box">

                            <div class="info-label">
                                Nama Lengkap
                            </div>

                            <div class="info-value">
                                {{ $guru->nama_lengkap }}
                            </div>

                        </div>


                        <div class="info-box">

                            <div class="info-label">
                                NIP
                            </div>

                            <div class="info-value">
                                {{ $guru->nip }}
                            </div>

                        </div>


                        <div class="info-box">

                            <div class="info-label">
                                Golongan
                            </div>

                            <div class="info-value">
                                {{ $guru->golongan }}
                            </div>

                        </div>


                        <div class="info-box">

                            <div class="info-label">
                                Mata Pelajaran
                            </div>

                            <div class="info-value">
                                {{ $guru->mata_pelajaran }}
                            </div>

                        </div>


                    </div>


                </div>


            </section>

        </main>

    </div>

</body>

</html>