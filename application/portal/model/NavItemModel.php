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

class NavItemModel extends Model
{
    protected $name='nav_item';
    /***********关联*************/
    public function nav(){
        return $this->belongsTo('NavModel','nav_id');
    }
    /*************存取器**************/
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
    /**获取导航项所实际对应的栏目、单页、内容（该方法为获取器）
     * @param $value
     * @param $data
     * @return $this|null
     */
    public function getItemAttr($value,$data){
        return self::getItemByType($data['type'],$data['item_id']);
    }

    /**获取导航项所实际对应的栏目、单页、内容
     * @param $type
     * @param $itemId
     * @return $this|null
     */
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