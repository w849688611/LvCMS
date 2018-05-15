<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/14
 * Time: 上午10:14
 */

namespace app\portal\validate\single;


use app\lib\validate\BaseValidate;

class SingleAddValidate extends BaseValidate
{
    protected $rule=[
        'title'=>'require'
    ];
    protected $message=[
        'title.require'=>'标题不能为空'
    ];
}