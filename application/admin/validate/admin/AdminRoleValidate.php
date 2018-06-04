<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/9
 * Time: 18:22
 */

namespace app\admin\validate\admin;


use app\admin\model\RoleModel;
use app\lib\validate\BaseValidate;

class AdminRoleValidate extends BaseValidate
{
    protected $rule=[
        'role_id'=>'roleValid'
    ];
    protected $message=[
        'role_id'=>'角色无效'
    ];
    public function roleValid($value){
        $role=RoleModel::where('id','=',$value)->find();
        if($role){
            return true;
        }
        return false;
    }
}