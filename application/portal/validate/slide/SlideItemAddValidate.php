<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/16
 * Time: 下午10:35
 */

namespace app\portal\validate\slide;


use app\lib\validate\BaseValidate;
use app\portal\enum\TypeEnum;

class SlideItemAddValidate extends BaseValidate
{
    protected $rule=[
        'slide_id'=>'require|positiveInt',
        'type'=>'require|typeValid',
        'item_id'=>'require|positiveInt',
        'list_order'=>'positiveInt'
    ];
    protected $message=[
        'slide_id.require'=>'幻灯片组id不能为空',
        'slide_id.positiveInt'=>'幻灯片组id必须为有效正整数',
        'type.require'=>'导航类型不能为空',
        'type.typeValid'=>'导航类型不合法',
        'item_id.require'=>'导航内容id不能为空',
        'item_id.positiveInt'=>'导航内容id必须为有效正整数',
        'list_order.positiveInt'=>'序号必须为有效正整数'
    ];
    public function typeValid($value){
        if($value==TypeEnum::CATEGORY||$value==TypeEnum::SINGLE||$value==TypeEnum::POST||$value==TypeEnum::LINK){
            return true;
        }
        return false;
    }
}