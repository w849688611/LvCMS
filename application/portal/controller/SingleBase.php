<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/13
 * Time: 下午10:56
 */

namespace app\portal\controller;


use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\portal\model\SingleModel;
use app\portal\validate\single\SingleAddValidate;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class SingleBase extends Controller
{
    /**添加单页
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new SingleAddValidate())->goCheck();
        $single=new SingleModel($request->param());
        if($request->has('content')){
            $single->content=$request->param('content','','htmlspecialchars_decode');
        }
        if($request->has('more')){
            $single->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        }
        $single->allowField(true)->save();
        return ResultService::success('添加单页成功');
    }

    /**删除单页
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
        $single=SingleModel::where('id','=',$id)->find();
        if($single){
            $single->delete();
            return ResultService::success('删除单页成功');
        }
        else{
            return ResultService::failure('单页不存在');
        }
    }

    /**更新单页
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
        $single=SingleModel::where('id','=',$id)->find();
        if($single){
            if($request->has('status')){
                $single->status=$request->param('status');
            }
            if($request->has('title')){
                $single->title=$request->param('title');
            }
            if($request->has('keywords')){
                $single->keywords=$request->param('keywords');
            }
            if($request->has('excerpt')){
                $single->excerpt=$request->param('excerpt');
            }
            if($request->has('content')){
                $single->content=$request->param('content','','htmlspecialchars_decode');
            }
            if($request->has('more')){
                $single->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
            }
            if($request->has('template_id')){
                $single->template_id=$request->param('template_id');
            }
            $single->save();
            return ResultService::success('更新单页成功');
        }
        else{
            return ResultService::failure('单页不存在');
        }
    }

    /**根据id获取单页
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
            $single=SingleModel::where('id','=',$id)->with('template')->find();
            if($single){
                $single->hidden(['create_time','update_time','template.create_time','template.update_time']);
                return ResultService::makeResult(ResultService::Success,'',$single->toArray());
            }
            else{
                return ResultService::failure('内容不存在');
            }
        }
        else{
            $singles=SingleModel::with('template')->select();
            $singles->hidden(['create_time','update_time','template.create_time','template.update_time']);
            return ResultService::makeResult(ResultService::Success,'',$singles->toArray());
        }
    }

    /**分页获取单页
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getByPage(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $pageResult=[];
        $singles=SingleModel::with('template')->select();
        $singles->hidden(['create_time','update_time','template.create_time','template.update_time']);
        $pageResult['total']=count($singles);
        if($request->has('page')){
            $pageResult['page']=$page=$request->param('page');
            $pageResult['pageSize']=$pageSize=$request->has('pageSize')?$request->param('pageSize'):config('base.defaultPageSize');
            $singles=SingleModel::page($page,$pageSize)->with('template')->select();
            $singles->hidden(['create_time','update_time','template.create_time','template.update_time']);
        }
        $pageResult['pageData']=$singles;
        return ResultService::makeResult(ResultService::Success,'',$pageResult);
    }
}