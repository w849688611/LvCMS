<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/25
 * Time: 下午9:51
 */

namespace app\portal\controller;


use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\portal\enum\TypeEnum;
use app\portal\model\CommentModel;
use app\portal\model\PostModel;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

/**后台对评论的操作（查看评论内容，删除评论）
 * Class CommentBase
 * @package app\portal\controller
 */
class CommentBase extends Controller
{
    /**获取评论信息
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $comment=CommentModel::where('id','=',$id)->with(['post','user','replyUser'])->find();
        if($comment){
            return ResultService::makeResult(ResultService::Success,'',$comment->toArray());
        }
        else{
            return ResultService::failure('评论不存在');
        }
    }

    /**更新评论（目前仅更新状态）
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $comment=CommentModel::get($id);
        if($comment){
            if($request->has('status')){
                $comment->status=$request->param('status');
            }
            $comment->save();
            return ResultService::success('更新评论成功');
        }
        else{
            return ResultService::failure('评论不存在');
        }
    }
    /**删除评论
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function delete(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $comment=CommentModel::where('id','=',$id)->find();
        if($comment){
            CommentModel::where('parent_id','=',$comment->id)->delete();
            $comment->delete();
            return ResultService::success('删除评论成功');
        }
        else{
            return ResultService::failure('评论不存在');
        }
    }

    /**搜索评论
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function search(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $keyword='';
        $pageResult=[];
        if($request->has('keyword')){
            $keyword=$request->param('keyword');
        }
        $comments=CommentModel::where('content','like',"%$keyword%")
            ->with(['post','user'])
            ->select()->hidden(['create_time','update_time']);
        $pageResult['total']=count($comments);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $comments=CommentModel::where('content','like',"%$keyword%")->page($page,$pageSize)
                ->with(['post','user'])
                ->select()->hidden(['create_time','update_time']);
        }
        $pageResult['pageData']=$comments;
        return ResultService::success('',$pageResult);
    }
}