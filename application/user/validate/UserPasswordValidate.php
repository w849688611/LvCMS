<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/10
 * Time: 下午1:43
 */

namespace app\user\validate;


use app\lib\validate\BaseValidate;

class UserPasswordValidate extends BaseValidate
{
    protected $rule=[
        'password'=>'min:6'
    ];
    protected $message=[
        'password.min'=>'密码至少6位'
    ];
}