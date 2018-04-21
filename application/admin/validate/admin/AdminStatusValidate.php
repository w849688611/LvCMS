<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/9
 * Time: 18:22
 */

namespace app\admin\validate\admin;


use app\admin\enum\AdminStatusEnum;
use app\lib\validate\BaseValidate;

class AdminStatusValidate extends BaseValidate
{
    protected $rule=[
        'status'=>'statusValid'
    ];
    protected $message=[
        'status'=>'账号状态不合法'
    ];
    public function statusValid($value){
        if($value==AdminStatusEnum::LOCK||$value==AdminStatusEnum::NORMAL||$value==AdminStatusEnum::BAN){
            return true;
        }
        return false;
    }
}