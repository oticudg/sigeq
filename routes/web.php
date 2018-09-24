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

/**
 * Rutas típicas de autentificación de la app.
 * reemplazando: Auth::routes();
 */
Route::group(['namespace' => 'Auth'], function () {
    // Rutas de Autentificación...
    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login');
        // Rutas de Registro... 
        if (config('frontend.registration_open')) {
            Route::get('registro', 'RegisterController@showRegistrationForm')->name('register');
            Route::post('register', 'RegisterController@register');
        }
    });
    Route::post('logout', 'LoginController@logout')->name('logout');
});
Route::post('app', 'RouteController@dataForTemplate');
Route::get('report', 'ReportController@ficha_insumos');

/**
 * Requieren autentificación.
 */
Route::group(['middleware' => ['auth', 'onlyAjax']], function () {

    /**
     * Admin, Acceso para usuarios con privilegios.
     * "/admin/*"
     */
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin::'], function () {

        // Users Routes...
        Route::resource('users', 'UsersController')->except(['create', 'edit']);
        Route::post('get-data-users', 'UsersController@dataForRegister');
        // Route::get('init-session-user/{id}', 'UsersController@initWithOneUser');

        // Roles Routes...
        Route::resource('roles', 'RolesController')->except(['create', 'edit']);
        Route::post('get-data-roles', 'RolesController@dataForRegister');

        // Permissions Routes...
        Route::resource('permissions', 'PermissionsController')->only(['index', 'show', 'update']);

        // Route::match(['post', 'get'], 'change-module-user', 'UsersController@changeModule');

    });

    Route::resource('history', 'HistoryController')->except(['create', 'edit']);
    Route::post('get-history', 'HistoryController@data');
    Route::resource('ficha', 'FichasController')->except(['create', 'edit']);
    Route::post('get-ficha', 'FichasController@data');
    Route::post('get-specialities', 'FichasController@specialities');
    Route::post('municipalities', 'FichasController@municipalities');
    Route::post('parish', 'FichasController@parishes');
    Route::post('getspeciality', 'FichasController@getspeciality');
    Route::resource('insumoConfig', 'InsumosConfigController')->except(['create', 'edit']);
    Route::resource('insumo', 'InsumosController')->except(['create', 'edit']);
    Route::post('get-insumo', 'InsumosController@data');
    Route::resource('procedure', 'ProcedureController')->except(['create', 'edit']);
    Route::post('get-procedures', 'ProcedureController@data');

    Route::group(['prefix' => '/', 'namespace' => 'Dashboard', 'as' => 'Dashboard::'], function () {
        Route::get('profile', 'ProfileController@show');
        Route::post('change-pass', 'ProfileController@editPassword');
        Route::post('update-user', 'ProfileController@editUser');
    });

    Route::post('admin/app', 'RouteController@canPermission');

});

Route::get('{any?}', 'RouteController@index')->where('any', '.*');
