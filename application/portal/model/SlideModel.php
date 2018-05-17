<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/15
 * Time: 下午4:06
 */

namespace app\portal\model;


use think\Model;

class SlideModel extends Model
{
    protected $name='slide';
    /*************存取器**************/
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }

    /**获取幻灯片组下的幻灯片项目
     * @param $id
     * @return array
     */
    public static function generateItem($id){
        $slideItems=SlideItemModel::where('slide_id','=',$id)
            ->order('list_order','desc')
            ->select()->toArray();
        for($i=0,$len=count($slideItems);$i<$len;$i++){
            $slideItems[$i]['item']=SlideItemModel::getItemByType($slideItems[$i]['type'],$slideItems[$i]['item_id']);
        }
        return $slideItems;
    }
}