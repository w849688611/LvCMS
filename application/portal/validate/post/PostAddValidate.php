<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/23
 * Time: 下午1:19
 */

namespace app\portal\validate\post;


use app\lib\validate\BaseValidate;

class PostAddValidate extends BaseValidate
{
    protected $rule=[
        'title'=>'require'
    ];
    protected $message=[
        'title.require'=>'标题不能为空'
    ];
}