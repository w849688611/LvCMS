<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/10
 * Time: 上午11:38
 */

namespace app\user\validate;


use app\lib\validate\BaseValidate;
use app\user\enum\UserStatusEnum;
use app\user\exception\UserLockException;
use app\user\model\UserModel;

class UserLoginValidate extends BaseValidate
{
    private $user;
    protected $rule=[
        'account'=>'require|accountCorrect|accountStatus',
        'password'=>'require|passwordCorrect'
    ];
    protected $message=[
        'account.require'=>'账号不能为空',
        'account.accountCorrect'=>'账号不正确',
        'account.accountStatus'=>'账号已锁死',
        'password.require'=>'密码不能为空',
        'password.passwordCorrect'=>'密码不正确',
    ];
    /**账号是否正确
     * @param $value
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function accountCorrect($value){
        $this->user=UserModel::where('account','=',$value)->find();
        if($this->user){
            return true;
        }
        return false;
    }

    /**检验账号是否锁死
     * @param $value
     * @return bool
     * @throws UserLockException
     */
    public function accountStatus($value){
        if($this->user->status==UserStatusEnum::NORMAL){
            $errorCount=$this->user->error_count;
            if($errorCount>=config('security.lockCount')){
                $this->user->status=UserStatusEnum::LOCK;
                $this->user->save();
                throw new UserLockException();
            }
            return true;
        }
        return false;
    }

    /**验证密码，到达一定次数后锁死
     * @param $value
     * @return bool
     * @throws UserLockException
     */
    public function passwordCorrect($value){
        $result=$this->user->checkPassword($value);
        if(!$result){
            $this->user->error_count+=1;
            if($this->user->error_count>=config('security.lockCount')){
                $this->status=UserStatusEnum::LOCK;
                $this->user->save();
                throw new UserLockException();
            }
            $this->user->save();
            return false;
        }
        return true;
    }
}