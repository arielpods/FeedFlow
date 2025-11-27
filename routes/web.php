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
        
        Route::get('/organizations/{organization}/members', 'members')->name('organizations.members.index');
        Route::post('/organizations/{organization}/members', 'inviteMember')->name('organizations.members.store');
        Route::delete('/organizations/{organization}/members/{user}', 'removeMember')->name('organizations.members.destroy');
        Route::post('/organizations/switch', 'switch')->name('organizations.switch');
    });

    //Route::controller(SurveyController::class)->group(function () {
    Route::controller('App\Http\Controllers\SurveyController')->group(function () {
        Route::get('/organizations/{organization}/survey', 'survey')->name('survey.index');
        Route::get('/organizations/{organization}/survey/create', 'create')->name('survey.create');
        

        Route::POST('/organizations/{organization}/survey', 'store')->name('survey.store');


        Route::get('/surveys/{survey}/edit',  'edit')->name('surveys.edit');
        Route::patch('/surveys/{survey}', 'update')->name('surveys.update');
        Route::delete('/surveys/{survey}', 'destroy')->name('surveys.destroy');
        

    });

require __DIR__.'/auth.php';
 });