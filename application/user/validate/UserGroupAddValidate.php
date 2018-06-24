<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/6/14
 * Time: 下午9:51
 */

namespace app\user\validate;


use app\lib\validate\BaseValidate;

class UserGroupAddValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require'
    ];
    protected $message=[
        'name.require'=>'用户组名称不能为空'
    ];
}