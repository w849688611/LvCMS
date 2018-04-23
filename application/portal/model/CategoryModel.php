<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/22
 * Time: 下午9:32
 */

namespace app\portal\model;


use think\Model;

class CategoryModel extends Model
{
    protected $name='category';
    protected $autoWriteTimestamp=true;

    public static function generateCategoryTree($postCategory,$tag='checked',$tagSuccessValue='1',$tagFailureValue='0'){
        if(is_array($postCategory)){
            $parentCategory=self::where('parent_id','=','0')->select()->toArray();
            for($i=0,$len=count($parentCategory);$i<$len;$i++){
                if(self::isCategoryInPostCategory($postCategory,$parentCategory[$i])){
                    $parentCategory[$tag]=$tagSuccessValue;
                }
                else{
                    $parentCategory[$tag]=$tagFailureValue;
                }
                $parentCategory[$i]['children']=self::generateCategoryChildren($postCategory,$tag,$tagSuccessValue,$tagFailureValue,$parentCategory[$i]['id']);
            }
            return $parentCategory;
        }
        return array();
    }
    public static function generateCategoryChildren($postCategory,$tag='checked',$tagSuccessValue='1',$tagFailureValue='0',$parentId){
        $parentCategory=self::where('parent_id','=',$parentId)->select()->toArray();
        if(count($parentCategory)==0){
            return array();
        }
        else{
            for($i=0,$len=count($postCategory);$i<$len;$i++){
                if(self::isCategoryInPostCategory($postCategory,$parentCategory[$i])){
                    $parentAuth[$i][$tag]=$tagSuccessValue;
                }
                else{
                    $parentAuth[$i][$tag]=$tagFailureValue;
                }
                $parentAuth[$i]['children']=self::generateCategoryChildren($postCategory,$tag,$tagSuccessValue,$tagFailureValue,$parentAuth[$i]['id']);
            }
            return $parentCategory;
        }
    }
    public static function isCategoryInPostCategory($postCategory,$category){
        for($i=0,$len=count($postCategory);$i<$len;$i++){
            if($category['id']==$postCategory[$i]){
                return true;
            }
        }
        return false;
    }
    /*************存取器**************/
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
    /*************关联**************/
    public function post(){
        return $this->belongsToMany('PostModel','category_post','post_id','category_id');
    }
}