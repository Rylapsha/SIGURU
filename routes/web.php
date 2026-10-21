
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GuruProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\SupervisiController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminSupervisiController;

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

// Dashboard Guru
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profil', [GuruProfileController::class, 'index'])
        ->name('profil');

    Route::put('/profil/foto', [GuruProfileController::class, 'updateFoto'])
        ->name('profil.foto.update');

    Route::get('/dokumen', [DokumenController::class, 'index'])
        ->name('dokumen.index');

    Route::get('/dokumen/kategori/{jenis}', [DokumenController::class, 'kategori'])
        ->where('jenis', 'modul-ajar|atp-ap|prota-prosem|prota|prosem|kisi-kisi|naskah-soal|analisis-butir-soal')
        ->name('dokumen.kategori');

    Route::get('/dokumen/modul-ajar', function () {
        return redirect()->route('dokumen.kategori', ['jenis' => 'modul-ajar']);
    })->name('dokumen.modul-ajar');

    Route::get('/dokumen/atp', function () {
        return redirect()->route('dokumen.kategori', ['jenis' => 'atp-ap']);
    });

    Route::get('/dokumen/ap', function () {
        return redirect()->route('dokumen.kategori', ['jenis' => 'atp-ap']);
    });

    Route::post('/dokumen/{jenis}', [DokumenController::class, 'store'])
        ->name('dokumen.store');

    Route::get('/dokumen/{dokumen}/lihat', [DokumenController::class, 'lihat'])
        ->name('dokumen.lihat');

    Route::delete('/dokumen/{dokumen}', [DokumenController::class, 'destroy'])
        ->name('dokumen.destroy');

    Route::get('/dokumen/{dokumen}/unduh', [DokumenController::class, 'unduh'])
        ->name('dokumen.unduh');

    Route::get('/supervisi', [SupervisiController::class, 'index'])
        ->name('guru.supervisi');

    Route::get('/supervisi/{id}', [SupervisiController::class, 'show'])
        ->whereNumber('id')
        ->name('guru.supervisi.detail');
});


Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/guru', function () {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        return view('admin.guru.index');
    })->name('guru.index');

    // Halaman dan pengelolaan supervisi admin
    Route::get('/supervisi', [AdminSupervisiController::class, 'index'])
        ->name('supervisi.index');

    Route::post('/supervisi', [AdminSupervisiController::class, 'store'])
        ->name('supervisi.store');

    Route::patch('/supervisi/{id}/status', [AdminSupervisiController::class, 'updateStatus'])
        ->whereNumber('id')
        ->name('supervisi.status');

    Route::patch('/supervisi/{id}/komentar', [AdminSupervisiController::class, 'updateKomentar'])
        ->whereNumber('id')
        ->name('supervisi.komentar');

    Route::patch('/supervisi/{id}/hasil', [AdminSupervisiController::class, 'updateHasil'])
        ->whereNumber('id')
        ->name('supervisi.hasil');
});
