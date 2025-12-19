<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Siswa\SiswaDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Rute khusus Guru
    Route::prefix('guru')->name('guru.')->group(function () {
        // Halaman Siswa Bimbingan
        Route::get('/siswa', [GuruDashboardController::class, 'indexSiswa'])->name('siswa.index');
        
        // Halaman Penilaian
        Route::get('/penilaian', [GuruDashboardController::class, 'indexPenilaian'])->name('penilaian.index');
        Route::get('/penilaian/{siswa}/input', [GuruDashboardController::class, 'createPenilaian'])->name('penilaian.create');
        Route::post('/penilaian/{siswa}', [GuruDashboardController::class, 'storePenilaian'])->name('penilaian.store');
        
        // Jurnal (tetap ada sesuai permintaan sebelumnya)
        Route::get('/jurnal', [GuruDashboardController::class, 'indexJurnal'])->name('jurnal.index');
    });
});
Route::middleware(['auth'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/jurnal', [SiswaDashboardController::class, 'indexJurnal'])->name('jurnal.index');
    Route::get('/jurnal/create', [SiswaDashboardController::class, 'createJurnal'])->name('jurnal.create');
    Route::post('/jurnal', [SiswaDashboardController::class, 'storeJurnal'])->name('jurnal.store');
});

require __DIR__.'/auth.php';
