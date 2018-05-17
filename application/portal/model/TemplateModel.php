<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/14
 * Time: 上午10:23
 */

namespace app\portal\model;


use think\Model;

class TemplateModel extends Model
{
    protected $autoWriteTimestamp=true;
    protected $name='template';

    public static function getByType($type){
        return self::where('type','=',$type)->select();
    }
    /*************存取器**************/
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
}