<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;

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




    Route::controller('App\Http\Controllers\SurveyController')->group(function () {

        // ... Vos autres routes ...

        // ROUTES LIÉES À L'ORGANISATION (Création et Liste)
        Route::prefix('organizations/{organization}')->group(function () {

            // Liste des sondages
            Route::get('/survey', 'survey')->name('survey.index');

            // Formulaire de création (C'est cette ligne qui vous manque ou qui est mal nommée)
            Route::get('/survey/create',  'create')->name('surveys.create');

            // Action de sauvegarde
            Route::post('/survey',  'store')->name('surveys.store');
        });

        // ROUTES LIÉES AU SONDAGE LUI-MÊME (Modification, Suppression, Questions)
        // Pas besoin du préfixe organization ici car on a l'ID du sondage

        // Modification / Suppression du sondage
        Route::get('/surveys/{survey}/edit',  'edit')->name('surveys.edit');
        Route::patch('/surveys/{survey}',  'update')->name('surveys.update');
        Route::delete('/surveys/{survey}',  'destroy')->name('surveys.destroy');

        // Gestion des Questions (Story 3)
        Route::get('/surveys/{survey}/questions', 'manageQuestions')->name('surveys.questions.index');
        Route::post('/surveys/{survey}/questions',  'storeQuestion')->name('surveys.questions.store');
        Route::delete('/questions/{question}', 'destroyQuestion')->name('surveys.questions.destroy');

        Route::get('/surveys/{token}', 'show')->name('surveys.show');

    });
});

require __DIR__.'/auth.php';
