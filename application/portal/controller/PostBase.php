<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/20
 * Time: 16:50
 */

namespace app\portal\controller;


use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\portal\model\CategoryPostModel;
use app\portal\model\PostModel;
use app\portal\validate\post\PostAddValidate;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class PostBase extends Controller
{
    /**添加内容
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new PostAddValidate())->goCheck();
        $post=new PostModel($request->param());
        $post->user_id=TokenService::getCurrentVars($request->header('token'),'id');
        if($request->has('content')){
            $post->content=$request->param('content','','htmlspecialchars_decode');
        }
        if($request->has('more')){
            $post->more=$request->param('more','','htmlspecialchars_decode,json_decode');
        }
        $post->allowField(true)->save();
        if($request->has('category')){
            $category=json_decode(htmlspecialchars_decode($request->param('category')),true);
            $categoryIds=array();
            for($i=0,$len=count($category);$i<$len;$i++){
                $categoryIds[]=$category[$i]['id'];
            }
            $post->category()->save($categoryIds);
        }
        return ResultService::success('添加内容成功');
    }

    /**删除内容
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
        $post=PostModel::where('id','=',$id)->find();
        if($post){
            CategoryPostModel::where('post_id','=',$post->id)->delete();
            $post->delete();
            return ResultService::success('删除内容成功');
        }
        else{
            return ResultService::failure('内容不存在');
        }
    }

    /**更新内容
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
        $post=PostModel::where('id','=',$id)->find();
        if($post){
            if($request->has('post_status')){
                $post->post_status=$request->param('post_status');
            }
            if($request->has('comment_status')){
                $post->comment_status=$request->param('comment_status');
            }
            if($request->has('is_top')){
                $post->is_top=$request->param('is_top');
            }
            if($request->has('is_recommend')){
                $post->is_recommend=$request->param('is_recommend');
            }
            if($request->has('published_time')){
                $post->published_time=$request->param('published_time');
            }
            if($request->has('title')){
                $post->title=$request->param('title');
            }
            if($request->has('author')){
                $post->author=$request->param('author');
            }
            if($request->has('keywords')){
                $post->keywords=$request->param('keywords');
            }
            if($request->has('excerpt')){
                $post->excerpt=$request->param('excerpt');
            }
            if($request->has('source')){
                $post->source=$request->param('source');
            }
            if($request->has('content')){
                $post->content=$request->param('content','','htmlspecialchars_decode');
            }
            if($request->has('more')){
                $post->more=$request->param('more','','htmlspecialchars_decode,json_decode');
            }
            $post->save();
            return ResultService::success('更新内容成功');
        }
        else{
            return ResultService::failure('内容不存在');
        }
    }

    /**获取内容
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $post=PostModel::where('id','=',$id)->with('category')->find();
            if($post){
                $post->hidden(['create_time','update_time','category.create_time','category.update_time','category.pivot']);
                return ResultService::makeResult(ResultService::Success,'',$post->toArray());
            }
            else{
                return ResultService::failure('内容不存在');
            }
        }
        else{
            $posts=PostModel::with('category')->select();
            $posts->hidden(['create_time','update_time','category.create_time','category.update_time','category.pivot']);
            return ResultService::makeResult(ResultService::Success,'',$posts->toArray());
        }
    }

    /**分页获取内容
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getByPage(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        if($request->has('page')){
            $page=$request->param('page');
            $pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $posts=PostModel::page($page,$pageSize)->with('category')->select();
            $posts->hidden(['create_time','update_time','category.create_time','category.update_time','category.pivot']);
        }
        else{
            $posts=PostModel::with('category')->select();
            $posts->hidden(['create_time','update_time','category.create_time','category.update_time','category.pivot']);
        }
        return ResultService::makeResult(ResultService::Success,'',$posts->toArray());
    }
    public function getCommentOfPost(Request $request){

    }
}