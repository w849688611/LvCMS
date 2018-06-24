<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/6/14
 * Time: 下午9:52
 */

namespace app\user\model;


use think\Model;

class UserGroupModel extends Model
{
    protected $name='user_group';
    protected $autoWriteTimestamp=true;

    public function user(){
        return $this->hasMany('UserModel','user_group_id');
    }
}