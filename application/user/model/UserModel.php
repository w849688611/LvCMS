<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/30
 * Time: 上午12:27
 */

namespace app\user\model;


use think\Model;

class UserModel extends Model
{
    protected $name='user';
    protected $autoWriteTimestamp=true;

    public function setPasswordAttr($value){
        return md5(config('security.salt').$value);
    }
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
}