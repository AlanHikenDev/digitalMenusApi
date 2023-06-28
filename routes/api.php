<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Router\ApiTools;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout');
Route::post('/register', 'LoginController@register');
Route::post('/menu/buscarporurl', 'MenuController@buscarporurl');
Route::post('/menu/buscarporidmenu', 'MenuController@buscarporidMenu');

//rutas autentificadas por sanctum 
Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/user', function (Request $request) {
    return $request->user();
  });
  
  Route::post('/users/getbyId', 'UsersController@getbyId');
  Route::post('/menu/buscarporidusr', 'MenuController@buscarporidusr');
  ApiTools::restMethods("menu", "MenuController", []);
  ApiTools::restMethods("users", "UsersController", []);
  Route::post('/menu/imagenupdate', 'MenuController@imagenupdate');

});