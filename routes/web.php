<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\SupervisiController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminSupervisiController;
use App\Http\Controllers\AdminGuruController;
use App\Http\Controllers\AdminKomentarDokumenController;
use App\Http\Controllers\AdminMataPelajaranController;
use App\Http\Controllers\AdminAgendaKegiatanController;

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

// Logout Guru dan Admin
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// Halaman awal aplikasi
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard Guru
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profil Guru
    Route::get('/profil', [GuruProfileController::class, 'index'])
        ->name('profil');

    Route::put('/profil/foto', [GuruProfileController::class, 'updateFoto'])
        ->name('profil.foto.update');

    // Dokumen Guru
    Route::get('/dokumen', [DokumenController::class, 'index'])
        ->name('dokumen.index');

    Route::get('/dokumen/kategori/{jenis}', [DokumenController::class, 'kategori'])
        ->where(
            'jenis',
            'modul-ajar|atp-ap|prota-prosem|prota|prosem|kisi-kisi|naskah-soal|analisis-butir-soal'
        )
        ->name('dokumen.kategori');

    Route::get('/dokumen/modul-ajar', function () {
        return redirect()->route('dokumen.kategori', [
            'jenis' => 'modul-ajar',
        ]);
    })->name('dokumen.modul-ajar');

    Route::get('/dokumen/atp', function () {
        return redirect()->route('dokumen.kategori', [
            'jenis' => 'atp-ap',
        ]);
    });

    Route::get('/dokumen/ap', function () {
        return redirect()->route('dokumen.kategori', [
            'jenis' => 'atp-ap',
        ]);
    });

    Route::post('/dokumen/{jenis}', [DokumenController::class, 'store'])
        ->name('dokumen.store');

    Route::get('/dokumen/{dokumen}/lihat', [DokumenController::class, 'lihat'])
        ->name('dokumen.lihat');

    Route::delete('/dokumen/{dokumen}', [DokumenController::class, 'destroy'])
        ->name('dokumen.destroy');

    Route::get('/dokumen/{dokumen}/unduh', [DokumenController::class, 'unduh'])
        ->name('dokumen.unduh');

    // Supervisi Guru
    Route::get('/supervisi', [SupervisiController::class, 'index'])
        ->name('guru.supervisi');

    Route::get('/supervisi/{id}', [SupervisiController::class, 'show'])
        ->whereNumber('id')
        ->name('guru.supervisi.detail');

    Route::get('/supervisi/{id}/surat', [SupervisiController::class, 'lihatSurat'])
        ->whereNumber('id')
        ->name('guru.supervisi.surat');

    Route::get('/supervisi/{id}/feedback', [SupervisiController::class, 'feedback'])
        ->whereNumber('id')
        ->name('guru.supervisi.feedback');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Data Guru
        Route::get('/guru', [AdminGuruController::class, 'index'])
            ->name('guru.index');

        Route::post('/guru', [AdminGuruController::class, 'store'])
            ->name('guru.store');

        Route::put('/guru/{guru}', [AdminGuruController::class, 'update'])
            ->name('guru.update');

        Route::delete('/guru/{guru}', [AdminGuruController::class, 'destroy'])
            ->name('guru.destroy');

        Route::get('/guru/{id}', [AdminGuruController::class, 'show'])
            ->whereNumber('id')
            ->name('guru.detail');

        // Melihat PDF dokumen guru
        Route::get('/guru/dokumen/{id}/lihat', [
            AdminGuruController::class,
            'lihatDokumen',
        ])
            ->whereNumber('id')
            ->name('guru.dokumen.lihat');

        // Menyimpan komentar admin pada dokumen guru
        Route::post('/guru/dokumen/{dokumenId}/komentar', [
            AdminKomentarDokumenController::class,
            'store',
        ])
            ->whereNumber('dokumenId')
            ->name('guru.dokumen.komentar.store');

        // Halaman dan pengelolaan supervisi admin
        Route::get('/supervisi', [AdminSupervisiController::class, 'index'])
            ->name('supervisi.index');

        Route::get('/supervisi/create', [AdminSupervisiController::class, 'create'])
            ->name('supervisi.create');

        Route::post('/supervisi', [AdminSupervisiController::class, 'store'])
            ->name('supervisi.store');

        Route::patch('/supervisi/{id}/status', [
            AdminSupervisiController::class,
            'updateStatus',
        ])
            ->whereNumber('id')
            ->name('supervisi.status');

        Route::patch('/supervisi/{id}/komentar', [
            AdminSupervisiController::class,
            'updateKomentar',
        ])
            ->whereNumber('id')
            ->name('supervisi.komentar');

        Route::patch('/supervisi/{id}/hasil', [
            AdminSupervisiController::class,
            'updateHasil',
        ])
            ->whereNumber('id')
            ->name('supervisi.hasil');

        Route::patch('/supervisi/{id}/penilaian', [
            AdminSupervisiController::class,
            'updatePenilaian',
        ])
            ->whereNumber('id')
            ->name('supervisi.penilaian');

        Route::get('/supervisi/{id}/feedback', [
            AdminSupervisiController::class,
            'feedback',
        ])
            ->whereNumber('id')
            ->name('supervisi.feedback');

        Route::delete(
            '/guru/dokumen/komentar/{id}',
            [AdminKomentarDokumenController::class, 'destroy']
        )->whereNumber('id')->name('guru.dokumen.komentar.destroy');

        // Mata Pelajaran
        Route::get('/mata-pelajaran', [AdminMataPelajaranController::class, 'index'])
            ->name('mata-pelajaran.index');

        Route::post('/mata-pelajaran', [AdminMataPelajaranController::class, 'store'])
            ->name('mata-pelajaran.store');

        Route::put('/mata-pelajaran/{mataPelajaran}', [AdminMataPelajaranController::class, 'update'])
            ->name('mata-pelajaran.update');

        Route::delete('/mata-pelajaran/{mataPelajaran}', [AdminMataPelajaranController::class, 'destroy'])
            ->name('mata-pelajaran.destroy');

        // Agenda kegiatan admin
        Route::get('/agenda-kegiatan', [AdminAgendaKegiatanController::class, 'index'])
            ->name('agenda.index');

        Route::post('/agenda-kegiatan', [AdminAgendaKegiatanController::class, 'store'])
            ->name('agenda.store');

        Route::put('/agenda-kegiatan/{agendaKegiatan}', [AdminAgendaKegiatanController::class, 'update'])
            ->name('agenda.update');

        Route::delete('/agenda-kegiatan/{agendaKegiatan}', [AdminAgendaKegiatanController::class, 'destroy'])
            ->name('agenda.destroy');

        // Hapus jadwal supervisi
        Route::delete('/supervisi/{id}', [
            AdminSupervisiController::class,
            'destroy',
        ])
            ->whereNumber('id')
            ->name('supervisi.destroy');

        Route::get('/supervisi/{id}/detail', [
            AdminSupervisiController::class,
            'show',
        ])
            ->whereNumber('id')
            ->name('supervisi.detail');
    });
