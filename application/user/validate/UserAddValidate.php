<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/30
 * Time: 上午12:13
 */

namespace app\user\validate;


use app\lib\validate\BaseValidate;
use app\user\model\UserModel;

class UserAddValidate extends BaseValidate
{
    protected $rule=[
        'account'=>'require｜min:6|accountExist',
        'password'=>'require|min:6'
    ];
    protected $message=[
        'account.require'=>'会员帐号不能为空',
        'account.min'=>'会员帐号长度不能少于6位',
        'account.accountExist'=>'会员帐号已存在',
        'password.require'=>'会员密码不能为空',
        'password.min'=>'会员密码长度不能少于6位'
    ];

    public function accountExist($value){
        $count=UserModel::where('account','=',$value)->count();
        if($count>0){
            return false;
        }
        return true;
    }
}