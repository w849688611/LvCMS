<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/9
 * Time: 18:22
 */

namespace app\admin\validate\admin;


use app\lib\validate\BaseValidate;

class AdminPasswordValidate extends BaseValidate
{
    protected $rule=[
        'password'=>'min:6'
    ];
    protected $message=[
        'password.min'=>'密码至少6位'
    ];
}