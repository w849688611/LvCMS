<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/15
 * Time: 下午4:06
 */

namespace app\portal\model;


use app\portal\enum\TypeEnum;
use think\Model;

class SlideItemModel extends Model
{
    protected $name='slide_item';
    public function slide(){
        return $this->belongsTo('SlideModel','slide_id');
    }
    /*************存取器**************/
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
    public function getItemAttr($value,$data){
        return self::getItemByType($data['type'],$data['item_id']);
    }
    public static function getItemByType($type,$itemId){
        switch ($type){
            case TypeEnum::CATEGORY:
                $item=CategoryModel::where('id','=',$itemId)->with('template')
                    ->find();
                break;
            case TypeEnum::SINGLE:
                $item=SingleModel::where('id','=',$itemId)->with('template')
                    ->find();
                break;
            case TypeEnum::POST:
                $item=PostModel::where('id','=',$itemId)->with('template')
                    ->find();
                break;
            default:
                $item=null;
        }
        if(!is_null($item)){
            $item->hidden(['create_time','update_time']);
        }
        return $item;
    }
}