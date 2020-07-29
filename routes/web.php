<?php

use App\Mail\CuponMail;
use Illuminate\Support\Facades\Mail;
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
    Route::get('Producto/panelControl', 'ProductoController@panelControl');
    Route::get('Producto/muestra/{id}', 'ProductoController@muestra');
    Route::get('Producto/info', 'ProductoController@info');
    Route::get('Producto/elimina/{id}', 'ProductoController@elimina');
    Route::get('Producto/exportar', 'ProductoController@exportar');
    Route::get('Producto/garantia', 'ProductoController@garantia');
    Route::get('Producto/ajaxProductosGarantia','ProductoController@ajaxProductosGarantia');
    Route::post('Producto/guardaGarantia', 'ProductoController@guardaGarantia');

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
    Route::post('Cliente/password', 'ClienteController@password');
    Route::post('Cliente/ajaxGuardaCliente', 'ClienteController@ajaxGuardaCliente');
    Route::post('Cliente/ajaxVerificaCorreo', 'ClienteController@ajaxVerificaCorreo');
    Route::get('Cliente/ajaxComboClienteNuevo/{clienteId}', 'ClienteController@ajaxComboClienteNuevo');
    Route::post('Cliente/ajaxEditaCliente', 'ClienteController@ajaxEditaCliente');
    Route::post('Cliente/guardaAjaxClienteEdicion', 'ClienteController@guardaAjaxClienteEdicion');

    //CUPONES
    Route::get('Cupon/nuevo', 'CuponController@nuevo');
    Route::get('Cupon/listado', 'CuponController@listado');
    Route::get('Cupon/ajax_listado', 'CuponController@ajax_listado');
    Route::get('Cupon/ajaxMuestraCupon', 'CuponController@ajaxMuestraCupon');
    Route::get('Cupon/pruebaCorreo', 'CuponController@pruebaCorreo');
    Route::post('Cupon/guardar', 'CuponController@guardar');
    Route::post('Cupon/ajaxBuscaProducto', 'CuponController@ajaxBuscaProducto');//va
    Route::get('Cupon/eliminar/{id}', 'CuponController@eliminar');
    Route::post('Cupon/cobrar', 'CuponController@cobrar');
    Route::get('Cupon/ver/{id}', 'CuponController@ver');

    //MOVIMIENTOS INGRESOS
    Route::get('Movimiento/ingreso', 'MovimientoController@ingreso');
    Route::post('Movimiento/ajaxBuscaProducto', 'MovimientoController@ajaxBuscaProducto');
    Route::post('Movimiento/guarda', 'MovimientoController@guarda');

    //PROMOS
    Route::get('Combo/nuevo', 'ComboController@nuevo');//va
    Route::post('Combo/guarda', 'ComboController@guarda');//va
    Route::post('Combo/ajaxBuscaProducto', 'ComboController@ajaxBuscaProducto');//va
    Route::get('Combo/editar/{id}', 'ComboController@editar');//va
    Route::post('Combo/actualiza', 'ComboController@actualiza');
    Route::post('Combo/agregar_combo_producto', 'ComboController@agregar_combo_producto');
    Route::post('Combo/ajaxMuestraPromo', 'ComboController@ajaxMuestraPromo');
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

    Route::get('Escala/grupo_escala', 'EscalaController@grupo_escala');
    Route::get('Escala/ajax_producto', 'EscalaController@ajax_producto');
    Route::post('Escala/guarda_multiple', 'EscalaController@guarda_multiple');


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
    Route::get('Pedido/editar/{id}', 'PedidoController@editar');

    // PROVEEDORES
    Route::get('Proveedor/listado', 'ProveedorController@listado');
    Route::post('Proveedor/guardar', 'ProveedorController@guardar');
    Route::post('Proveedor/actualizar', 'ProveedorController@actualizar');
    Route::get('Proveedor/eliminar/{id}', 'ProveedorController@eliminar');

    // ENTREGAS
    Route::get('Entrega/entrega/{id}', 'EntregaController@entrega');
    Route::post('Entrega/store', 'EntregaController@store');
    Route::get('Pedido/pedido_productos/{id}', 'PedidoController@pedido_productos');

    Route::get('Entrega/excel/{id}', 'EntregaController@excel');

    Route::get('Entrega/envio', 'EntregaController@envio');
    Route::post('Entrega/ajax_importar', 'EntregaController@ajax_importar');
    Route::post('Entrega/importar_envio', 'EntregaController@importar_envio');
    Route::get('Entrega/ver_pedido/{id}', 'EntregaController@ver_pedido');

    //TIPOS
    Route::get('Tipo/listado', 'TipoController@listado');
    Route::post('Tipo/guardar', 'TipoController@guardar');
    Route::post('Tipo/actualizar', 'TipoController@actualizar');
    Route::get('Tipo/eliminar/{id}', 'TipoController@eliminar');

    // USUARIOS
    Route::get('User/listado', 'UserController@listado');
    Route::post('User/guardar', 'UserController@guardar');
    Route::post('User/actualizar', 'UserController@actualizar');
    Route::get('User/eliminar/{id}', 'UserController@eliminar');
    Route::get('User/perfil', 'UserController@perfil');
    Route::post('User/password', 'UserController@password');
    Route::post('User/actualizarImagen', 'UserController@actualizarImagen');

    //VENTAS
    Route::post('Venta/ajaxBuscaProducto', 'VentaController@ajaxBuscaProducto');
    Route::post('Venta/ajaxBuscaProductoTienda', 'VentaController@ajaxBuscaProductoTienda');
    Route::post('Venta/guardaVenta', 'VentaController@guardaVenta');
    Route::post('Venta/adicionaItem', 'VentaController@adicionaItem');
    Route::get('Venta/nuevo', 'VentaController@nuevo');
    Route::get('Venta/tienda', 'VentaController@tienda');
    Route::get('Venta/listado', 'VentaController@listado');
    Route::get('Venta/ajax_listado', 'VentaController@ajax_listado');
    Route::get('Venta/muestra/{ventaId}', 'VentaController@muestra');
    Route::post('Venta/elimina', 'VentaController@elimina');
    Route::post('Venta/ajaxCambiaProducto', 'VentaController@ajaxCambiaProducto');
    
    // ENVIO
    Route::get('Envio/nuevo', 'EnvioController@nuevo');
    Route::post('Envio/ajaxBuscaProductos', 'EnvioController@ajaxBuscaProductos');
    Route::post('Envio/guarda', 'EnvioController@guarda');
    Route::get('Envio/listado', 'EnvioController@listado');
    Route::get('Envio/ajax_listados', 'EnvioController@ajax_listados');
    
    //MOVIMIENTOS   
    Route::get('Movimiento/registraDatos', 'MovimientoController@registraDatos');
    Route::get('Envio/ver_pedido/{id}', 'EnvioController@ver_pedido');
    Route::post('Movimiento/ajaxMuestraTotalesAlmacen', 'MovimientoController@ajaxMuestraTotalesAlmacen');

    //ALCANCES
    Route::get('Alcance', 'AlcanceController@index');
    Route::get('Alcance/ajax_alcance', 'AlcanceController@ajax_alcance');
    Route::get('Alcance/ajax_ventas_meses', 'AlcanceController@ajax_ventas_meses');

    Route::post('Alcance/guarda', 'AlcanceController@guarda');

    //REPORTE DE TIENDA
    Route::get('Tienda/reporte_tienda', 'TiendaController@reporte_tienda');
    Route::get('Tienda/ajax_tienda_listado', 'TiendaController@ajax_tienda_listado');

});

Route::get('Cliente/inicio', 'ClienteController@inicio');

Route::get('Tienda/inicio', 'TiendaController@inicio');
Route::get('Tienda/ver/{id}', 'TiendaController@ver');

//PRUEBAS -> BORRAR
Route::get('Cupon/test', 'CuponController@codigoGenerador');
Route::get('Cupon/tests', 'CuponController@test');
Route::get('/email', function() {
    Mail::to('arielfernandez.rma7@gmail.com')->send(new CuponMail());
    //return new CuponMail(); 
});
Route::get('Cupon/prueba', 'CuponController@pruebaImprime');
