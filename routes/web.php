<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::redirect('/', 'login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Volt::route('petani', 'admin.petani-manager')->name('admin.petani');
    Volt::route('periode', 'admin.periode-manager')->name('admin.periode');
    Volt::route('kriteria', 'admin.kriteria-manager')->name('admin.kriteria');
    Volt::route('bobot', 'admin.bobot-manager')->name('admin.bobot');
    Volt::route('proses-spk', 'admin.calculation-process')->name('admin.spk');
    Volt::route('perangkingan', 'admin.ranking-results')->name('admin.ranking');
    Volt::route('hasil-alokasi', 'admin.allocation-results')->name('admin.allocation');
});

require __DIR__ . '/settings.php';
