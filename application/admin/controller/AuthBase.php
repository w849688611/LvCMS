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
        (new AuthAddValidate())->goCheck();
        $auth->name=$request->param('name');
        $auth->uris=$request->param('uris');
        $auth->parent_id=$request->param('parent_id');
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
            $auth=AuthModel::where('id','=',$id)->find();
            if($auth){
                return ResultService::makeResult(ResultService::Success,'',$auth->toArray());
            }
            else{
                return ResultService::failure('资源不存在');
            }
        }
        else{
            $auths=AuthModel::all();
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
        if($request->has('page')){
            $page=$request->param('page');
            $pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $auth=AuthModel::page($page,$pageSize)->select();
        }
        else{
            $auth=AuthModel::select();
        }
        return ResultService::makeResult(ResultService::Success,'',$auth->toArray());
    }

    /**获取树形权限
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
        $roleId=TokenService::getCurrentVars($request->header('token'),'role');
        $auths=RoleAuthModel::where('role_id','=',$roleId)->field('auth_id')->select()->toArray();
        $roleAuth=array();
        foreach($auths as $item){
            $roleAuth[]=$item['auth_id'];
        }
        return ResultService::makeResult(ResultService::Success,'',AuthModel::generateAuthTree($roleAuth));
        //return ResultService::makeResult(ResultService::Success,'',$roleAuth);
    }
    public function getTreeOfRole(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $roleId=TokenService::getCurrentVars($request->header('token'),'role');
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