<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\HitungPenghasilan;
use Illuminate\Support\Facades\Auth;
use App\Livewire\PrintColorAnalysis;

Route::get('/test-gs', function() {
    return shell_exec('C:\PROGRA~1\gs\gs10.06.0\bin\gswin64c.exe --version');
});
Route::get('/hitung-penghasilan', HitungPenghasilan::class)
    ->middleware(['auth'])
    ->name('hitung-penghasilan');
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
Route::get('/print-color-analysis', PrintColorAnalysis::class)
    ->middleware(['auth'])
    ->name('print-color-analysis');
Route::get('/login', Login::class)->name('login')->middleware('guest');

require __DIR__.'/auth.php';