<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    //'middleware' => [],
], function ($router) {
    $router->get('user/role', 'AdminController@addRole');                //添加角色
    $router->get('user/info', 'AdminController@addUser');                //添加用户
    $router->get('user/permission', 'AdminController@addPermission');    //添加权限

    $router->post('excel/add', 'ExcelController@ImportExcel');           //excel导入

});

