<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/13
 * Time: 下午6:33
 */

namespace app\portal\controller;


use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\portal\model\NavItemModel;
use app\portal\model\NavModel;
use app\portal\validate\nav\NavAddValidate;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class NavBase extends Controller
{
    /**添加导航组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new NavAddValidate())->goCheck();
        $nav=new NavModel($request->param());
        if($request->has('more')){
            $nav->more=$request->param('more','','htmlspecialchars_decode,json_decode');
        }
        $nav->allowField(true)->save();
        return ResultService::success('添加导航组成功');
    }

    /**删除导航组
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
        $nav=NavModel::where('id','=',$id)->find();
        if($nav){
            NavItemModel::where('nav_id','=',$nav->id)->delete();
            $nav->delete();
            return ResultService::success('删除导航组成功');
        }
        else{
            return ResultService::failure('导航组不存在');
        }
    }

    /**更新导航组
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
        $nav=NavModel::where('id','=',$id)->find();
        if($nav){
            if($request->has('name')){
                $nav->name=$request->param('name');
            }
            if($request->has('excerpt')){
                $nav->excerpt=$request->param('excerpt');
            }
            if($request->has('more')){
                $nav->more=$request->param('more','','htmlspecialchars_decode,json_decode');
            }
            $nav->save();
            return ResultService::success('更新导航组成功');
        }
        else{
            return ResultService::failure('导航组不存在');
        }
    }

    /**获取导航组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function get(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        if($request->has('id')){
            (new IDPositive())->goCheck();
            $id=$request->param('id');
            $nav=NavModel::where('id','=',$id)->find();
            if($nav){
                $nav->item=NavModel::generateItemTree($id);
                return ResultService::success('',$nav->toArray());
            }
            else{
                return ResultService::failure('导航组不存在');
            }
        }
        else{
            $navs=NavModel::select();
            return ResultService::success('',$navs->toArray());
        }
    }

    /**分页获取导航组
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getByPage(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        if($request->has('page')){
            $page=$request->param('page');
            $pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $navs=NavModel::page($page,$pageSize)->select();
        }
        else{
            $navs=NavModel::select();
        }
        return ResultService::success('',$navs->toArray());
    }

    /**获取导航组下的导航项
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function geItemOfNav(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $items=NavModel::generateItemTree($id);
        return ResultService::success('',$items);
    }
}