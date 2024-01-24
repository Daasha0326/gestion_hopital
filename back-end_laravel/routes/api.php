<?php

use App\Http\Controllers\AdminContrller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('listePatient/user', [UserController::class, 'listePatient']);
    Route::get('nbrePatient', [UserController::class, 'nbrePatientParJour']);
    Route::post('createDossier', [UserController::class, 'createDossierMedical']);
});


Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout/{id}', [AuthController::class, 'logout']);

//Routes pour l'administrateur
Route::post('/ajout/user', [AdminController::class, 'store']);
Route::get('/user/{id}', [AdminController::class, 'show']);
Route::post('update/user', [AdminController::class, 'update']);
Route::get('/delete/user', [AdminController::class, 'destroy']);



// Patients
Route::get('patient', [UserController::class, 'index']);
Route::post('/ajout/patient', [UserController::class, 'ajouterInfoPatient']);
Route::delete('patient/{patient}', [UserController::class, 'destroy']);
Route::put('/patient/{patient}', [UserController::class, 'update']);

