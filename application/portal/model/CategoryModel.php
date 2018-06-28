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

    /**生成栏目树
     * @param $postCategory
     * @param string $tag
     * @param string $tagSuccessValue
     * @param string $tagFailureValue
     * @return array
     */
    public static function generateCategoryTree($postCategory,$tag='checked',$tagSuccessValue='1',$tagFailureValue='0'){
        if(is_array($postCategory)){
            $parentCategory=self::where('parent_id','=','0')->order('list_order','desc')->select()->hidden(['create_time','update_time'])->toArray();
            for($i=0,$len=count($parentCategory);$i<$len;$i++){
                if(self::isCategoryInPostCategory($postCategory,$parentCategory[$i])){
                    $parentCategory[$i][$tag]=$tagSuccessValue;
                }
                else{
                    $parentCategory[$i][$tag]=$tagFailureValue;
                }
                $parentCategory[$i]['children']=self::generateCategoryChildren($postCategory,$tag,$tagSuccessValue,$tagFailureValue,$parentCategory[$i]['id']);
            }
            return $parentCategory;
        }
        return array();
    }

    /**递归生成栏目子树（其实可以和上一个方法合并成一个方法，但是怕以后看不懂）
     * @param $postCategory
     * @param string $tag
     * @param string $tagSuccessValue
     * @param string $tagFailureValue
     * @param $parentId
     * @return array
     */
    public static function generateCategoryChildren($postCategory,$tag='checked',$tagSuccessValue='1',$tagFailureValue='0',$parentId){
        $parentCategory=self::where('parent_id','=',$parentId)->order('list_order','desc')->select()->hidden(['create_time','update_time'])->toArray();
        if(count($parentCategory)==0){
            return array();
        }
        else{
            for($i=0,$len=count($parentCategory);$i<$len;$i++){
                if(self::isCategoryInPostCategory($postCategory,$parentCategory[$i])){
                    $parentCategory[$i][$tag]=$tagSuccessValue;
                }
                else{
                    $parentCategory[$i][$tag]=$tagFailureValue;
                }
                $parentCategory[$i]['children']=self::generateCategoryChildren($postCategory,$tag,$tagSuccessValue,$tagFailureValue,$parentCategory[$i]['id']);
            }
            return $parentCategory;
        }
    }

    /**判断栏目是否为内容所属栏目
     * @param $postCategory
     * @param $category
     * @return bool
     */
    public static function isCategoryInPostCategory($postCategory,$category){
        for($i=0,$len=count($postCategory);$i<$len;$i++){
            if($category['id']==$postCategory[$i]){
                return true;
            }
        }
        return false;
    }

    /**生成树形表格
     * @param $categoryTree
     * @param int $level
     * @return array
     */
    public static function generateCategoryTreeTable($categoryTree,$level=0){
        $result=array();
        for($i=0,$len=count($categoryTree);$i<$len;$i++){
            $categoryTree[$i]['level']=$level;
            $childrenTree=$categoryTree[$i]['children'];
            unset($categoryTree[$i]['children']);
            array_push($result,$categoryTree[$i]);
            if(count($childrenTree)>0){
                $children=self::generateCategoryTreeTable($childrenTree,$level+1);
                $result=array_merge($result,$children);
            }
        }
        return $result;
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
    public function template(){
        return $this->belongsTo('TemplateModel','template_id');
    }
}