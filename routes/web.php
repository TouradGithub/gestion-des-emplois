<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\TeacherController;
use App\Http\Controllers\SalleDeClasseController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\NiveauPedagogiqueController;
use App\Http\Controllers\TrimesterController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EmploiTempsController;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::prefix('admin')->name('web.')->middleware(['auth'])->group(function () {
    Route::resource('teachers', TeacherController::class);
    Route::get('teachers-list', [TeacherController::class, 'show'])->name('teachers.list');
    Route::resource('niveauformations', \App\Http\Controllers\NiveauformationController::class);
    Route::resource('anneescolaires', \App\Http\Controllers\AnneescolaireController::class);
    Route::get('niveauformations/list', [\App\Http\Controllers\NiveauformationController::class,'index'])->name('niveauformations.list');
    Route::get('/anneescolaires/list', [\App\Http\Controllers\AnneescolaireController::class, 'list'])->name('anneescolaires.list');


    Route::resource('salle-de-classes', SalleDeClasseController::class);
    Route::get('salle-de-classes-list', [SalleDeClasseController::class, 'list'])->name('salle-de-classes.list');


    Route::get( 'departements', [DepartementController::class, 'index'])->name('departements.index');
    Route::get('departements/list', [DepartementController::class, 'list'])->name('departements.list');
    Route::get('departements/create', [DepartementController::class, 'create'])->name('departements.create');
    Route::delete('departements/destroy/{id}', [DepartementController::class, 'destroy'])->name('departements.destroy');
    Route::post('departements/create', [DepartementController::class, 'store'])->name('departements.store');


    Route::get('specialities', [SpecialityController::class, 'index'])->name('specialities.index');
    Route::get('specialities/list', [SpecialityController::class, 'list'])->name('specialities.list'); // JSON for bootstrap-table
    Route::get('specialities/create', [SpecialityController::class, 'create'])->name('specialities.create');
    Route::post('specialities/store', [SpecialityController::class, 'store'])->name('specialities.store');
    Route::delete('specialities/{speciality}', [SpecialityController::class, 'destroy'])->name('specialities.destroy');




    Route::resource('niveau-pedagogiques', NiveauPedagogiqueController::class)->names([
        'index' => 'niveau-pedagogiques.index',
        'create' => 'niveau-pedagogiques.create',
        'store' => 'niveau-pedagogiques.store',
        'edit' => 'niveau-pedagogiques.edit',
        'update' => 'niveau-pedagogiques.update',
        'destroy' => 'niveau-pedagogiques.destroy',
    ]);


    Route::prefix('trimesters')->name('trimesters.')->group(function () {
        Route::get('/', [TrimesterController::class, 'index'])->name('index');
        Route::get('/create', [TrimesterController::class, 'create'])->name('create');
        Route::post('/', [TrimesterController::class, 'store'])->name('store');
        Route::get('/{trimester}/edit', [TrimesterController::class, 'edit'])->name('edit');
        Route::put('/{trimester}', [TrimesterController::class, 'update'])->name('update');
        Route::delete('/{trimester}', [TrimesterController::class, 'destroy'])->name('destroy');
    });


    Route::resource('classes', ClasseController::class);
    Route::get('classes/delete/{id}', [ClasseController::class,'destroy'])->name('classes.delete');
    // الراوتات المخصصة أولاً قبل resource routes
    Route::get('/emplois/get-teachers', [EmploiTempsController::class, 'getTeachers'])->name('emplois.getTeachers');
    Route::get('/emplois/get-subjects', [EmploiTempsController::class, 'getSubjects'])->name('emplois.getSubjects');
    Route::get('/emplois/get-teachers-by-department', [EmploiTempsController::class, 'getTeachersByDepartment'])->name('emplois.getTeachersByDepartment');
    Route::post('/emplois/get-trimesters', [EmploiTempsController::class, 'getTrimesters'])->name('emplois.getTrimesters');
    Route::get('emplois/list', [EmploiTempsController::class, 'show'])->name('emplois.list');
    Route::get('emplois/showEmploi/{classId}', [EmploiTempsController::class, 'showEmploi'])->name('emplois.showEmploi');

    // resource route أخيراً
    Route::resource('emplois', EmploiTempsController::class);

    Route::get('classes/list', [ClasseController::class, 'list'])->name('classes.list');

    Route::resource('subjects', \App\Http\Controllers\SubjectController::class);
    Route::get('subjects_teachers/list', [\App\Http\Controllers\SubjectTeacherController::class,'listSubjectTeacher'])->name('subjects_teachers.list');
    Route::resource('subjects_teachers', \App\Http\Controllers\SubjectTeacherController::class);

});
