<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/8
 * Time: 下午11:57
 */

namespace app\portal\validate\comment;


use app\lib\validate\BaseValidate;

class CommentAddValidate extends BaseValidate
{
    protected $rule=[
        'post_id'=>'require|positiveInt',
        'parent_id'=>'require|positiveInt|isReply',
        'content'=>'require'

    ];
    protected $message=[
        'post_id.require'=>'文章id不能为空',
        'post_id.positiveInt'=>'文章id必须为有效正整数',
        'parent_id.require'=>'顶级评论id不能为空',
        'parent_id.positiveInt'=>'顶层评论id必须为有效正整数',
        'parent_id.isReply'=>'评论不为顶级评论时，回复评论id不能为空',
//        'reply_id.isReply'=>'回复id不能为空',
//        'reply_user_id.isReply'=>'回复评论作者id不能为空',
        'content.require'=>'评论内容不能为空'
    ];
    public function isReply($value,$rule,$data){
        if($value==0){
            return true;
        }
        else{
            if(array_key_exists('reply_id',$data)&&
                array_key_exists('reply_user_id',$data)&&
                $this->positiveInt($data['reply_id'])&&
                $this->positiveInt($data['reply_user_id'])
            ){
                return true;
            }
            return false;
        }
    }

}