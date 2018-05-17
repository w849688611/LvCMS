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

//后台相关接口
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
Route::rule('api/category/getPostOfCategory','portal/CategoryBase/getPostOfCategory');
/**
 * 内容相关接口
 */
Route::rule('api/post/add','portal/PostBase/add');
Route::rule('api/post/delete','portal/PostBase/delete');
Route::rule('api/post/update','portal/PostBase/update');
Route::rule('api/post/get','portal/PostBase/get');
Route::rule('api/post/getByPage','portal/PostBase/getByPage');
Route::rule('api/post/getCommentOfPost','portal/PostBase/getCommentOfPost');
/**
 * 管理评论相关接口
 */
Route::rule('api/comment/get','portal/CommentBase/get');
Route::rule('api/comment/delete','portal/CommentBase/delete');
/**
 * 单页相关接口
 */
Route::rule('api/single/add','portal/SingleBase/add');
Route::rule('api/single/delete','portal/SingleBase/delete');
Route::rule('api/single/update','portal/SingleBase/update');
Route::rule('api/single/get','portal/SingleBase/get');
Route::rule('api/single/getByPage','portal/SingleBase/getByPage');
/**
 * 模版相关接口
 */
Route::rule('api/template/add','portal/TemplateBase/add');
Route::rule('api/template/delete','portal/TemplateBase/delete');
Route::rule('api/template/update','portal/TemplateBase/update');
Route::rule('api/template/get','portal/TemplateBase/get');
Route::rule('api/template/getCategoryTemplate','portal/TemplateBase/getCategoryTemplate');
Route::rule('api/template/getSingleTemplate','portal/TemplateBase/getSingleTemplate');
Route::rule('api/template/getPostTemplate','portal/TemplateBase/getPostTemplate');
/**
 * 导航相关接口
 */
Route::rule('api/nav/add','portal/NavBase/add');
Route::rule('api/nav/delete','portal/NavBase/delete');
Route::rule('api/nav/update','portal/NavBase/update');
Route::rule('api/nav/get','portal/NavBase/get');
Route::rule('api/nav/getByPage','portal/NavBase/getByPage');
Route::rule('api/nav/geItemOfNav','portal/NavBase/geItemOfNav');
Route::rule('api/nav/item/add','portal/NavItemBase/add');
Route::rule('api/nav/item/delete','portal/NavItemBase/delete');
Route::rule('api/nav/item/update','portal/NavItemBase/update');
Route::rule('api/nav/item/get','portal/NavItemBase/get');
/**
 * 幻灯片相关接口
 */
Route::rule('api/slide/add','portal/SlideBase/add');
Route::rule('api/slide/delete','portal/SlideBase/delete');
Route::rule('api/slide/update','portal/SlideBase/update');
Route::rule('api/slide/get','portal/SlideBase/get');
Route::rule('api/slide/getByPage','portal/SlideBase/getByPage');
Route::rule('api/slide/geItemOfNav','portal/SlideBase/geItemOfSlide');
Route::rule('api/slide/item/add','portal/SlideItemBase/add');
Route::rule('api/slide/item/delete','portal/SlideItemBase/delete');
Route::rule('api/slide/item/update','portal/SlideItemBase/update');
Route::rule('api/slide/item/get','portal/SlideItemBase/get');

/**
 * 管理用户相关接口
 */
Route::rule('api/user/add','user/UserBase/add');
Route::rule('api/user/delete','user/UserBase/delete');
Route::rule('api/user/update','user/UserBase/update');
Route::rule('api/user/get','user/UserBase/get');
Route::rule('api/user/getByPage','user/UserBase/getByPage');
/**
 * 文件相关接口
 */
Route::rule('api/file/add','file/FileBase/add');
Route::rule('api/file/delete','file/FileBase/delete');
Route::rule('api/file/get','file/FileBase/get');
Route::rule('api/file/getByPage','file/FileBase/getByPage');
Route::rule('api/file/getByMd5','file/FileBase/getByMd5');
Route::rule('api/file/getBySha1','file/FileBase/getBySha1');
/**
 * 工具相关接口
 */
Route::rule('api/util/captcha/get','util/CaptchaBase/get');
Route::rule('api/util/captcha/check','util/CaptchaBase/check');
//前台相关接口
/**
 * 前台用户接口
 */
Route::rule('api/front/user/login','user/UserFront/login');
Route::rule('api/front/user/logout','user/UserFront/logout');
Route::rule('api/front/user/checkLogin','user/UserFront/checkLogin');
Route::rule('api/front/user/register','user/UserFront/register');
Route::rule('api/front/user/update','user/UserFront/update');
Route::rule('api/front/user/get','user/UserFront/get');
/**
 * 前端内容相关接口
 */
Route::rule('api/front/portal/getCategory','portal/PortalFront/getCategory');
Route::rule('api/front/portal/getPostOfCategory','portal/PortalFront/getPostOfCategory');
Route::rule('api/front/portal/getPost','portal/PortalFront/getPost');
Route::rule('api/front/portal/getCommentOfPost','portal/PortalFront/getCommentOfPost');
Route::rule('api/front/portal/addComment','portal/PortalFront/addComment');
Route::rule('api/front/portal/deleteComment','portal/PortalFront/deleteComment');
Route::rule('api/front/portal/getNav','portal/PortalFront/getNav');
Route::rule('api/front/portal/getSlide','portal/PortalFront/getSlide');
Route::rule('api/front/portal/getFriendLink','portal/PortalFront/getFriendLink');


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
