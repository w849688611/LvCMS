<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/14
 * Time: 上午10:15
 */

namespace app\portal\model;


use think\Model;

class SingleModel extends Model
{
    protected $autoWriteTimestamp=true;
    protected $name='single';
    /*************存取器**************/
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
    public function template(){
        return $this->belongsTo('TemplateModel','template_id');
    }
}