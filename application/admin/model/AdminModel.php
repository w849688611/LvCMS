<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 12:24
 */

namespace app\admin\model;


use think\Model;

class AdminModel extends Model
{
    protected $autoWriteTimestamp=true;
    protected $name='admin';
    public function setPasswordAttr($value){
        return md5(config('security.salt').$value);
    }
    public function getMoreAttr($value){
        return json_decode($value,true);
    }
    public function setMoreAttr($value){
        return json_encode($value);
    }
    public function role(){
        return $this->belongsTo('RoleModel','role_id');
    }
    public function checkPassword($password){
        if(md5(config('security.salt').$password)==$this->password){
            return true;
        }
        return false;
    }
}