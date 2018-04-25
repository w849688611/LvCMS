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

/**
 * 管理员相关接口
 */
Route::rule('api/admin/add','admin/AdminBase/add');
Route::rule('api/admin/delete','admin/AdminBase/delete');
Route::rule('api/admin/update','admin/AdminBase/update');
Route::rule('api/admin/get','admin/AdminBase/get');
Route::rule('api/admin/getByPage','admin/AdminBase/getByPage');
Route::rule('api/admin/login','admin/AdminBase/login');
Route::rule('api/admin/logout','admin/AdminBase/logout');
Route::rule('api/admin/checkLogin','admin/AdminBase/checkLogin');
/**
 * 管理员角色相关接口
 */
Route::rule('api/role/add','admin/RoleBase/add');
Route::rule('api/role/delete','admin/RoleBase/delete');
Route::rule('api/role/update','admin/RoleBase/update');
Route::rule('api/role/get','admin/RoleBase/get');
Route::rule('api/role/getByPage','admin/RoleBase/getByPage');
Route::rule('api/role/bindAuth','admin/RoleBase/bindAuth');
Route::rule('api/role/checkRoleOwnAuth','admin/RoleBase/checkRoleOwnAuth');
/**
 * 管理员权限相关接口
 */
Route::rule('api/auth/add','admin/AuthBase/add');
Route::rule('api/auth/delete','admin/AuthBase/delete');
Route::rule('api/auth/update','admin/AuthBase/update');
Route::rule('api/auth/get','admin/AuthBase/get');
Route::rule('api/auth/getByPage','admin/AuthBase/getByPage');
Route::rule('api/auth/getTree','admin/AuthBase/getTree');
Route::rule('api/auth/getParent','admin/AuthBase/getParent');
Route::rule('api/auth/getChildren','admin/AuthBase/getChildren');
Route::rule('api/auth/getTreeOfRole','admin/AuthBase/getTreeOfRole');
/**
 * 栏目相关接口
 */
Route::rule('api/category/add','portal/CategoryBase/add');
Route::rule('api/category/delete','portal/CategoryBase/delete');
Route::rule('api/category/update','portal/CategoryBase/update');
Route::rule('api/category/get','portal/CategoryBase/get');
Route::rule('api/category/getTree','portal/CategoryBase/getTree');
/**
 * 内容相关接口
 */
Route::rule('api/post/add','portal/PostBase/add');
Route::rule('api/post/delete','portal/PostBase/delete');
Route::rule('api/post/update','portal/PostBase/update');
Route::rule('api/post/get','portal/PostBase/get');
Route::rule('api/post/getByPage','portal/PostBase/getByPage');

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
