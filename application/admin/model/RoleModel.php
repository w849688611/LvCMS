<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 14:01
 */

namespace app\admin\model;


use think\Model;

class RoleModel extends Model
{
    protected $autoWriteTimestamp=true;
    protected $name='role';

    /**与用户一对多关联
     * @return \think\model\relation\HasMany
     */
    public function admin(){
        return $this->hasMany('AdminModel','role');
    }
    /**与权限多对多关联
     * @return \think\model\relation\BelongsToMany
     */
    public function auth(){
        return $this->belongsToMany('AuthModel','role_auth','auth_id','role_id');
    }
}