<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/1
 * Time: 下午9:18
 */

namespace app\user\validate;


use app\lib\validate\BaseValidate;
use app\user\enum\UserStatusEnum;

class UserStatusValidate extends BaseValidate
{
    protected $rule=[
        'status'=>'statusValid'
    ];
    protected $message=[
        'status'=>'账号状态不合法'
    ];
    public function statusValid($value){
        if($value==UserStatusEnum::LOCK||$value==UserStatusEnum::NORMAL||$value==UserStatusEnum::BAN){
            return true;
        }
        return false;
    }
}