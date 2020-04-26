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

Route::get('prueba/inicia', 'PruebaController@inicia'); 

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('prueba/tabla', 'CarreraController@tabla'); 

Route::post('carrera/store', 'CarreraController@store'); 

Route::get('persona/nuevo', 'PersonaController@nuevo'); 
Route::post('persona/guarda', 'PersonaController@guarda');
Route::get('Persona/listado', 'PersonaController@listado');
Route::get('Persona/ajax_listado', 'PersonaController@ajax_listado');
Route::get('Persona/ver_persona/{persona_id}', 'PersonaController@ver_persona');
Route::get('Persona/verifica', 'PersonaController@verifica');
// Route::get('persona/ajax_datos', function () {
    // return datatables()->query(DB::table('personas'))->toJson();
// });
Route::get('persona/exportarexcel', 'PersonaController@exportarExcel')->name('personas.exportarexcel');


Route::get('user/asignar', 'UserController@asignar');

//NOTAS
Route::get('nota/listado', 'NotaController@listado');

Route::get('nota/detalle/{id}', 'NotaController@detalle');

Route::get('nota/exportarexcel/{id}', 'NotaController@exportarexcel');

Route::get('nota/segundoTurno/{id}', 'NotaController@segundoTurno');

Route::post('nota/actualizar', 'NotaController@actualizar');

Route::post('nota/ajax_importar', 'NotaController@ajax_importar');

Route::post('nota/segundoTurnoActualizar', 'NotaController@segundoTurnoActualizar');

//NOTAS PROPUESTA
Route::get('notaspropuesta/listado', 'NotasPropuestaController@listado');

Route::get('notaspropuesta/exportarexcel/{id}', 'NotasPropuestaController@exportarexcel');

Route::post('notaspropuesta/actualizar', 'NotasPropuestaController@actualizar');

Route::post('notaspropuesta/ajax_importar', 'NotasPropuestaController@ajax_importar');

//MIGRACIONES
Route::get('Migracion/inicia', 'MigracionController@inicia');

Route::get('Migracion/usuario', 'MigracionController@usuario');

Route::get('Migracion/asignatura', 'MigracionController@asignatura');

Route::get('Migracion/notas_propuestas', 'MigracionController@notas_propuestas');

//INSCRIPCIONES
Route::get('Inscripcion/inscripcion', 'InscripcionController@inscripcion');

Route::get('Inscripcion/busca_ci', 'InscripcionController@busca_ci');

Route::get('Inscripcion/selecciona_asignatura', 'InscripcionController@selecciona_asignatura');

Route::get('Inscripcion/contabilidad', 'InscripcionController@contabilidad');

Route::get('Inscripcion/secretariado', 'InscripcionController@secretariado');

Route::get('Inscripcion/auxiliar', 'InscripcionController@auxiliar');

Route::get('Inscripcion/busca_asignatura', 'InscripcionController@busca_asignatura');

Route::get('Inscripcion/busca_carrera', 'InscripcionController@busca_carrera');

Route::post('Inscripcion/store', 'InscripcionController@store');

Route::get('Inscripcion/lista', 'InscripcionController@lista');

Route::get('Inscripcion/ajax_datos', 'InscripcionController@ajax_datos');

Route::get('Inscripcion/re_inscripcion/{id}', 'InscripcionController@re_inscripcion');

Route::get('Inscripcion/asignaturas_a_tomar', 'InscripcionController@asignaturas_a_tomar');

Route::get('Inscripcion/tomar_asignaturas/{persona_id}', 'InscripcionController@tomar_asignaturas');

Route::get('Inscripcion/vista', 'InscripcionController@vista');



// ADMINISTRACION
Route::post('Asignatura/guarda', 'AsignaturaController@guarda');
Route::get('Carrera/listado', 'CarreraController@listado');
Route::get('Carrera/ajax_lista_asignaturas', 'CarreraController@ajax_lista_asignaturas');
Route::get('Asignatura/listado_malla/{carrera_id}', 'AsignaturaController@listado_malla');
Route::get('Asignatura/eliminar/{asignatura_id}', 'AsignaturaController@eliminar');

Route::post('User/guarda_asignacion', 'UserController@guarda_asignacion');
Route::get('User/listado', 'UserController@listado');
Route::get('User/ajax_listado', 'UserController@ajax_listado');
Route::get('User/asigna_materias/{user_id}', 'UserController@asigna_materias');
Route::get('User/eliminaAsignacion/{np_id}', 'UserController@eliminaAsignacion');
Route::get('Persona/detalle/{persona_id}', 'PersonaController@detalle');
Route::get('Persona/ajax_materias/{carrera_id}/{persona_id}/{anio_vigente}', 'PersonaController@ajax_materias');