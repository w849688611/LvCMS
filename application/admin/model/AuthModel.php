<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/15
 * Time: 20:39
 */

namespace app\admin\model;


use think\Model;

class AuthModel extends Model
{
    protected $name='auth';

    /**根据用户权限获取用户的权限配置树
     * @param $roleAuth
     * @param string $tag
     * @param string $tagSuccessValue
     * @param string $tagFailureValue
     * @return array
     */
    public static function generateAuthTree($roleAuth,$tag='checked',$tagSuccessValue='1',$tagFailureValue='0'){
        if(is_array($roleAuth)){
            $parentAuth=self::where('parent_id','=','0')->select()->toArray();
            for($i=0,$len=count($parentAuth);$i<$len;$i++){
                if(self::isAuthInRoleAuth($roleAuth,$parentAuth[$i])){
                    $parentAuth[$i][$tag]=$tagSuccessValue;
                }
                else{
                    $parentAuth[$i][$tag]=$tagFailureValue;
                }
                $parentAuth[$i]['children']=self::generateAuthChildren($roleAuth,$tag,$tagSuccessValue,$tagFailureValue,$parentAuth[$i]['id']);
            }
            return $parentAuth;
        }
        return array();
    }
    private static function generateAuthChildren($roleAuth,$tag='checked',$tagSuccessValue='1',$tagFailureValue='0',$parentId){
        $parentAuth=self::where('parent_id','=',$parentId)->select()->toArray();
        if(count($parentAuth)==0){
            return array();
        }
        else{
            for($i=0,$len=count($parentAuth);$i<$len;$i++){
                if(self::isAuthInRoleAuth($roleAuth,$parentAuth[$i])){
                    $parentAuth[$i][$tag]=$tagSuccessValue;
                }
                else{
                    $parentAuth[$i][$tag]=$tagFailureValue;
                }
                $parentAuth[$i]['children']=self::generateAuthChildren($roleAuth,$tag,$tagSuccessValue,$tagFailureValue,$parentAuth[$i]['id']);
            }
            return $parentAuth;
        }
    }
    private static function isAuthInRoleAuth($roleAuth,$auth){
        for($i=0,$len=count($roleAuth);$i<$len;$i++){
            if($auth['id']==$roleAuth[$i]){
                return true;
            }
        }
        return false;
    }

    /**生成角色的资源树
     * @param $roleAuth
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAuthTreeOfRole($roleAuth){
        $auths=self::where('id','IN',$roleAuth)->select()->toArray();
        $tree=array();
        for($i=0,$len=count($auths);$i<$len;$i++){
            if($auths[$i]['parent_id']==0){
                $tree[]=&$auths[$i];
            }
            else{
                for($j=0;$j<$len;$j++){
                    if($auths[$j]['id']==$auths[$i]['parent_id']){
                        if(!isset($auths[$j]['children'])){
                            $auths[$j]['children']=array();
                        }
                        $auths[$j]['children'][]=$auths[$i];
                        break;
                    }
                }
            }
        }
        return $tree;
    }
}