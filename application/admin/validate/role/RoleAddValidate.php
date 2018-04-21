<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/15
 * Time: 19:08
 */

namespace app\admin\validate\role;


use app\lib\validate\BaseValidate;

class RoleAddValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require'
    ];
    protected $message=[
        'name.require'=>'角色名称不能为空'
    ];
}