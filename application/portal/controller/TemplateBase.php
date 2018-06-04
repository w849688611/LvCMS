<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/13
 * Time: 下午10:54
 */

namespace app\portal\controller;


use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\portal\enum\TypeEnum;
use app\portal\model\TemplateModel;
use app\portal\validate\template\TemplateAddValidate;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class TemplateBase extends Controller
{
    /**添加模版
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        (new TemplateAddValidate())->goCheck();
        $template=new TemplateModel($request->param());
        if($request->has('more')){
            $template->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
        }
        if($template->is_default&&$template->is_default==1){
            TemplateModel::where('is_default','=','1')->update(['is_default'=>0]);
        }
        $template->allowField(true)->save();
        return ResultService::success('添加模版成功');
    }

    /**删除模版
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
        $template=TemplateModel::where('id','=',$id)->find();
        if($template){
            $template->delete();
            return ResultService::success('删除模版成功');
        }
        else{
            return ResultService::failure('模版不存在');
        }
    }

    /**更新模版
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
        $template=TemplateModel::where('id','=',$id)->find();
        if($template){
            if($request->has('name')){
                $template->name=$request->param('name');
            }
            if($request->has('url')){
                $template->url=$request->param('url');
            }
            if($request->has('type')){
                $template->type=$request->param('type');
            }
            if($request->has('more')){
                $template->more=json_decode(htmlspecialchars_decode($request->param('more')),true);
            }
            if($request->has('is_default')){
                $template->is_default=$request->param('is_default');
                if($template->is_default==1){
                    TemplateModel::where('is_default','=','1')->update(['is_default'=>0]);
                }
            }
            $template->save();
            return ResultService::success('更新模版成功');
        }
        else{
            return ResultService::failure('模版不存在');
        }
    }

    /**按id获取模版
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
            $template=TemplateModel::where('id','=',$id)->find();
            if($template){
                $template->hidden(['create_time','update_time']);
                return ResultService::makeResult(ResultService::Success,'',$template->toArray());
            }
            else{
                return ResultService::failure('模版不存在');
            }
        }
        else{
            $templates=TemplateModel::select();
            return ResultService::makeResult(ResultService::Success,'',$templates->toArray());
        }
    }

    /**获取栏目模版
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getCategoryTemplate(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $templates=TemplateModel::getByType(TypeEnum::CATEGORY);
        return ResultService::success('',$templates->toArray());
    }

    /**获取单页模版
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getSingleTemplate(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $templates=TemplateModel::getByType(TypeEnum::SINGLE);
        return ResultService::success('',$templates->toArray());
    }

    /**获取内容模版
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getPostTemplate(Request $request){
        if(!TokenService::validAdminToken($request->header('token'))){
            throw new TokenException();
        }
        $templates=TemplateModel::getByType(TypeEnum::POST);
        return ResultService::success('',$templates->toArray());
    }
}