<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

    Route::controller(OrganizationController::class)->group(function () {
        Route::get('/organizations', 'index')->name('organizations.index');
        Route::post('/organizations', 'store')->name('organizations.store');
        Route::patch('/organizations/{organization}', 'update')->name('organizations.update');
        Route::delete('/organizations/{organization}', 'destroy')->name('organizations.destroy');
        Route::post('/organizations/{organization}/members', 'inviteMember')->name('organizations.members.store');
        Route::delete('/organizations/{organization}/members/{user}', 'removeMember')->name('organizations.members.destroy');
        Route::post('/organizations/switch', 'switch')->name('organizations.switch');
    });
});

require __DIR__.'/auth.php';
