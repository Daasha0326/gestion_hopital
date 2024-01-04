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

    //Route pour le medecin
    Route::get('/medecin/liste/patients', [UserController::class, 'listePatient']);
    Route::get('/medecin/nombre/patient', [UserController::class, 'nbrePatientParJour']);
    Route::post('medecin/create/dossier', [UserController::class, 'createDossierMedical']);

    //Routes pour l'administrateur
    Route::get('/admin/liste/users', [AdminController::class, 'index']);
    Route::post('/http://localhost:8000/api/admin/liste/userscreate/user', [AdminController::class, 'store']);
    Route::get('/user/{id}', [AdminController::class, 'show']);
    Route::put('/update/user/{id}', [AdminController::class, 'update']);
    Route::delete('/delete/user/{id}', [AdminController::class, 'destroy']);
    Route::post('/create/service', [AdminController::class, 'createService']);
});


Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout/{id}', [AuthController::class, 'logout']);
