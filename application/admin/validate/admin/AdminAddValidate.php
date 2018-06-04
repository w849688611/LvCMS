<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 13:05
 */

namespace app\admin\validate\admin;


use app\admin\model\AdminModel;
use app\admin\model\RoleModel;
use app\lib\validate\BaseValidate;

class AdminAddValidate extends BaseValidate
{
    protected $rule=[
        'account'=>'require|min:6|accountExist',
        'password'=>'require|min:6',
        'role_id'=>'require|positiveInt|roleValid'
    ];
    protected $message=[
        'account.require'=>'账号不能为空',
        'account.min'=>'账号长度至少6位',
        'account.accountExist'=>'账号已存在',
        'password.require'=>'密码不能为空',
        'password.min'=>'密码长度至少6位',
        'role_id.require'=>'角色id不能为空',
        'role_id.positiveInt'=>'角色id必须为有效正整数',
        'role_id.roleValid'=>'角色无效'
    ];

    /**账号是否已经存在
     * @param $value
     * @return bool
     */
    public function accountExist($value){
        $count=AdminModel::where('account','=',$value)->count();
        if($count>0){
            return false;
        }
        return true;
    }

    /**角色是否有效
     * @param $value
     * @return bool
     * @throws \think\exception\DbException
     */
    public function roleValid($value){
        $role=RoleModel::where('id','=',$value)->find();
        if($role){
            return true;
        }
        return false;
    }
}