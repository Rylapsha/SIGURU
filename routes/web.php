<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GuruProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\SupervisiController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::get('/', function () {
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
