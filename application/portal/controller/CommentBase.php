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
use app\portal\model\CommentModel;
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
        $comment=CommentModel::where('id','=',$id)->with(['post','user'])->find();
        if($comment){
            return ResultService::makeResult(ResultService::Success,'',$comment->toArray());
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
            $comment->delete();
            return ResultService::success('删除评论成功');
        }
        else{
            return ResultService::failure('评论不存在');
        }
    }
}