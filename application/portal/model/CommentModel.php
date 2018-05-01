<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/1
 * Time: 下午9:39
 */

namespace app\portal\model;


use think\Model;

class CommentModel extends Model
{
    protected $name='comment';
    protected $autoWriteTimestamp=true;

    public function post(){
        return $this->belongsTo('PostModel','post_id');
    }
    public function user(){
        return $this->belongsTo('UserModel','user_id');
    }
}