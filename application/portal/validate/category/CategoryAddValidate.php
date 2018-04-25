<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/22
 * Time: 下午9:23
 */

namespace app\portal\validate\category;


use app\lib\validate\BaseValidate;

class CategoryAddValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require',
        'parent_id'=>'require|positiveInt'
    ];
    protected $message=[
        'name.require'=>'栏目名称不能为空',
        'parent_id.require'=>'父级栏目不能为空',
        'parent_id.positiveInt'=>'父级栏目id必须为有效正整数'
    ];
}