<?php
use App\Http\Controllers\Api\Web\Frontoffice\HomeController; 
use App\Http\Controllers\Api\Web\Backoffice\WebRouteController;

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



//La route pour la page d'accueil

Route::get('/', [HomeController::class, 'home'] );

//Les routes pour les administrateurs

Route::get('/admin/dashboard',[WebRouteController::class, 'administrateur']);
 
Route::get('/admin/publications/create',[WebRouteController::class, 'administrateur']);

Route::get('/admin/publications/{slug}/create',[WebRouteController::class, 'administrateur']);

Route::get('/admin/publications',[WebRouteController::class, 'administrateur']);

Route::get('/admin/brouillons',[WebRouteController::class, 'administrateur']);

Route::get('/admin/category',[WebRouteController::class, 'administrateur']);

Route::get('/admin/tags',[WebRouteController::class, 'administrateur']);

Route::get('/admin/files',[WebRouteController::class, 'administrateur']);

Route::get('/admin/authors',[WebRouteController::class, 'administrateur']);
 
//Les routes pour les publicateurs

Route::get('/pub/dashboard',[WebRouteController::class, 'publicateur']);

Route::get('/pub/profile',[WebRouteController::class, 'administrateur']);
 