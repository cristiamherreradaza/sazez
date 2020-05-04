<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('logueo', function () {
    return view('layouts.index');
});

Route::get('/', 'Auth\LoginController@inicio');

// Route::get('user/{id}', function($id){
//     return 'Bienvenido '.$id_demo;
// });

// Route::get('users/{id}', function ($id) {
    
// });
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('Producto/nuevo', 'ProductoController@nuevo');
Route::post('Producto/guarda', 'ProductoController@guarda');
Route::get('Producto/listado', 'ProductoController@listado');
Route::get('Producto/ajax_listado', 'ProductoController@ajax_listado');

Route::get('User/listado', 'UserController@listado');
Route::get('User/ajax_listado', 'UserController@ajax_listado');
Route::get('User/asigna_materias/{user_id}', 'UserController@asigna_materias');
Route::get('User/eliminaAsignacion/{np_id}', 'UserController@eliminaAsignacion');
Route::get('Persona/detalle/{persona_id}', 'PersonaController@detalle');
Route::get('Persona/ajax_materias/{carrera_id}/{persona_id}/{anio_vigente}', 'PersonaController@ajax_materias');

// MARCAS
Route::get('Marca/listado', 'MarcaController@listado');
Route::post('Marca/guardar', 'MarcaController@guardar');
Route::post('Marca/actualizar', 'MarcaController@actualizar');
Route::post('Marca/eliminar', 'MarcaController@eliminar');