<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/15
 * Time: 18:57
 */

namespace app\admin\controller;


use app\admin\model\AuthModel;
use app\admin\model\RoleAuthModel;
use app\admin\model\RoleModel;
use app\admin\validate\role\RoleAddValidate;
use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Db;
use think\Request;

class RoleBase extends Controller
{
    /**添加角色
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \app\lib\exception\ParamException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new RoleAddValidate())->goCheck();
        $role=new RoleModel($request->param());
        $role->allowField(true)->save();
        return ResultService::success('添加角色成功');
    }
    /**删除角色
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
        $role=RoleModel::where('id','=',$id)->find();
        if($role){
            if(count($role->admin)>0){
                return ResultService::failure('角色下仍有用户，无法删除');
            }
            RoleAuthModel::where('role_id','=',$role->id)->delete();
            $role->delete();
            return ResultService::success('删除角色成功');
        }
        else{
            return ResultService::failure('角色不存在');
        }
    }
    /**更新角色
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
        $role=RoleModel::where('id','=',$id)->find();
        if($request->has('name')){
            $role->name=$request->param('name');
        }
        $role->save();
        return ResultService::success('更新角色成功');
    }
    /**获取角色
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
            $role=RoleModel::where('id','=',$id)->with('auth')->find();
            if($role){
                return ResultService::makeResult(ResultService::Success,'',$role->toArray());
            }
            else{
                return ResultService::failure('角色不存在');
            }
        }
        else{
            $roles=RoleModel::select();
            return ResultService::makeResult(ResultService::Success,'',$roles->toArray());
        }
    }
    /**获取角色分页
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getByPage(Request $request){
        if(!TokenService::validToken($request->header('token'))){
            throw new TokenException();
        }
        $pageResult=[];
        $role=RoleModel::select()->toArray();
        $pageResult['total']=count($role);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $role=RoleModel::page($page,$pageSize)->select();
        }
        $pageResult['pageData']=$role;
        return ResultService::makeResult(ResultService::Success,'',$pageResult);
    }

    /**绑定权限
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \app\lib\exception\ParamException
     */
    public function bindAuth(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        if($request->has('auths')){
            $data=array();
            $roleId=$request->param('id');
            //$auths=json_decode(htmlspecialchars($request->param('auths')),true);
            $auths=json_decode($request->param('auths'),true);
            $roleAuthModel=new RoleAuthModel();
            $roleAuthModel->where('role_id','=',$roleId)->delete();
            foreach($auths as $auth){
                if(isset($auth['id'])){
                    $data[]=[
                        'role_id'=>$roleId,
                        'auth_id'=>$auth['id']
                    ];
                }
            }
            $roleAuthModel->saveAll($data);
        }
        return ResultService::success('绑定成功');
    }

    /**检查是否有权访问某一页面
     * @param Request $request
     * @return \think\response\Json
     */
    public function checkRoleOwnAuth(Request $request){
        $roleId=0;
        //带token来的，从token中取出roleId
        if(TokenService::validAdminToken($request->header('token'))){
            $token=$request->header('token');
            if(TokenService::getCurrentVars($token,'super')=='1'){
                return ResultService::success();
            }
            else{
                $roleId=TokenService::getCurrentVars($token,'role');
            }
        }
        //没获取到roleId，检查是否携带roleId
        if($roleId==0){
            if($request->has('roleId')){
                $roleId=$request->param('roleId');
            }
            else{
                return ResultService::failure('无权访问该页面');
            }
        }
        if($request->has('authId')){
            $authId=$request->param('authId');
            $count=RoleAuthModel::where('role_id','=',$roleId)->where('auth_id','=',$authId)->count();
            if($count>0){
                return ResultService::success();
            }
        }
        else if($request->has('uris')){
            $uris=$request->param('uris');
            $count=Db::name('role_auth')
                ->alias('role_auth')//做一下别名处理，如果不做则在下一步链接时候会报找不到列的错误，因为链接需要写完整表名，而用name是去前缀的表名，如果用table则无需做别名处理
                ->join('auth','role_auth.auth_id=auth.id')
                ->where('role_id','=',$roleId)
                ->where('uris','=',$uris)
                ->count();
            if($count>0){
                return ResultService::success();
            }
        }
        return ResultService::failure('无权访问该页面');
    }
}