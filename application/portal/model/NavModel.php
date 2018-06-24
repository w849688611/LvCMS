<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/15
 * Time: 下午4:06
 */

namespace app\portal\model;


use think\Model;

class NavModel extends Model
{
    protected $name='nav';
    /***********关联*************/
    public function item(){
        return $this->hasMany('NavItemModel','nav_id');
    }
    /*************存取器**************/
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
    /**生成导航树
     * @param int $id
     * @return array
     */
    public static function generateItemTree($id=0){
        $items=NavItemModel::where('nav_id','=',$id)
            ->where('parent_id','=','0')
            ->order('list_order','desc')
            ->select()->toArray();
        for($i=0,$len=count($items);$i<$len;$i++){
            $items[$i]['item']=NavItemModel::getItemByType($items[$i]['type'],$items[$i]['item_id']);
            $items[$i]['children']=self::generateItemChildren($id,$items[$i]['id']);
        }
        return $items;
    }

    /**递归获取导航项的子项
     * @param $id
     * @param $parentId
     * @return array
     */
    public static function generateItemChildren($id,$parentId){
        $parentItems=NavItemModel::where('nav_id','=',$id)
            ->where('parent_id','=',$parentId)
            ->order('list_order','desc')
            ->select()->toArray();
        if(!count($parentItems)==0){
            for($i=0,$len=count($parentItems);$i<$len;$i++){
                $parentItems[$i]['item']=NavItemModel::getItemByType($parentItems[$i]['type'],$parentItems[$i]['item_id']);
                $parentItems[$i]['children']=self::generateItemChildren($id,$parentItems[$i]['id']);
            }
        }
        return $parentItems;
    }
}