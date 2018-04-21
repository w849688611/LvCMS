<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 14:13
 */

namespace app\admin\validate\admin;


use app\admin\enum\AdminStatusEnum;
use app\admin\exception\AdminLockException;
use app\admin\model\AdminModel;
use app\lib\validate\BaseValidate;

class AdminLoginValidate extends BaseValidate
{
    private $admin;
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
        $this->admin=AdminModel::where('account','=',$value)->find();
        if($this->admin){
            return true;
        }
        return false;
    }

    /**检验账号是否锁死
     * @param $value
     * @return bool
     * @throws AdminLockException
     */
    public function accountStatus($value){
        if($this->admin->status==AdminStatusEnum::NORMAL){
            $errorCount=$this->admin->error_count;
            if($errorCount>=config('security.lockCount')){
                $this->admin->status=AdminStatusEnum::LOCK;
                $this->admin->save();
                throw new AdminLockException();
            }
            return true;
        }
        return false;
    }

    /**验证密码，到达一定次数后锁死
     * @param $value
     * @return bool
     * @throws AdminLockException
     */
    public function passwordCorrect($value){
        $result=$this->admin->checkPassword($value);
        if(!$result){
            $this->admin->error_count+=1;
            if($this->admin->error_count>=config('security.lockCount')){
                $this->status=AdminStatusEnum::LOCK;
                $this->admin->save();
                throw new AdminLockException();
            }
            $this->admin->save();
            return false;
        }
        return true;
    }
}