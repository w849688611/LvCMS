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
use app\portal\model\CategoryModel;
use app\portal\model\CategoryPostModel;
use app\portal\validate\category\CategoryAddValidate;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class CategoryBase extends Controller
{
    /**添加栏目
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new CategoryAddValidate())->goCheck();
        $category=new CategoryModel($request->param());
        if($request->has('content')){
            $category->content=$request->param('content','','htmlspecialchars_decode');
        }
        if($request->has('more')){
            $category->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        }
        $category->allowField(true)->save();
        return ResultService::success('添加栏目成功');
    }

    /**删除栏目
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
        $category=CategoryModel::where('id','=',$id)->find();
        if($category){
            $category->delete();
            return ResultService::success('删除成功');
        }
        else{
            return ResultService::failure('栏目不存在');
        }
    }

    /**更新栏目
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
        $category=CategoryModel::where('id','=',$id)->find();
        if($category){
            if($request->has('name')){
                $category->name=$request->param('name');
            }
            if($request->has('excerpt')){
                $category->excerpt=$request->param('excerpt');
            }
            if($request->has('content')){
                $category->content=$request->param('content','','htmlspecialchars_decode');
            }
            if($request->has('thumbnail')){
                $category->thumbnail=$request->param('thumbnail');
            }
            if($request->has('more')){
                $category->more=$request->param('more','','json_decode');
            }
            if($request->has('parent_id')){
                if($request->param('parent_id')==$category->id){
                    return ResultService::failure('父级栏目不能为自身');
                }
                $category->parent_id=$request->param('parent_id');
            }
            if($request->has('template_id')){
                $category->template_id=$request->param('template_id');
            }
            $category->save();
            return ResultService::success('更新栏目成功');
        }
        else{
            return ResultService::failure('栏目不存在');
        }
    }

    /**根据id获取栏目
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
            $category=CategoryModel::where('id','=',$id)->with('template')->find();
            if($category){
                $category->hidden(['create_time','update_time','template.create_time','template_update_time']);
                return ResultService::makeResult(ResultService::Success,'',$category->toArray());
            }
            else{
                return ResultService::failure('栏目不存在');
            }
        }
        else{
            $categories=CategoryModel::with('template')->select()->hidden(['create_time','update_time','template.create_time','template_update_time']);
            return ResultService::makeResult(ResultService::Success,'',$categories->toArray());
        }
    }

    /**获取栏目树
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getTree(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $tag=$request->has('tag')?$request->param('tag'):'checked';//表示权限是否被允许的键名，用于前端生成带复选框的树时使用
        $tagSuccessValue=$request->has('tagSuccessValue')?$request->param('tagSuccessValue'):'1';
        $tagFailureValue=$request->has('tagFailureValue')?$request->param('tagSuccessValue'):'0';
        if($request->has('postId')){
            $postId=$request->param('postId');
            (new IDPositive())->singleCheck(['id'=>$postId]);
            $categories=CategoryPostModel::where('post_id','=',$postId)->field('category_id')->select()->toArray();
            $postCategory=array();
            foreach($categories as $item){
                $postCategory[]=$item['category_id'];
            }
            return ResultService::makeResult(ResultService::Success,'',CategoryModel::generateCategoryTree($postCategory,$tag,$tagSuccessValue,$tagFailureValue));
        }
        else{
            //var_dump(CategoryModel::generateCategoryTree(array(),$tag,$tagSuccessValue,$tagFailureValue));
            return ResultService::makeResult(ResultService::Success,'',CategoryModel::generateCategoryTree(array(),$tag,$tagSuccessValue,$tagFailureValue));
        }
    }

    /**获取栏目下内容
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getPostOfCategory(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $category=CategoryModel::where('id','=',$id)->find();
        $posts=[];
        if($category){
            $posts=$category->post;
            $posts->hidden(['create_time','update_time','delete_time','pivot']);
        }
        return ResultService::makeResult(ResultService::Success,'',$posts);
    }
}