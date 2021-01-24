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
    $router->post('user/add', 'AdminController@saveAdmin');                //注册
    $router->post('user_role/add', 'AdminController@addRoleUser');         //赋予角色

    $router->post('user/check', 'AdminController@checkLogin');             //登录验证
    $router->post('user/out', 'AdminController@logout');                   //登出
    $router->post('role/get', 'AdminController@getRole');                  //获取用户
    $router->post('permissions/get', 'AdminController@getPermissions');    //获取权限
    $router->post('user_list/get', 'AdminController@getUserList');         //获取用户列表


    $router->get('user/role', 'AdminController@addRole');                  //添加角色
    $router->get('user/info', 'AdminController@addUser');                  //添加用户
    $router->post('user/permission', 'AdminController@addPermission');     //添加权限

    $router->post('excel/add', 'ExcelController@ImportExcel');             //excel导入
    $router->post('excel/get', 'ExcelController@getLabour');               //excel_list
    $router->get('excel/import', 'ExcelController@exportExcel');          //excel导出
    $router->get('excel/uq_import', 'ExcelController@exportUqExcel');          //excel导出



});

Route::get('/index', 'IndexController@index');//主页面
Route::get('login', 'BaseController@login');//登录
Route::get('labourlist', 'IndexController@labourlist');//劳动防护用品列表
Route::get('vis', 'IndexController@vislist');//目视化列表
Route::get('labour_edit', 'IndexController@labour_edit');//修改劳动防护用品
Route::get('labour_add', 'IndexController@labour_add');//添加劳动防护用品
Route::get('excel_add', 'IndexController@excel_add');//excel导入劳动防护用品数据view
Route::post('bc', 'IndexController@testt');//excel导入劳动防护用品数据view
Route::get('useradmin', 'IndexController@user_admin');//用户列表
Route::get('register', 'IndexController@register');//添加用户




