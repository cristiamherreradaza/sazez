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

Route::get('/home', 'ProductoController@panelControl')->name('home');

// LOGIN SOCIALITE GITHUB
Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');

// LOGIN SOCIALITE FACEBOOK
Route::get('login/facebook', 'Auth\LoginController@redirectToProviderFacebook');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallbackFacebook');

// LOGIN SOCIALITE GOOGLE
Route::get('login/google', 'Auth\LoginController@redirectToProviderGoogle');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallbackGoogle');

// Aqui colocar todas las rutas para los usuarios que no sean clientes.... todavia por definir.
Route::middleware(['auth', 'admin'])->group(function () {
    // PRODUCTOS
    Route::post('Producto/guarda', 'ProductoController@guarda');
    Route::post('Producto/guardaEdicion', 'ProductoController@guardaEdicion');
    Route::post('Producto/importaExcel', 'ProductoController@importaExcel');
    Route::get('Producto/nuevo', 'ProductoController@nuevo');
    Route::get('Producto/listado', 'ProductoController@listado');
    Route::get('Producto/ajax_listado', 'ProductoController@ajax_listado');
    Route::get('Producto/edita/{producto_id}', 'ProductoController@edita');
    Route::get('Producto/elimina_imagen/{imagen_id}/{producto_id}', 'ProductoController@elimina_imagen');
    Route::get('Producto/ajaxMuestraImgProducto/{producto_id}', 'ProductoController@ajaxMuestraImgProducto');

    // PAQUETES
    Route::get('Paquete/nuevo', 'PaqueteController@nuevo');

    Route::get('User/listado', 'UserController@listado');
    Route::get('User/ajax_listado', 'UserController@ajax_listado');
    Route::get('User/asigna_materias/{user_id}', 'UserController@asigna_materias');
    Route::get('User/eliminaAsignacion/{np_id}', 'UserController@eliminaAsignacion');
    Route::get('Persona/detalle/{persona_id}', 'PersonaController@detalle');
    Route::get('Persona/ajax_materias/{carrera_id}/{persona_id}/{anio_vigente}', 'PersonaController@ajax_materias');

    // ALMACENES
    Route::get('Almacen/listado', 'AlmacenController@listado');
    Route::post('Almacen/guardar', 'AlmacenController@guardar');
    Route::post('Almacen/actualizar', 'AlmacenController@actualizar');
    Route::get('Almacen/eliminar/{id}', 'AlmacenController@eliminar');

    //CATEGORIAS
    Route::get('Categoria/listado', 'CategoriaController@listado');
    Route::post('Categoria/guardar', 'CategoriaController@guardar');
    Route::post('Categoria/actualizar', 'CategoriaController@actualizar');
    Route::get('Categoria/eliminar/{id}', 'CategoriaController@eliminar');

    //CLIENTES
    Route::get('Cliente/listado', 'ClienteController@listado');
    Route::post('Cliente/guardar', 'ClienteController@guardar');
    Route::post('Cliente/actualizar', 'ClienteController@actualizar');
    Route::get('Cliente/eliminar/{id}', 'ClienteController@eliminar');


    //COMBOS
    Route::get('Combo/nuevo', 'ComboController@nuevo');
    Route::post('Combo/guarda', 'ComboController@guarda');
    Route::get('Combo/ajax_listado_producto', 'ComboController@ajax_listado_producto');
    Route::get('Combo/editar/{id}', 'ComboController@editar');
    Route::get('Combo/lista_combo_productos/{id}', 'ComboController@lista_combo_productos');
    Route::post('Combo/agregar_combo_producto', 'ComboController@agregar_combo_producto');
    //Route::post('Combo/eliminar_combo_producto', 'ComboController@eliminar_combo_producto');
    Route::get('Combo/elimina_producto/{combo_id}/{producto_id}', 'ComboController@elimina_producto');
    Route::get('Combo/listado', 'ComboController@listado');
    Route::get('Combo/eliminar/{id}', 'ComboController@eliminar');
    Route::post('Combo/actualiza_precio', 'ComboController@actualiza_precio');;

    // ESCALAS
    Route::get('Escala/listado', 'EscalaController@listado');
    Route::post('Escala/guardar', 'EscalaController@guardar');
    Route::post('Escala/actualizar', 'EscalaController@actualizar');
    Route::get('Escala/eliminar/{id}', 'EscalaController@eliminar');

    // MARCAS
    Route::get('Marca/listado', 'MarcaController@listado');
    Route::post('Marca/guardar', 'MarcaController@guardar');
    Route::post('Marca/actualizar', 'MarcaController@actualizar');
    Route::get('Marca/eliminar/{id}', 'MarcaController@eliminar');

    // PEDIDOS
    Route::get('Pedido/nuevo', 'PedidoController@nuevo');
    Route::get('Pedido/pedido_productos/{id}', 'PedidoController@pedido_productos');
    Route::get('Pedido/ajax_listado_producto', 'PedidoController@ajax_listado_producto');
    Route::post('Pedido/agregar_pedido_producto', 'PedidoController@agregar_pedido_producto');
    Route::post('Pedido/ajaxBuscaProducto', 'PedidoController@ajaxBuscaProducto');
    Route::get('Pedido/lista_pedido_productos/{id}', 'PedidoController@lista_pedido_productos');
    Route::post('Pedido/actualiza_cantidad', 'PedidoController@actualiza_cantidad');
    Route::get('Pedido/elimina_producto/{pedido_id}/{producto_id}', 'PedidoController@elimina_producto');
    Route::post('Pedido/guarda', 'PedidoController@guarda');
    Route::get('Pedido/eliminar/{id}', 'PedidoController@eliminar');


    Route::get('Pedido/listado', 'PedidoController@listado');
    Route::get('Pedido/ajax_listado', 'PedidoController@ajax_listado');

    // ENTREGAS
    Route::get('Entrega/entrega/{id}', 'EntregaController@entrega');
    Route::post('Entrega/store', 'EntregaController@store');
    Route::get('Pedido/pedido_productos/{id}', 'PedidoController@pedido_productos');

    Route::get('Entrega/excel/{id}', 'EntregaController@excel');

    Route::get('Entrega/envio', 'EntregaController@envio');
    Route::post('Entrega/ajax_importar', 'EntregaController@ajax_importar');
    Route::post('Entrega/importar_envio', 'EntregaController@importar_envio');
    Route::get('Entrega/ver_pedido/{id}', 'EntregaController@ver_pedido');

    // USUARIOS
    Route::get('User/listado', 'UserController@listado');
    Route::post('User/guardar', 'UserController@guardar');
    Route::post('User/actualizar', 'UserController@actualizar');
    Route::get('User/eliminar/{id}', 'UserController@eliminar');
    Route::get('User/perfil', 'UserController@perfil');

    //VENTAS
    Route::post('Venta/ajaxBuscaProducto', 'VentaController@ajaxBuscaProducto');
    Route::post('Venta/adicionaItem', 'VentaController@adicionaItem');
    Route::get('Venta/nuevo', 'VentaController@nuevo');

});

Route::get('Cliente/inicio', 'ClienteController@inicio');