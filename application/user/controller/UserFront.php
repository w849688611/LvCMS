<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/1
 * Time: 下午9:04
 */

namespace app\user\controller;


use app\lib\exception\TokenException;
use app\service\ResultService;
use app\service\TokenService;
use app\user\model\UserModel;
use app\user\validate\UserAddValidate;
use app\user\validate\UserLoginValidate;
use app\user\validate\UserPasswordValidate;
use think\Request;

class UserFront
{
    /**用户登录
     * @param Request $request
     * @return \think\response\Json
     */
    public function login(Request $request){
        (new UserLoginValidate())->goCheck();
        $user=UserModel::where('account','=',$request->param('account'))->find();
        $user->error_count=0;//清空密码错误次数
        $user->save();
        $payload=[
            'id'=>$user->id,
            'account'=>$user->account,
            'type'=>$user->type,
            'status'=>$user->status,
            'errorCount'=>$user->error_count,
            'isUser'=>'1'
        ];
        $token=TokenService::initToken($payload);
        return ResultService::makeResult(ResultService::Success,'',['token'=>$token]);
    }

    /**用户登出
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
        if(TokenService::validUserToken($request->header('token'))){
            return ResultService::success();
        }
        else{
            return ResultService::failure();
        }
    }

    /**注册
     * @param Request $request
     * @return \think\response\Json
     */
    public function register(Request $request){
        (new UserAddValidate())->goCheck();
        $user=new UserModel($request->param());
        $user->password=$request->param('password');
        $user->more=$request->param('more','','htmlspecialchars_decode,json_decode');
        $user->allowField(true)->save();
        return ResultService::success('注册用户成功');
    }

    /**更新
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        if(!TokenService::validUserToken($request->header('token'))){
            throw new TokenException();
        }
        $id=TokenService::getCurrentVars($request->header('token'),'id');
        $user=UserModel::where('id','=',$id)->find();
        if($user){
            if($request->has('password')&&$request->has('oldPassword')){
                $password=$request->param('password');
                $oldPassword=$request->param('oldPassword');
                if($user->checkPassword($oldPassword)){
                    (new UserPasswordValidate())->goCheck();
                    $user->password=$password;
                }
                else{
                    return ResultService::makeResult(ResultService::Failure,'原密码不正确','','30002');
                }
            }
            if($request->param('more')){
                $user->more=$request->param('more','','htmlspecialchars_decode,json_decode');
            }
            $user->save();
            return ResultService::success('更新用户成功');
        }
        else{
            return ResultService::failure('未成功获取到用户信息');
        }
    }

    /**根据token获取个人信息
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if(!TokenService::validUserToken($request->header('token'))){
            throw new TokenException();
        }
        $id=TokenService::getCurrentVars($request->header('token'),'id');
        $user=UserModel::where('id','=',$id)->find()->hidden(['create_time','update_time','password']);
        if($user){
            return ResultService::makeResult(ResultService::Success,'',$user->toArray());
        }
        else{
            return ResultService::failure('未成功获取到用户信息');
        }
    }
}