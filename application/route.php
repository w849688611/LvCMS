<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
use think\Hook;
Route::rule('api/admin/add','admin/AdminBase/add');
Route::rule('api/admin/delete','admin/AdminBase/delete');
Route::rule('api/admin/update','admin/AdminBase/update');
Route::rule('api/admin/get','admin/AdminBase/get');
Route::rule('api/admin/getByPage','admin/AdminBase/getByPage');
Route::rule('api/admin/login','admin/AdminBase/login');
Route::rule('api/admin/logout','admin/AdminBase/logout');
Route::rule('api/admin/checkLogin','admin/AdminBase/checkLogin');

Route::rule('api/role/add','admin/RoleBase/add');
Route::rule('api/role/delete','admin/RoleBase/delete');
Route::rule('api/role/update','admin/RoleBase/update');
Route::rule('api/role/get','admin/RoleBase/get');
Route::rule('api/role/getByPage','admin/RoleBase/getByPage');
Route::rule('api/role/bindAuth','admin/RoleBase/bindAuth');
Route::rule('api/role/checkRoleOwnAuth','admin/RoleBase/checkRoleOwnAuth');

Route::rule('api/auth/add','admin/AuthBase/add');
Route::rule('api/auth/delete','admin/AuthBase/delete');
Route::rule('api/auth/update','admin/AuthBase/update');
Route::rule('api/auth/get','admin/AuthBase/get');
Route::rule('api/auth/getByPage','admin/AuthBase/getByPage');
Route::rule('api/auth/getTree','admin/AuthBase/getTree');
Route::rule('api/auth/getParent','admin/AuthBase/getParent');
Route::rule('api/auth/getChildren','admin/AuthBase/getChildren');
Route::rule('api/auth/getTreeOfRole','admin/AuthBase/getTreeOfRole');

Hook::listen('plugin_route');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
