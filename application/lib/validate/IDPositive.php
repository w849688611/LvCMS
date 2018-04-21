<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/9
 * Time: 17:08
 */

namespace app\lib\validate;


class IDPositive extends BaseValidate
{
    protected $rule=[
        'id'=>'require|positiveInt'
    ];
    protected $message= [
        'id.require'=>'id不能为空',
        'id.positiveInt'=>'id必须为有效正整数'
    ];
}