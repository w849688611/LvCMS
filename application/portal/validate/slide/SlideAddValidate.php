<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/16
 * Time: 下午8:38
 */

namespace app\portal\validate\slide;


use app\lib\validate\BaseValidate;

class SlideAddValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require'
    ];
    protected $message=[
        'name.require'=>'幻灯片组名字不能为空'
    ];
}