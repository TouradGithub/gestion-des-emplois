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
use App\Http\Controllers\PointageController;


Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// راوتس المعلمين
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'user.type:teacher'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/departments', [App\Http\Controllers\TeacherDashboardController::class, 'departments'])->name('departments');
    Route::get('/schedule/{subjectTeacher}', [App\Http\Controllers\TeacherDashboardController::class, 'showSchedule'])->name('schedule');
    Route::get('/pointages', [App\Http\Controllers\TeacherDashboardController::class, 'showPointages'])->name('pointages');
    Route::get('/profile', [App\Http\Controllers\TeacherDashboardController::class, 'profile'])->name('profile');
    Route::get('/emploi-temps', [App\Http\Controllers\TeacherDashboardController::class, 'emploiTemps'])->name('emploi-temps');

    // Demandes d'ajout de séances
    Route::get('/requests', [App\Http\Controllers\TeacherRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [App\Http\Controllers\TeacherRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [App\Http\Controllers\TeacherRequestController::class, 'store'])->name('requests.store');
    Route::delete('/requests/{teacherRequest}', [App\Http\Controllers\TeacherRequestController::class, 'destroy'])->name('requests.destroy');
    Route::get('/requests/get-subjects', [App\Http\Controllers\TeacherRequestController::class, 'getSubjectsByClass'])->name('requests.get-subjects');
    Route::get('/requests/get-trimesters', [App\Http\Controllers\TeacherRequestController::class, 'getTrimestersByClass'])->name('requests.get-trimesters');
    Route::get('/requests/get-horaires', [App\Http\Controllers\TeacherRequestController::class, 'getAvailableHoraires'])->name('requests.get-horaires');
    Route::get('/requests/get-salles', [App\Http\Controllers\TeacherRequestController::class, 'getAvailableSalles'])->name('requests.get-salles');

    // Attestations (Demandes de certificats)
    Route::get('/attestations', [App\Http\Controllers\TeacherAttestationController::class, 'index'])->name('attestations.index');
    Route::get('/attestations/create', [App\Http\Controllers\TeacherAttestationController::class, 'create'])->name('attestations.create');
    Route::post('/attestations', [App\Http\Controllers\TeacherAttestationController::class, 'store'])->name('attestations.store');
    Route::get('/attestations/{attestation}/download', [App\Http\Controllers\TeacherAttestationController::class, 'download'])->name('attestations.download');
    Route::delete('/attestations/{attestation}', [App\Http\Controllers\TeacherAttestationController::class, 'destroy'])->name('attestations.destroy');
});


Route::prefix('admin')->name('web.')->middleware(['auth', 'user.type:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [App\Http\Controllers\AdminDashboardController::class, 'getStats'])->name('dashboard.stats');

    Route::resource('teachers', TeacherController::class);
    Route::get('teachers-list', [TeacherController::class, 'show'])->name('teachers.list');
    Route::resource('niveauformations', \App\Http\Controllers\NiveauformationController::class);
    Route::get('niveauformations/list', [\App\Http\Controllers\NiveauformationController::class,'index'])->name('niveauformations.list');

    // انتبه: يجب أن تكون الـ routes بدون parameters قبل الـ resource
    Route::get('/anneescolaires/list', [\App\Http\Controllers\AnneescolaireController::class, 'list'])->name('anneescolaires.list');
    Route::get('/anneescolaires/get-classes', [\App\Http\Controllers\AnneescolaireController::class, 'getClassesFromYear'])->name('anneescolaires.get-classes');
    Route::resource('anneescolaires', \App\Http\Controllers\AnneescolaireController::class);
    Route::get('/anneescolaires/{anneescolaire}/details', [\App\Http\Controllers\AnneescolaireController::class, 'showDetails'])->name('anneescolaires.details');
    Route::post('/anneescolaires/{anneescolaire}/clone-classes', [\App\Http\Controllers\AnneescolaireController::class, 'cloneClasses'])->name('anneescolaires.clone-classes');
    Route::post('/anneescolaires/{anneescolaire}/store-classe', [\App\Http\Controllers\AnneescolaireController::class, 'storeClasse'])->name('anneescolaires.store-classe');
    Route::get('/anneescolaires/get-classes-by-niveau', [\App\Http\Controllers\AnneescolaireController::class, 'getClassesByNiveau'])->name('anneescolaires.get-classes-by-niveau');
    Route::get('/anneescolaires/get-available-students', [\App\Http\Controllers\AnneescolaireController::class, 'getAvailableStudents'])->name('anneescolaires.get-available-students');
    Route::post('/anneescolaires/{anneescolaire}/assign-students', [\App\Http\Controllers\AnneescolaireController::class, 'assignStudents'])->name('anneescolaires.assign-students');
    Route::post('/anneescolaires/{anneescolaire}/import-students', [\App\Http\Controllers\AnneescolaireController::class, 'importStudents'])->name('anneescolaires.import-students');
    Route::get('/anneescolaires/download-student-template', [\App\Http\Controllers\AnneescolaireController::class, 'downloadStudentTemplate'])->name('anneescolaires.download-student-template');


    Route::resource('salle-de-classes', SalleDeClasseController::class);
    Route::get('salle-de-classes-list', [SalleDeClasseController::class, 'list'])->name('salle-de-classes.list');


    Route::get( 'departements', [DepartementController::class, 'index'])->name('departements.index');
    Route::get('departements/list', [DepartementController::class, 'list'])->name('departements.list');
    Route::get('departements/create', [DepartementController::class, 'create'])->name('departements.create');
    Route::post('departements/create', [DepartementController::class, 'store'])->name('departements.store');
    Route::get('departements/{id}/edit', [DepartementController::class, 'edit'])->name('departements.edit');
    Route::put('departements/{id}', [DepartementController::class, 'update'])->name('departements.update');
    Route::delete('departements/destroy/{id}', [DepartementController::class, 'destroy'])->name('departements.destroy');


    Route::get('specialities/list', [SpecialityController::class, 'list'])->name('specialities.list');

    Route::resource('specialities', SpecialityController::class);



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


    // Classes routes - specific routes before resource
    Route::get('/classes/get-matching-classes', [ClasseController::class, 'getMatchingClasses'])->name('classes.get-matching-classes');
    Route::get('/classes/get-classes-for-students', [ClasseController::class, 'getClassesForStudents'])->name('classes.get-classes-for-students');
    Route::get('/classes/get-emplois', [ClasseController::class, 'getEmploisFromClass'])->name('classes.get-emplois');
    Route::get('/classes/get-students', [ClasseController::class, 'getStudentsFromClass'])->name('classes.get-students');
    Route::get('/classes/list', [ClasseController::class, 'list'])->name('classes.list');
    Route::get('/classes/delete/{id}', [ClasseController::class,'destroy'])->name('classes.delete');
    Route::post('/classes/{class}/clone-emplois', [ClasseController::class, 'cloneEmplois'])->name('classes.clone-emplois');
    Route::post('/classes/{class}/clone-students', [ClasseController::class, 'cloneStudents'])->name('classes.clone-students');
    Route::post('/classes/{class}/import-students', [ClasseController::class, 'importStudents'])->name('classes.import-students');
    Route::get('/classes/{class}/download-template', [ClasseController::class, 'downloadStudentTemplate'])->name('classes.download-template');
    Route::resource('classes', ClasseController::class);
    // Routes personnalisées avant resource routes
    Route::get('/emplois/get-teachers', [EmploiTempsController::class, 'getTeachers'])->name('emplois.getTeachers');
    Route::get('/emplois/get-subjects', [EmploiTempsController::class, 'getSubjects'])->name('emplois.getSubjects');
    Route::get('/emplois/get-teachers-by-department', [EmploiTempsController::class, 'getTeachersByDepartment'])->name('emplois.getTeachersByDepartment');
    Route::post('/emplois/get-trimesters', [EmploiTempsController::class, 'getTrimesters'])->name('emplois.getTrimesters');
    Route::get('emplois/list', [EmploiTempsController::class, 'show'])->name('emplois.list');
    Route::get('emplois/stats', [EmploiTempsController::class, 'getStats'])->name('emplois.stats');
    Route::get('emplois/filters', [EmploiTempsController::class, 'getFilters'])->name('emplois.filters');
    Route::get('emplois/showEmploi/{classId}', [EmploiTempsController::class, 'showEmploi'])->name('emplois.showEmploi');
    Route::get('emplois/export-all-classes-pdf', [EmploiTempsController::class, 'exportAllClassesPdf'])->name('emplois.exportAllClassesPdf');

    // Routes du calendrier
    Route::get('emplois/calendar/reference-data', [EmploiTempsController::class, 'getReferenceData'])->name('emplois.calendar.referenceData');
    Route::get('emplois/calendar/events', [EmploiTempsController::class, 'getCalendarEvents'])->name('emplois.calendar.events');
    Route::get('emplois/calendar/event/{id}', [EmploiTempsController::class, 'getCalendarEvent'])->name('emplois.calendar.event');
    Route::post('emplois/calendar/event', [EmploiTempsController::class, 'storeCalendarEvent'])->name('emplois.calendar.store');
    Route::put('emplois/calendar/event/{id}', [EmploiTempsController::class, 'updateCalendarEvent'])->name('emplois.calendar.update');
    Route::delete('emplois/calendar/event/{id}', [EmploiTempsController::class, 'deleteCalendarEvent'])->name('emplois.calendar.delete');
    Route::post('emplois/calendar/check-availability', [EmploiTempsController::class, 'checkAvailability'])->name('emplois.calendar.checkAvailability');

    // Resource route
    Route::resource('emplois', EmploiTempsController::class);
    Route::get('show-calendrier', [EmploiTempsController::class, 'showCalender'])->name('emplois.showCalender');

    Route::resource('subjects', \App\Http\Controllers\SubjectController::class);
    Route::get('subjects_teachers/list', [\App\Http\Controllers\SubjectTeacherController::class,'listSubjectTeacher'])->name('subjects_teachers.list');
    Route::resource('subjects_teachers', \App\Http\Controllers\SubjectTeacherController::class);

    // Students Management Routes
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);

    // طلبات الأساتذة
    Route::get('/teacher-requests', [App\Http\Controllers\Admin\TeacherRequestController::class, 'index'])->name('teacher-requests.index');
    Route::get('/teacher-requests/{teacherRequest}', [App\Http\Controllers\Admin\TeacherRequestController::class, 'show'])->name('teacher-requests.show');
    Route::post('/teacher-requests/{teacherRequest}/approve', [App\Http\Controllers\Admin\TeacherRequestController::class, 'approve'])->name('teacher-requests.approve');
    Route::post('/teacher-requests/{teacherRequest}/reject', [App\Http\Controllers\Admin\TeacherRequestController::class, 'reject'])->name('teacher-requests.reject');

    // Attestations (Admin)
    Route::prefix('attestations')->name('attestations.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AttestationController::class, 'index'])->name('index');
        Route::get('/{attestation}', [App\Http\Controllers\Admin\AttestationController::class, 'show'])->name('show');
        Route::post('/{attestation}/approve', [App\Http\Controllers\Admin\AttestationController::class, 'approve'])->name('approve');
        Route::post('/{attestation}/reject', [App\Http\Controllers\Admin\AttestationController::class, 'reject'])->name('reject');
        Route::get('/{attestation}/download', [App\Http\Controllers\Admin\AttestationController::class, 'downloadPdf'])->name('download');
    });

    // Routes de gestion des pointages
    Route::prefix('pointages')->name('pointages.')->group(function () {
        // Routes du calendrier (avant les routes avec paramètres)
        Route::get('/calendar', [PointageController::class, 'calendar'])->name('calendar');
        Route::get('/calendar/events', [PointageController::class, 'getCalendarEvents'])->name('calendar.events');
        Route::post('/calendar/store', [PointageController::class, 'storeCalendarPointage'])->name('calendar.store');

        // Page du pointage rapide
        Route::get('/rapide/aujourd-hui', [PointageController::class, 'rapide'])->name('rapide');
        Route::get('/rapide/data', [PointageController::class, 'getRapideData'])->name('rapide.data');
        Route::post('/rapide/store', [PointageController::class, 'storeRapideAjax'])->name('rapide.store');
        Route::get('/rapide/export-pdf', [PointageController::class, 'exportRapidePdf'])->name('rapide.export-pdf');

        // AJAX endpoints
        Route::get('/ajax/emplois', [PointageController::class, 'getEmploisForTeacher'])->name('get-emplois');
        Route::get('/ajax/statistiques', [PointageController::class, 'getStatistiques'])->name('get-statistiques');

        // Routes CRUD standard
        Route::get('/', [PointageController::class, 'index'])->name('index');
        Route::get('/create', [PointageController::class, 'create'])->name('create');
        Route::post('/', [PointageController::class, 'store'])->name('store');
        Route::get('/{pointage}', [PointageController::class, 'show'])->name('show');
        Route::get('/{pointage}/edit', [PointageController::class, 'edit'])->name('edit');
        Route::put('/{pointage}', [PointageController::class, 'update'])->name('update');
        Route::post('/{pointage}/update-status', [PointageController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{pointage}', [PointageController::class, 'destroy'])->name('destroy');
    });

});
