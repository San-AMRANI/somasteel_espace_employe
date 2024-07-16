<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Middleware\IsRHmd;
use App\Http\Middleware\IsResponsable;
use App\Http\Middleware\IsRhOrResp;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DemandesController;
use App\Http\Controllers\DemandesCongeController;
use App\Http\Controllers\AnnuaireController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AttendanceController;




//I don't need registration
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::Put('/register', [RegisterController::class, 'update']);

//somasteel Blog

// Route::get('/SomaProduit', [BlogeController::class, 'produitIndex'])->name('bloge.produit');






Route::middleware('auth')->group(function () {
    Route::put('/home/updateEmail', [HomeController::class, 'updateEmail'])->name('home.update');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::put('/home/update-picture', [HomeController::class, 'updatePicture'])->name('profile.updatePicture');
    Route::get('/home/profiles_imgs/{filename}', [HomeController::class ,'getProfileImage'])->name('profile.image');
    Route::delete('/home/delete-picture', [HomeController::class,'deleteProfilePicture'])->name('home.delete.picture');
    // dd('test');

    Route::get('/demandes', [DemandesController::class, 'index'])->name('demandes.index');
    Route::post('/demandes', [DemandesCongeController::class, 'store'])->name('demandesconge.store');
    Route::put('/demandes/{demande_id}/decide', [DemandesCongeController::class, 'update'])->name('demandeconge.update');
    Route::get('/demandes/download/{dc_id}', [DemandesCongeController::class, 'downloadConge'])->name('demandeConge.downloadConge');

    //Absence
    Route::get('/Permissions_Absence', [AbsenceController::class, 'index'])->name('absence.index');



    //Annuaire routes
    Route::middleware(IsRhOrResp::class)->group(function () {
        Route::get('/AbsDeclaration', [AttendanceController::class, 'index'])->name('absenceDec.index');
        Route::post('/AbsDeclaration/store', [AttendanceController::class, 'store'])->name('absenceDec.store');

    });
    Route::middleware(IsRHmd::class)->group(function () {
        Route::get('/Annuaire', [AnnuaireController::class, 'index'])->name('annuaire.index');//done
        Route::get('/Annuaire/{depart}', [AnnuaireController::class, 'showDepartment'])->name('annuaire.depart');//done
        Route::get('/Annuaire/{depart}/{employee_nom}/{employee_id}', [AnnuaireController::class,'showEmployee'])->name('annuaire.employee');//done
        Route::post('/Annuaire/create-department', [AnnuaireController::class,'storeDepart'])->name('annuaire.depart.store');
        // Route::get('/Annuaire/{depart}/{employee_nom}_{employee_id}/edit', [AnnuaireController::class, 'editEmp'])->name('annuaire.editEmployee');
        Route::put('/Annuaire/update/{employee_id}', [AnnuaireController::class, 'updateEmp'])->name('annuaire.employee.update');
        Route::put('/Annuaire/updatePass/{employee_id}', [AnnuaireController::class, 'changePassword'])->name('annuaire.employee.changePassword');
        Route::delete('/Annuaire/delete/{employee_id}', [AnnuaireController::class, 'destroyEmp'])->name('annuaire.employee.destroy');
        Route::post('/Annuaire/{depart}/register', [AnnuaireController::class, 'storeEmployee'])->name('annuaire.employee.register');
        
        
        //password
        //Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        //Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        //Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        //Route::post('/password/reset', [ResetPasswordController::class, 'reset']);
    });
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/home');
    } else {
        return view('auth.login');
    }
});

// Auth::routes();
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


