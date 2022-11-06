<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuentasbancariasclienteController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProveedorcuentaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

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
//  Route::group(['middleware' => 'auth'], function () {
//      Route::post('store', "InicioController@store")->name('dashboard.store');
//      Route::get('/dashboard', [InicioController::class, 'index'])->name('dashboard');
//  }
Route::group(['middleware' => 'prevent-back-history'],function(){
	Auth::routes();
	Route::group(['middleware' => 'auth'], function () {
        // Route::group(['middleware' => 'checkstatus'], function () {

        // });
        Route::resource('roles', RoleController::class);
        Route::delete('role_delete_modal', 'App\Http\Controllers\RoleController@destroy')->name('roles.destroy');
        Route::resource('permissions', 'App\Http\Controllers\PermissionController');
        Route::delete('permiso_delete_modal', 'App\Http\Controllers\PermissionController@destroy')->name('permissions.destroy');
        Route::resource('users', UserController::class);
        Route::delete('user_delete_modal', 'App\Http\Controllers\UserController@destroy')->name('users.destroy');
        // Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::get('/existenciapdf', 'App\Http\Controllers\HomeController@existenciapdf')->name('existenciapdf');

        Route::get('change-password', 'App\Http\Controllers\ChangePasswordController@index')->name('changemy.password');
        Route::post('change-password', 'App\Http\Controllers\ChangePasswordController@store')->name('change.password');

        Route::resource('almacens','App\Http\Controllers\AlmacenController');
        Route::delete('almacen_delete_modal', 'App\Http\Controllers\AlmacenController@destroy')->name('almacens.destroy');

        Route::resource('clasificacions','App\Http\Controllers\ClasificacionController');
        Route::delete('clasificacion_delete_modal', 'App\Http\Controllers\ClasificacionController@destroy')->name('clasificacions.destroy');

        Route::resource('mercancias','App\Http\Controllers\MercanciaController');
        Route::delete('mercancia_delete_modal', 'App\Http\Controllers\MercanciaController@destroy')->name('mercancias.destroy');


        Route::resource('recepcions','App\Http\Controllers\RecepcionController');
        Route::get('recepcion_pdf/{recepcion}', 'App\Http\Controllers\RecepcionController@imprimir')->name('recepcions.imprimir');
        Route::put('recepcion_cancelar_modal', 'App\Http\Controllers\RecepcionController@cancelar')->name('recepcions.cancelar');
        Route::put('recepcion_firmar_modal', 'App\Http\Controllers\RecepcionController@firmar')->name('recepcions.firmar');

        Route::resource('recepcionmercancias','App\Http\Controllers\RecepcionmercanciaController');
        Route::delete('recep_mercancia_delete_modal', 'App\Http\Controllers\RecepcionmercanciaController@destroy')->name('recepcionmercancias.destroy');
        Route::put('recep_mercancia_update_modal', 'App\Http\Controllers\RecepcionmercanciaController@update')->name('recepcionmercancias.update');

        Route::resource('vales','App\Http\Controllers\ValeController');
        Route::put('vale_firmar_modal', 'App\Http\Controllers\ValeController@firmar')->name('vales.firmar');
        Route::get('vale_pdf/{vale}', 'App\Http\Controllers\ValeController@imprimir')->name('vales.imprimir');
        Route::put('vale_delete_modal', 'App\Http\Controllers\ValeController@cancelar')->name('vales.cancelar');


        Route::resource('valeitems','App\Http\Controllers\ValeitemController');
        Route::put('valeitem_edit_modal', 'App\Http\Controllers\ValeitemController@editar')->name('valeitems.editar');
        Route::delete('valeitem_delete_modal', 'App\Http\Controllers\ValeitemController@destroy')->name('valeitems.destroy');

        Route::resource('ficuentas','App\Http\Controllers\FicuentaController');
        Route::delete('ficuenta_delete_modal', 'App\Http\Controllers\FicuentaController@destroy')->name('ficuentas.destroy');

        Route::resource('fisubcuentas','App\Http\Controllers\FisubcuentaController');
        Route::get('fisubcuentas/get_by_cuenta/{ficuenta_id}', 'App\Http\Controllers\FisubcuentaController@get_by_cuenta')->name('fisubcuentas.get_by_cuenta');
        Route::delete('fisubcuenta_delete_modal', 'App\Http\Controllers\FisubcuentaController@destroy')->name('fisubcuentas.destroy');


        Route::resource('fiinfracuentas','App\Http\Controllers\FiinfracuentaController');
        Route::get('fiinfracuentas/get_by_subcuenta/{fisubcuenta_id}', 'App\Http\Controllers\FiinfracuentaController@get_by_subcuenta')->name('fiinfracuentas.get_by_subcuenta');
        Route::delete('analisis_delete_modal', 'App\Http\Controllers\FiinfracuentaController@destroy')->name('fiinfracuentas.destroy');

        Route::resource('clasificadorcuentas','App\Http\Controllers\ClasificadorcuentaController');
        Route::delete('clasificadorcuenta_delete_modal', 'App\Http\Controllers\ClasificadorcuentaController@destroy')->name('clasificadorcuentas.destroy');

        Route::resource('tipotproductos','App\Http\Controllers\TipotproductoController');
        Route::delete('tipotproducto_delete_modal', 'App\Http\Controllers\TipotproductoController@destroy')->name('tipotproductos.destroy');

        Route::resource('tipotproductos','App\Http\Controllers\TipotproductoController');
        Route::delete('tipotproducto_delete_modal', 'App\Http\Controllers\TipotproductoController@destroy')->name('tipotproductos.destroy');

        Route::resource('tproductos','App\Http\Controllers\TproductoController');
        Route::delete('tproducto_delete_modal', 'App\Http\Controllers\TproductoController@destroy')->name('tproductos.destroy');

        Route::resource('materiaprimas','App\Http\Controllers\MateriaprimaController');
        Route::put('materiaprima_edit_modal', 'App\Http\Controllers\MateriaprimaController@editar')->name('materiaprimas.editar');
        Route::delete('mprima_delete_modal', 'App\Http\Controllers\MateriaprimaController@destroy')->name('materiaprimas.destroy');

        Route::put('solicitudes_cancelar_modal', 'App\Http\Controllers\SolicitudeController@cancelar')->name('solicitudes.cancelar');
        Route::put('solicitudes_confirmar_modal', 'App\Http\Controllers\SolicitudeController@confirmar')->name('solicitudes.confirmar');
        Route::put('solicitudes_entregar_modal', 'App\Http\Controllers\SolicitudeController@entregar')->name('solicitudes.entregar');
        Route::get('solicitude_pdf/{solicitude}', 'App\Http\Controllers\SolicitudeController@pdf')->name('solicitudes.pdf');
        Route::resource('solicitudes','App\Http\Controllers\SolicitudeController');

        // Route::delete('solicitude_delete_modal', 'SolicitudeController@destroy')->name('solicitudes.destroy');

        Route::resource('solicitudproductos','App\Http\Controllers\SolicitudproductoController');
        Route::put('solicitudproducto_edit_modal', 'App\Http\Controllers\SolicitudproductoController@editar')->name('solicitudproductos.editar');
        Route::delete('solicitudproducto_delete_modal', 'App\Http\Controllers\SolicitudproductoController@destroy')->name('solicitudproductos.destroy');

        Route::resource('solicitudmateriaprimas','App\Http\Controllers\SolicitudmateriasprimaController');
        Route::put('solicitudmateriaprima_edit_modal', 'App\Http\Controllers\SolicitudmateriasprimaController@editar')->name('solicitudmateriaprimas.editar');
        Route::delete('solicitudmateriaprima_delete_modal', 'App\Http\Controllers\SolicitudmateriasprimaController@destroy')->name('solicitudmateriasprimas.destroy');

        Route::put('ordentrabajos_cancelar_modal', 'App\Http\Controllers\OrdentrabajoController@cancelar')->name('ordentrabajos.cancelar');
        Route::put('ordentrabajos_terminar_modal', 'App\Http\Controllers\OrdentrabajoController@terminar')->name('ordentrabajos.terminar');
        Route::get('ordentrabajos_pdf/{ot}', 'App\Http\Controllers\OrdentrabajoController@pdf')->name('ordentrabajos.pdf');
        Route::resource('ordentrabajos','App\Http\Controllers\OrdentrabajoController');

        Route::resource('otsolicitudes','App\Http\Controllers\OtsolicitudeController');
        Route::put('otsolicitude_terminar_modal', 'App\Http\Controllers\OtsolicitudeController@terminar')->name('otsolicitudes.terminar');
        Route::delete('otsolicitude_delete_modal', 'App\Http\Controllers\OtsolicitudeController@destroy')->name('otsolicitudes.destroy');

        Route::get('ofertas/{oferta?}/recalcular', 'App\Http\Controllers\OfertaController@recalcular')->name('ofertas.recalcular');
        Route::resource('ofertas','App\Http\Controllers\OfertaController');

        Route::resource('ofertamercancias','App\Http\Controllers\OfertamercanciaController');

        Route::resource('ofertaproductos','App\Http\Controllers\OfertaproductoController');
        Route::put('ofertaproducto_edit_modal', 'App\Http\Controllers\OfertaproductoController@editar')->name('ofertaproductos.editar');
        Route::delete('ofertaproducto_delete_modal', 'App\Http\Controllers\OfertaproductoController@destroy')->name('ofertaproductos.destroy');

        Route::resource('clientes','App\Http\Controllers\ClienteController');
        Route::delete('cliente_delete_modal', 'App\Http\Controllers\ClienteController@destroy')->name('clientes.destroy');

        Route::resource('cuentasbancariasclientes','App\Http\Controllers\CuentasbancariasclienteController');
        Route::put('cuentasbancariascliente_edit_modal', 'App\Http\Controllers\CuentasbancariasclienteController@editar')->name('cuentasbancariasclientes.editar');
        Route::delete('cuentasbancariascliente_delete_modal', 'App\Http\Controllers\CuentasbancariasclienteController@destroy')->name('cuentasbancariasclientes.destroy');

        Route::resource('proveedors','App\Http\Controllers\ProveedorController');
        Route::resource('proveedorcuentas','App\Http\Controllers\ProveedorcuentaController');
        Route::put('proveedorcuenta_edit_modal', 'App\Http\Controllers\ProveedorcuentaController@editar')->name('proveedorcuentas.editar');
        Route::delete('proveedorcuenta_delete_modal', 'App\Http\Controllers\ProveedorcuentaController@destroy')->name('proveedorcuentas.destroy');

        Route::put('facturas_cancelar_modal', 'App\Http\Controllers\FacturaController@cancelar')->name('facturas.cancelar');
        Route::put('facturas_firmar_modal', 'App\Http\Controllers\FacturaController@firmar')->name('facturas.firmar');
        Route::put('facturas_pagar_modal', 'App\Http\Controllers\FacturaController@pagar')->name('facturas.pagar');
        Route::get('factura_pdf/{factura}', 'App\Http\Controllers\FacturaController@imprimir')->name('facturas.imprimir');
        Route::put('facturas_importar/{factura}', 'App\Http\Controllers\FacturaController@importar')->name('facturas.importar');
        Route::resource('facturas','App\Http\Controllers\FacturaController');


        Route::delete('facturaelemento_delete_modal', 'App\Http\Controllers\FacturaelementoController@eliminar')->name('facturaelementos.eliminar');
        Route::put('facturaelemento_edit_modal', 'App\Http\Controllers\FacturaelementoController@editar')->name('facturaelementos.editar');
        Route::resource('facturaelementos','App\Http\Controllers\FacturaelementoController');

    });
});





//hasta aqui
// Auth::routes();

// Route::post('users/{id}', function ($id) {

// });

// Route::put('users/{id}', function ($id) {

// });

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
