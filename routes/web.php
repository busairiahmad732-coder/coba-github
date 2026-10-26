<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HalamanController;

Route::get('/', [HalamanController::class,'latihanView'])
->name('latihanView');
Route::get('/profil', [HalamanController::class,'profilView'])
->name('profilView');
Route::get('/daftar', [HalamanController::class, 'daftarSiswa'])
->name('daftarSiswa');

//API
Route::get('/api/siswa', [HalamanController::class, 'apiDaftarSiswa'])
->name('api.siswa.index');
Route::post('/api/siswa', [HalamanController::class, 'tambahApiSiswa'])
->name('api.siswa.store');
Route::delete('/api/siswa/{id}', [HalamanController::class, 'hapusApiSiswa'])
->name('api.siswa.destroy');
Route::put('/api/siswa/{id}', [HalamanController::class, 'updateApiSiswa'])
->name('api.siswa.update');