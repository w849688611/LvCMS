<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/14
 * Time: 下午1:04
 */

namespace app\portal\validate\template;


use app\lib\validate\BaseValidate;
use app\portal\enum\TypeEnum;

class TemplateAddValidate extends BaseValidate
{
    protected $rule=[
        'url'=>'require',
        'name'=>'require',
        'type'=>'require|TypeValid'
    ];
    protected $message=[];

    public function TypeValid($value){
        if($value==TypeEnum::CATEGORY||$value==TypeEnum::SINGLE||$value==TypeEnum::POST){
            return true;
        }
        return false;
    }
}