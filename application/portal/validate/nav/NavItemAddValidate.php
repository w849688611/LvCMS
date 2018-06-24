<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/15
 * Time: 下午4:05
 */

namespace app\portal\validate\nav;


use app\lib\validate\BaseValidate;
use app\portal\enum\TypeEnum;

class NavItemAddValidate extends BaseValidate
{
    protected $rule=[
        'nav_id'=>'require|positiveInt',
        'parent_id'=>'require|positiveInt',
        'type'=>'require|typeValid',
        'item_id'=>'require|positiveInt'
    ];
    protected $message=[
        'nav_id.require'=>'导航组id不能为空',
        'nav_id.positiveInt'=>'导航组id必须为有效正整数',
        'parent_id.require'=>'父导航id不能为空',
        'parent_id.positiveInt'=>'父导航id必须为有效正整数',
        'type.require'=>'导航类型不能为空',
        'type.typeValid'=>'导航类型不合法',
        'item_id.require'=>'导航内容id不能为空',
        'item_id.positiveInt'=>'导航内容id必须为有效正整数',
    ];
    public function typeValid($value){
        if($value==TypeEnum::CATEGORY||$value==TypeEnum::SINGLE||$value==TypeEnum::POST||$value==TypeEnum::LINK){
            return true;
        }
        return false;
    }
}