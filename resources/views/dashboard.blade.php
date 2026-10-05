<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="Sistem Informasi Direktori Guru SMP Negeri 2 Purwakarta">
    <meta name="author" content="SMP Negeri 2 Purwakarta">

    <title>Dashboard - Direktori Guru</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('asset/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('asset/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

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


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop"
                        class="btn btn-link d-md-none rounded-circle mr-3">

                        <i class="fa fa-bars"></i>

                    </button>

                    <!-- Topbar Search
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">

                        <div class="input-group">

                            <input type="text"
                                class="form-control bg-light border-0 small"
                                placeholder="Cari..."
                                aria-label="Search"
                                aria-describedby="basic-addon2">

                            <div class="input-group-append">

                                <button class="btn btn-primary" type="button">

                                    <i class="fas fa-search fa-sm"></i>

                                </button>

                            </div>

                        </div>

                    </form> -->


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">


                        <!-- Divider -->
                        <div class="topbar-divider d-none d-sm-block"></div>


                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">

                            <a class="nav-link dropdown-toggle"
                                href="#"
                                id="userDropdown"
                                role="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">

                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    {{ Auth::user()->nama_lengkap ?? 'Guru' }}
                                </span>

                                <img class="img-profile rounded-circle"
                                    src="{{ asset('asset/img/undraw_profile.svg') }}">

                            </a>


                            <!-- Dropdown -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">

                                <a class="dropdown-item" href="{{ route('profil') }}">

                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>

                                    Profil Saya

                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item"
                                    href="#"
                                    data-toggle="modal"
                                    data-target="#logoutModal">

                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>

                                    Logout

                                </a>

                            </div>

                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">

                        <div>

                            <h1 class="h2 mb-1 text-gray-800">
                                Selamat Datang
                                {{ Auth::user()->nama_lengkap ?? 'Guru' }}
                            </h1>

                            <p class="mb-0 text-muted">
                                Sistem Informasi Direktori Guru SMP Negeri 2 Purwakarta
                            </p>

                        </div>

                    </div>


                    <!-- Dashboard Content -->
                    <div class="row">


                        <!-- ============================= -->
                        <!-- Kelengkapan Dokumen -->
                        <!-- ============================= -->

                        <div class="col-lg-6 mb-4 d-flex">

                            <div class="card shadow border-0 w-100">

                                <div class="card-header py-3 bg-white border-0  d-flex justify-content-between align-items-center">
                                    <div>

                                        <h6 class="m-0 mt-3 font-weight-bold text-primary">

                                            <i class="fas fa-folder-open mr-2"></i>

                                            Kelengkapan Dokumen

                                        </h6>



                                        <small class="text-muted">

                                            Status kelengkapan dokumen Anda

                                        </small>
                                    </div>

                                    <!-- Button -->
                                    <a href="#" class="btn btn-sm btn-light text-primary">

                                        <i class="fas fa-angle-right mr-1"></i>

                                        Lihat Selengkapnya

                                    </a>


                                </div>


                                <div class="card-body">


                                    <!-- Kepegawaian -->
                                    <div class="mb-4">

                                        <div class="d-flex justify-content-between mb-1">

                                            <span class="font-weight-bold text-gray-800">
                                                Kepegawaian
                                            </span>

                                            <span class="text-muted">
                                                4/4
                                            </span>

                                        </div>


                                        <div class="progress" style="height: 10px;">

                                            <div class="progress-bar bg-success"
                                                role="progressbar"
                                                style="width: 100%;">

                                            </div>

                                        </div>


                                        <small class="text-success">

                                            <i class="fas fa-check-circle mr-1"></i>

                                            Lengkap

                                        </small>

                                    </div>


                                    <!-- Pendidikan -->
                                    <div class="mb-4">

                                        <div class="d-flex justify-content-between mb-1">

                                            <span class="font-weight-bold text-gray-800">
                                                Pendidikan
                                            </span>

                                            <span class="text-muted">
                                                3/4
                                            </span>

                                        </div>


                                        <div class="progress" style="height: 10px;">

                                            <div class="progress-bar bg-warning"
                                                role="progressbar"
                                                style="width: 75%;">

                                            </div>

                                        </div>


                                        <small class="text-warning">

                                            <i class="fas fa-exclamation-circle mr-1"></i>

                                            1 dokumen belum tersedia

                                        </small>

                                    </div>


                                    <!-- Pembelajaran -->
                                    <div class="mb-4">

                                        <div class="d-flex justify-content-between mb-1">

                                            <span class="font-weight-bold text-gray-800">
                                                Pembelajaran
                                            </span>

                                            <span class="text-muted">
                                                4/6
                                            </span>

                                        </div>


                                        <div class="progress" style="height: 10px;">

                                            <div class="progress-bar bg-warning"
                                                role="progressbar"
                                                style="width: 67%;">

                                            </div>

                                        </div>


                                        <small class="text-warning">

                                            <i class="fas fa-exclamation-circle mr-1"></i>

                                            2 dokumen belum tersedia

                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <!-- ============================= -->
                        <!-- Status Supervisi -->
                        <!-- ============================= -->

                        <div class="col-lg-6 mb-4 d-flex ">

                            <div class="card shadow border-0 w-100">

                                <div class="card-header py-3 bg-white border-0 d-flex justify-content-between align-items-center">
                                    <div>

                                        <h6 class="m-0 mt-3 font-weight-bold text-success">

                                            <i class="fas fa-clipboard-check mr-2"></i>

                                            Status Supervisi

                                        </h6>

                                        <small class="text-muted">

                                            Informasi pelaksanaan supervisi

                                        </small>
                                    </div>

                                    <!-- Button -->
                                    <a href="#" class="btn btn-sm btn-light text-primary">

                                        <i class="fas fa-angle-right mr-1"></i>

                                        Lihat selengkapnya

                                    </a>

                                </div>


                                <div class="card-body mt-4">


                                    <!-- Status -->
                                    <div class="d-flex align-items-center mb-4">

                                        <div class="mr-3">

                                            <div class="icon-circle bg-success">

                                                <i class="fas fa-check text-white"></i>

                                            </div>

                                        </div>


                                        <div>

                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                                                Status

                                            </div>

                                            <div class="h5 mb-0 font-weight-bold text-gray-800">

                                                Sudah Dilaksanakan

                                            </div>

                                        </div>

                                    </div>


                                    <!-- Detail -->
                                    <div class="row">


                                        <!-- Supervisi Terakhir -->
                                        <div class="col-md-6 mb-4">

                                            <div class="text-xs text-muted text-uppercase mb-1">

                                                Supervisi Terakhir

                                            </div>

                                            <div class="font-weight-bold text-gray-800">

                                                12 September 2026

                                            </div>

                                        </div>


                                        <!-- Pengawas -->
                                        <div class="col-md-12 mb-4">

                                            <div class="text-xs text-muted text-uppercase mb-1">

                                                Pengawas

                                            </div>

                                            <div class="font-weight-bold text-gray-800">

                                                Nama Pengawas

                                            </div>

                                        </div>

                                    </div>


                                    <!-- Informasi
                                    <div class="alert alert-light border-left-success mb-4">

                                        <small class="text-muted">

                                            <i class="fas fa-info-circle mr-1 text-success"></i>

                                            Informasi hasil penilaian supervisi tidak ditampilkan
                                            pada akun guru.

                                        </small>

                                    </div> -->


                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- End Row -->


                    <!-- ============================= -->
                    <!-- Agenda Mendatang -->
                    <!-- ============================= -->

                    <div class="row">

                        <div class="col-lg-12 mb-4">

                            <div class="card shadow border-0">


                                <!-- Header -->
                                <div class="card-header py-3 bg-white border-0 d-flex justify-content-between align-items-center">

                                    <div>

                                        <h6 class="m-0 font-weight-bold text-primary">

                                            <i class="fas fa-calendar-alt mr-2"></i>

                                            Agenda Mendatang

                                        </h6>

                                        <small class="text-muted">

                                            Kegiatan yang perlu diperhatikan

                                        </small>

                                    </div>


                                    <a href="#" class="btn btn-sm btn-light text-primary">

                                        <i class="	fas fa-angle-right mr-1"></i>

                                        Lihat selengkapnya

                                    </a>

                                </div>


                                <!-- Body -->
                                <div class="card-body">


                                    <!-- Agenda 1 -->
                                    <div class="d-flex align-items-center mb-4">

                                        <div class="mr-3 text-center"
                                            style="min-width: 60px;">

                                            <div class="font-weight-bold text-primary"
                                                style="font-size: 20px;">

                                                08

                                            </div>

                                            <div class="text-xs text-muted">

                                                OKT

                                            </div>

                                        </div>


                                        <div>

                                            <div class="font-weight-bold text-gray-800">

                                                Supervisi Guru

                                            </div>

                                            <small class="text-muted">

                                                <i class="far fa-clock mr-1"></i>

                                                08:00 WIB

                                            </small>

                                        </div>

                                    </div>


                                    <hr>


                                    <!-- Agenda 2 -->
                                    <div class="d-flex align-items-center mb-4">

                                        <div class="mr-3 text-center"
                                            style="min-width: 60px;">

                                            <div class="font-weight-bold text-primary"
                                                style="font-size: 20px;">

                                                12

                                            </div>

                                            <div class="text-xs text-muted">

                                                OKT

                                            </div>

                                        </div>


                                        <div>

                                            <div class="font-weight-bold text-gray-800">

                                                Pengumpulan Dokumen

                                            </div>

                                            <small class="text-muted">

                                                <i class="far fa-clock mr-1"></i>

                                                23:59 WIB

                                            </small>

                                        </div>

                                    </div>


                                    <hr>


                                    <!-- Agenda 3 -->
                                    <div class="d-flex align-items-center mb-4">

                                        <div class="mr-3 text-center"
                                            style="min-width: 60px;">

                                            <div class="font-weight-bold text-primary"
                                                style="font-size: 20px;">

                                                16

                                            </div>

                                            <div class="text-xs text-muted">

                                                OKT

                                            </div>

                                        </div>


                                        <div>

                                            <div class="font-weight-bold text-gray-800">

                                                Rapat Guru

                                            </div>

                                            <small class="text-muted">

                                                <i class="fas fa-map-marker-alt mr-1"></i>

                                                Ruang Guru

                                            </small>

                                        </div>

                                    </div>


                                    <hr>


                                    <!-- Agenda 4 -->
                                    <div class="d-flex align-items-center">

                                        <div class="mr-3 text-center"
                                            style="min-width: 60px;">

                                            <div class="font-weight-bold text-primary"
                                                style="font-size: 20px;">

                                                21

                                            </div>

                                            <div class="text-xs text-muted">

                                                OKT

                                            </div>

                                        </div>


                                        <div>

                                            <div class="font-weight-bold text-gray-800">

                                                Evaluasi Pembelajaran

                                            </div>

                                            <small class="text-muted">

                                                <i class="far fa-clock mr-1"></i>

                                                09:00 WIB

                                            </small>

                                        </div>

                                    </div>


                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- End Agenda Row -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


            <!-- Footer -->
            <footer class="sticky-footer bg-white">

                <div class="container my-auto">

                    <div class="copyright text-center my-auto">

                        <span>
                            Copyright &copy; SIGURU SMP Negeri 2 Purwakarta 2026
                        </span>

                    </div>

                </div>

            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">

        <i class="fas fa-angle-up"></i>

    </a>


    <!-- Logout Modal -->
    <div class="modal fade"
        id="logoutModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">

        <div class="modal-dialog"
            role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Logout
                    </h5>

                    <button class="close"
                        type="button"
                        data-dismiss="modal"
                        aria-label="Close">

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>


                <div class="modal-body">

                    Apakah kamu yakin ingin keluar dari sistem?

                </div>


                <div class="modal-footer">

                    <button class="btn btn-secondary"
                        type="button"
                        data-dismiss="modal">

                        Batal

                    </button>


                    <form action="{{ route('logout') }}"
                        method="POST"
                        class="d-inline">

                        @csrf

                        <button type="submit"
                            class="btn btn-primary">

                            Logout

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('asset/vendor/jquery/jquery.min.js') }}"></script>

    <script src="{{ asset('asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('asset/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('asset/js/sb-admin-2.min.js') }}"></script>

</body>

</html>