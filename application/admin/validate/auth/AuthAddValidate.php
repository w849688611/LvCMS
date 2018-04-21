<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/17
 * Time: 23:46
 */

namespace app\admin\validate\auth;


use app\lib\validate\BaseValidate;

class AuthAddValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require',
        'uris'=>'require',
        'parent_id'=>'require|positiveInt'
    ];
    protected $message=[
        'name.require'=>'资源名称不能为空',
        'uris.require'=>'资源路径不能为空',
        'parent_id.require'=>'父级id不能为空',
        'parent_id.positiveInt'=>'父级id必须为有效正整数'
    ];
}