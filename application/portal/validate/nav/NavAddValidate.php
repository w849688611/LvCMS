<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/15
 * Time: 下午4:05
 */

namespace app\portal\validate\nav;


use app\lib\validate\BaseValidate;

class NavAddValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require'
    ];
    protected $message=[
        'name.require'=>'导航组名称不能为空'
    ];
}