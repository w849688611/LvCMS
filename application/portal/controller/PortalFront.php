<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/25
 * Time: 下午9:50
 */

namespace app\portal\controller;


use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\portal\enum\PostStatusEnum;
use app\portal\enum\SingleStatusEnum;
use app\portal\model\CategoryModel;
use app\portal\model\CommentModel;
use app\portal\model\PostModel;
use app\portal\model\SingleModel;
use app\portal\validate\comment\CommentAddValidate;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Hook;
use think\Request;

/**专门用于前端数据获取（非管理端）
 * Class PortalFront
 * @package app\portal\controller
 */
class PortalFront extends Controller
{
    /**根据id获取栏目相关信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function getCategory(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $category=CategoryModel::where('id','=',$id)->with('template')->find();
        if($category){
            return ResultService::makeResult(ResultService::Success,'',$category->toArray());
        }
        else{
            return ResultService::failure('栏目不存在');
        }
    }

    /**根据栏目id获取栏目下内容，需要经过筛选，剔除不可见内容，检测是否有page参数，若有则分页返回，若无则返回全部
     * @param Request $request
     * @return \think\response\Json
     */
    public function getPostOfCategory(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $category=CategoryModel::where('id','=',$id)->find();
        $posts=[];
        if($request->has('page')){
            $page=$request->param('page');
            $pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            if($category){
                $posts=$category->post()->where('post_status','=',PostStatusEnum::NORMAL)->with('template')->page($page,$pageSize)->select();
            }
        }
        else{
            $posts=$category->post()->where('post_status','=',PostStatusEnum::NORMAL)->with('template')->select();
        }
        return ResultService::makeResult(ResultService::Success,'',$posts->toArray());
    }

    /**根据id获取post，若内容不可见则返回不可见
     * 设置了before_get_post,after_get_post两个钩子
     * @param Request $request
     * @return \think\response\Json
     */
    public function getPost(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        Hook::listen('before_get_post',$id);
        $post=PostModel::where('id','=',$id)->where('post_status','=',PostStatusEnum::NORMAL)
            ->with('template')->find();
        if($post){
            Hook::listen('after_get_post',$post);
            return ResultService::makeResult(ResultService::Success,'',$post);
        }
        return ResultService::failure('内容不存在');
    }

    /**获取内容的评论，根据是否含有页数来决定是否分页
     * @param Request $request
     * @return \think\response\Json
     */
    public function getCommentOfPost(Request $request){
        (new IDPositive())->goCheck();
        $postId=$request->param('id');
        if($request->has('page')){
            $page=$request->param('page');
            $pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $comments=CommentModel::getCommentTree($postId,false,$page,$pageSize);
        }
        else{
            $comments=CommentModel::getCommentTree($postId,false);
        }
        return ResultService::makeResult(ResultService::Success,'',$comments);
    }

    /**用户发表评论
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function addComment(Request $request){
        if(!TokenService::validUserToken($request->header('token'))){
            throw new TokenException();
        }
        (new CommentAddValidate())->goCheck();
        $userId=TokenService::getCurrentVars($request->header('token'),'id');
        $comment=new CommentModel($request->param());
        $comment->content=$request->param('content','','htmlspecialchars_decode');
        $comment->user_id=$userId;
        if($comment->parent_id==0){
            $comment->floor=CommentModel::getFloorForCommentAdd($comment->post_id);
        }
        else{
            $comment->floor=-1;
        }
        $comment->allowField(true)->save();
        return ResultService::success('评论成功');
    }

    /**用户删除评论
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function deleteComment(Request $request){
        if(!TokenService::validUserToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $userId=TokenService::getCurrentVars($request->header('token'),'id');
        $comment=CommentModel::where('id','=',$id)->find();
        if($comment){
            if($comment->user_id==$userId){
                CommentModel::where('parent_id','=',$id)->delete();
                $comment->delete();
                return ResultService::success('删除评论成功');
            }
            else{
                return ResultService::failure('评论与用户不匹配');
            }
        }
        else{
            return ResultService::failure('评论不存在');
        }
    }

    /**获取单页
     * @param Request $request
     * @return \think\response\Json
     */
    public function getSingle(Request $request){
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        Hook::listen('before_get_single',$id);
        $single=SingleModel::where('id','=',$id)->where('status','=',SingleStatusEnum::NORMAL)
            ->with('template')->find();
        if($single){
            Hook::listen('after_get_single',$single);
            return ResultService::makeResult(ResultService::Success,'',$single);
        }
        return ResultService::failure('该页面不存在');
    }
    public function getNav(Request $request){

    }
    public function getSlide(Request $request){

    }
    public function getFriendLink(Request $request){

    }
}