<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/15
 * Time: 20:50
 */

namespace app\admin\controller;


use app\admin\model\AuthModel;
use app\admin\model\RoleAuthModel;
use app\admin\validate\auth\AuthAddValidate;
use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class AuthBase extends Controller
{
    /**添加资源
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \app\lib\exception\ParamException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new AuthAddValidate())->goCheck();
        $auth=new AuthModel($request->param());
        if($request->has('more')){
            $auth->more=json_decode($request->param('more'),true);
        }
        $auth->allowField(true)->save();
        return ResultService::success('添加资源成功');
    }

    /**删除资源
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \app\lib\exception\ParamException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delete(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $auth=AuthModel::where('id','=',$id)->find();
        if($auth){
            $auth->delete();
            return ResultService::success('删除资源成功');
        }
        else{
            return ResultService::failure('资源不存在');
        }
    }

    /**更新资源
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \app\lib\exception\ParamException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function update(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $auth=AuthModel::where('id','=',$id)->find();
        if($request->has('name')){
            $auth->name=$request->param('name');
        }
        if($request->has('uris')){
            $auth->uris=$request->param('uris');
        }
        if($request->has('parent_id')){
            $auth->parent_id=$request->param('parent_id');
            if($auth->parent_id==$auth->id){
                return ResultService::failure('父级权限不能为自身');
            }
        }
        if($request->has('more')){
            $auth->more=json_decode($request->param('more'),true);
        }
        if($request->has('list_order')){
            $auth->list_order=is_numeric($request->param('list_order'))?$request->param('list_order'):0;
        }
        $auth->save();
        return ResultService::success('更新角色成功');
    }

    /**获取资源
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \app\lib\exception\ParamException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
//            $tag=$request->has('tag')?$request->param('tag'):'checked';//表示权限是否被允许的键名，用于前端生成带复选框的树时使用
//            $tagSuccessValue=$request->has('tagSuccessValue')?$request->param('tagSuccessValue'):'1';//选中项的值
//            $tagFailureValue=$request->has('tagFailureValue')?$request->param('tagSuccessValue'):'0';
            $auth=AuthModel::where('id','=',$id)->find();
            if($auth->parent_id==0){
                $auth->parent_name='一级权限资源';
            }
            else{
                $auth->parent_name=AuthModel::where('id','=',$auth->parent_id)->find()->name;
            }
            if($auth){
                return ResultService::makeResult(ResultService::Success,'',$auth->toArray());
            }
            else{
                return ResultService::failure('资源不存在');
            }
        }
        else{
            $auths=AuthModel::select();
            return ResultService::makeResult(ResultService::Success,'',$auths->toArray());
        }
    }

    /**分页获取资源
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getByPage(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $pageResult=[];
        $auth=AuthModel::select()->toArray();
        $pageResult['total']=count($auth);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $auth=AuthModel::page($page,$pageSize)->select();
        }
        $pageResult['pageData']=$auth;
        return ResultService::makeResult(ResultService::Success,'',$pageResult);
    }

    /**获取树形权限（管理配置用）有roleId则返回该role的权限树（带选择标识），否则返回一个普通权限树（标识为不选）
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getTree(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $tag=$request->has('tag')?$request->param('tag'):'checked';//表示权限是否被允许的键名，用于前端生成带复选框的树时使用
        $tagSuccessValue=$request->has('tagSuccessValue')?$request->param('tagSuccessValue'):'1';
        $tagFailureValue=$request->has('tagFailureValue')?$request->param('tagSuccessValue'):'0';
        if($request->has('roleId')){
            $roleId=$request->param('roleId');
            (new IDPositive())->singleCheck(['id'=>$roleId]);
            $auths=RoleAuthModel::where('role_id','=',$roleId)->field('auth_id')->select()->toArray();
            $roleAuth=array();
            foreach($auths as $item){
                $roleAuth[]=$item['auth_id'];
            }
            return ResultService::makeResult(ResultService::Success,'',AuthModel::generateAuthTree($roleAuth,$tag,$tagSuccessValue,$tagFailureValue));
        }
        else{
            return ResultService::makeResult(ResultService::Success,'',AuthModel::generateAuthTree(array(),$tag,$tagSuccessValue,$tagFailureValue));
        }
    }

    /**获取角色的树形菜单（前端展示用）
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getTreeOfRole(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $roleId=TokenService::getCurrentVars($request->header('token'),'role_id');
        $auths=RoleAuthModel::where('role_id','=',$roleId)->field('auth_id')->select()->toArray();
        $roleAuth=array();
        foreach($auths as $item){
            $roleAuth[]=$item['auth_id'];
        }
        $tree=AuthModel::getAuthTreeOfRole($roleAuth);
        return ResultService::makeResult(ResultService::Success,'',$tree);
    }
    /**获取一级资源
     * @return \think\response\Json
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
      * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getParent(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $parent=AuthModel::where('parent_id','=','0')->select();
        return ResultService::makeResult(ResultService::Success,'',$parent->toArray());
    }

    /**获取子资源
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \app\lib\exception\ParamException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getChildren(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $children=AuthModel::where('parent_id','=',$request->param('id'))->select();
        return ResultService::makeResult(ResultService::Success,'',$children);
    }
}