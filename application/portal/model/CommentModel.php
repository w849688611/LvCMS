<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/1
 * Time: 下午9:39
 */

namespace app\portal\model;


use app\portal\enum\CommentStatusEnum;
use think\Model;

class CommentModel extends Model
{
    protected $name='comment';
    protected $autoWriteTimestamp=true;

    public function post(){
        return $this->belongsTo('PostModel','post_id');
    }
    public function user(){
        return $this->belongsTo('app\user\Model\UserModel','user_id');
    }
    public function replyUser(){
        return $this->belongsTo('app\user\Model\UserModel','reply_user_id');
    }

    /**添加评论时计算一下楼层
     * @param $postId
     * @return int
     */
    public static function getFloorForCommentAdd($postId){
        $comment=self::where('post_id','=',$postId)->order('floor','desc')->select()->toArray();
        if(count($comment)>0){
            return $comment[0]['floor']+1;
        }
        return 1;
    }

    /**获取评论树
     * @param $postId 文章id
     * @param bool $flag 是否显示全部评论
     * @param int $page 页码
     * @param int $pageSize 页面大小
     * @return $this|array
     */
    public static function getCommentTree($postId,$flag=false,$page=-1,$pageSize=5){
        $parentComment=CommentModel::where('post_id','=',$postId)
            ->where('parent_id','=','0')->with('user');
        if(!$flag){
            $parentComment->where('status','=',CommentStatusEnum::SHOW);
        }
        if($page!=-1){
            $parentComment=$parentComment->page($page,$pageSize);
        }
        $parentComment=$parentComment->order('create_time','asc')
            ->select()
            ->hidden(['update_time','user.password','user.error_count','user.create_time','user.update_time'])
            ->toArray();
        for($i=0,$len=count($parentComment);$i<$len;$i++){
            $childComment=self::where('post_id','=',$postId)
            ->where('parent_id','=',$parentComment[$i]['id'])
                ->where('status','=',CommentStatusEnum::SHOW)
                ->with('user,replyUser')
                ->order('create_time','asc')
                ->select()
                ->hidden(['update_time','user.password','user.error_count','user.create_time','user.update_time','reply_user.password','reply_user.create_time','reply_user.update_time','reply_user.error_count']);
            $parentComment[$i]['childComment']=$childComment;
        }
        return $parentComment;
    }
}