<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/15
 * Time: 下午3:59
 */

namespace app\portal\controller;


use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\portal\model\NavItemModel;
use app\portal\validate\nav\NavItemAddValidate;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class NavItemBase extends Controller
{
    /**添加导航项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new NavItemAddValidate())->goCheck();
        $navItem=new NavItemModel($request->param());
        if($request->has('more')){
            $navItem->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        }
        $navItem->allowField(true)->save();
        return ResultService::success('添加导航项成功');
    }

    /**删除导航项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function delete(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $navItem=NavItemModel::where('id','=',$id)->find();
        if($navItem){
            $navItem->delete();
            return ResultService::success('删除导航项成功');
        }
        else{
            return ResultService::failure('导航项不存在');
        }
    }

    /**更新导航项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function update(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $navItem=NavItemModel::where('id','=',$id)->find();
        if($navItem){
            if($request->has('nav_id')){
                $navItem->nav_id=$request->param('nav_id');
            }
            if($request->has('parent_id')){
                if($request->param('parent_id')==$navItem->id){
                    return ResultService::failure('父级导航不能为自身');
                }
                $navItem->parent_id=$request->param('parent_id');
            }
            if($request->has('item_id')){
                $navItem->item_id=$request->param('item_id');
            }
            if($request->has('name')){
                $navItem->name=$request->param('name');
            }
            if($request->has('type')){
                $navItem->type=$request->param('type');
            }
            if($request->has('more')){
                $navItem->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
            }
            if($request->has('link')){
                $navItem->link=$request->param('link');
            }
            $navItem->save();
            return ResultService::success('更新导航项成功');
        }
        else{
            return ResultService::failure('导航项不存在');
        }
    }

    /**根据id获取导航项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $navItem=NavItemModel::where('id','=',$id)->with('nav')->find();
        if($navItem){
            $navItem->parent=NavItemModel::where('id','=',$navItem->parent_id)->find();
            $navItem->item=$navItem->item;
            return ResultService::success('',$navItem->toArray());
        }
        return ResultService::failure('导航项不存在');
    }
}