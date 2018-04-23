<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 12:51
 */

namespace app\admin\controller;

use app\admin\enum\AdminStatusEnum;
use app\admin\model\AdminModel;
use app\admin\validate\admin\AdminAddValidate;
use app\admin\validate\admin\AdminLoginValidate;
use app\admin\validate\admin\AdminPasswordValidate;
use app\admin\validate\admin\AdminRoleValidate;
use app\admin\validate\admin\AdminStatusValidate;
use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

/**对管理员用户的基本操作（CURD）
 * Class AdminBase
 * @package app\admin\controller
 */
class AdminBase extends Controller
{
    /**添加管理员
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     * @throws \app\lib\exception\ParamException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new AdminAddValidate())->goCheck();
        $admin=new AdminModel($request->param());
        $admin->password=$request->param('password');//触发一下修改器
        $admin->allowField(true)->save();
        return ResultService::success('添加管理员成功');
    }

    /**删除管理员
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
        $admin=AdminModel::where('id','=',$id)->find();
        if($admin){
            $admin->delete();
            return ResultService::success('删除管理员成功');
        }
       else{
            return ResultService::failure('管理员不存在');
       }
    }

    /**更新管理员
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
        $admin=AdminModel::where('id','=',$id)->find();
        if($request->has('password')){
            (new AdminPasswordValidate())->goCheck();
            $admin->password=$request->param('password');
        }
        if($request->has('role')){
            (new AdminRoleValidate())->goCheck();
            $admin->role=$request->param('role');
        }
        if($request->has('status')){
            (new AdminStatusValidate())->goCheck();
            $status=$request->param('status');
            $admin->status=$status;
            if($status==AdminStatusEnum::NORMAL){
                $admin->error_count=0;
            }
        }
        $admin->save();
        return ResultService::success('更新管理员成功');
    }

    /**根据id获取管理员信息
     * @param Request $request
     * @return \think\response\Json
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
            $admin=AdminModel::where('id','=',$id)->find();
            if($admin){
                return ResultService::makeResult(ResultService::Success,'',$admin->toArray());
            }
            else{
                return ResultService::failure('管理员不存在');
            }
        }
        else{
            $admins=AdminModel::select();
            return ResultService::makeResult(ResultService::Success,'',$admins->toArray());
        }
    }

    /**获取全部管理员分页
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
            $admin=AdminModel::page($page,$pageSize)->select();
        }
        else{
            $admin=AdminModel::select();
        }
        return ResultService::makeResult(ResultService::Success,'',$admin->toArray());
    }

    /**管理员登录
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParamException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login(Request $request){
        (new AdminLoginValidate())->goCheck();
        $admin=AdminModel::where('account','=',$request->param('account'))->find();
        $admin->error_count=0;//清空密码错误次数
        $admin->save();
        $payload=[
            'id'=>$admin->id,
            'account'=>$admin->account,
            'role'=>$admin->role,
            'super'=>$admin->super,
            'status'=>$admin->status,
            'errorCount'=>$admin->error_count,
            'isAdmin'=>'1'
        ];
        $token=TokenService::initToken($payload);
        return ResultService::makeResult(ResultService::Success,'',['token'=>$token]);
    }

    /**管理员登出
     * @param Request $request
     * @return \think\response\Json
     */
    public function logout(Request $request){
        $token=$request->header('token');
        TokenService::clearToken($token);
        return ResultService::success();
    }
    /**检查登录状态
     * @param Request $request
     * @return \think\response\Json
     */
    public function checkLogin(Request $request){
        if(TokenService::validAdminToken($request->header('token'))){
            return ResultService::success();
        }
        else{
            return ResultService::failure();
        }
    }
}