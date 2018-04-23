<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/22
 * Time: 下午9:58
 */

namespace app\portal\model;


use think\Model;

class PostModel extends Model
{
    protected $name='post';
    protected $autoWriteTimestamp=true;

    /*************关联**************/
    public function category(){
        return $this->belongsToMany('CategoryModel','category_post','category_id','post_id');
    }
}