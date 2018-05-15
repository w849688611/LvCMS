<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/13
 * Time: 下午1:35
 */

namespace app\file\controller;


use app\file\model\FileModel;
use app\lib\exception\TokenException;
use app\lib\validate\IDPositive;
use app\service\ResultService;
use app\service\TokenService;
use think\Controller;
use think\Request;

class FileBase extends Controller
{
    /**上传文件
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function add(Request $request){
        if(!TokenService::validToken($request->header('token'))){
            throw new TokenException();
        }
        $tag=$request->has('tag')?$request->param('tag'):'file';
        $file=$request->file($tag);
        if(is_array($file)){
            $urls=[];
            foreach ($file as $item){
                $info=$this->handleFile($item);
                if($info){
                    $file=FileModel::where('md5','=',$info->hash('md5'))
                        ->whereOr('sha1','=',$info->hash('sha1'))->find();
                    if(!$file){
                        $file=new FileModel();
                        $file->url=config('file.showPath').$info->getSaveName();
                        $file->name=$info->getInfo()['name'];
                        $file->md5=$info->hash('md5');
                        $file->sha1=$info->hash('sha1');
                        $file->ext=$info->getExtension();
                        $file->save();
                    }
                    array_push($urls,$file->url);
                }
            }
            return ResultService::success('',['url'=>$urls]);
        }
        else{
            $info=$this->handleFile($file);
            if($info){
                $file=FileModel::where('md5','=',$info->hash('md5'))
                ->whereOr('sha1','=',$info->hash('sha1'))->find();
                if(!$file){
                    $file=new FileModel();
                    $file->url=config('file.showPath').$info->getSaveName();
                    $file->name=$info->getInfo()['name'];
                    $file->md5=$info->hash('md5');
                    $file->sha1=$info->hash('sha1');
                    $file->ext=$info->getExtension();
                    $file->save();
                }
               return ResultService::success('',['url'=>$file->url]);
            }
            else{
                return ResultService::failure('上传不成功');
            }
        }
    }

    /**处理上传的文件
     * @param $file
     * @return mixed
     */
    private function handleFile($file){
        $info=$file->validate(['ext'=>config('file.ext')])
            ->rule('md5')
            ->move(config('file.uploadPath'));
        return $info;
    }

    /**删除文件
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function delete(Request $request){
        if(!TokenService::validToken($request->header('token'))){
            throw new TokenException();
        }
        (new IDPositive())->goCheck();
        $id=$request->param('id');
        $file=FileModel::where('id','=',$id)->find();
        if($file){
            @unlink(config('file.basePath').$file->url);
            $file->delete();
            return ResultService::success('删除文件成功');
        }
        else{
            return ResultService::failure('文件不存在');
        }
    }

    /**获取文件信息
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
            $file=FileModel::where('id','=',$id)->find();
            if($file){
                return ResultService::makeResult(ResultService::Success,'',$file->toArray());
            }
            else{
                return ResultService::failure('用户不存在');
            }
        }
        else{
            $files=FileModel::select();
            return ResultService::makeResult(ResultService::Success,'',$files->toArray());
        }
    }

    /**分页获取文件
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
            $file=FileModel::page($page,$pageSize)->select();
        }
        else{
            $file=FileModel::select();
        }
        return ResultService::makeResult(ResultService::Success,'',$file->toArray());
    }
    /**根据文件md5查找文件
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getByMd5(Request $request){
        if(!TokenService::validToken($request->header('token'))){
            throw new TokenException();
        }
        if($request->has('md5')){
            $md5=$request->param('md5');
            $file=FileModel::where('md5','=',$md5)->select();
            if($file){
                return ResultService::success('',['url'=>$file->url]);
            }
        }
        return ResultService::failure();
    }

    /**根据文件sha1查找文件
     * @param Request $request
     * @return \think\response\Json
     * @throws TokenException
     */
    public function getBySha1(Request $request){
        if(!TokenService::validToken($request->header('token'))){
            throw new TokenException();
        }
        if($request->has('sha1')){
            $sha1=$request->param('sha1');
            $file=FileModel::where('md5','=',$sha1)->select();
            if($file){
                return ResultService::success();
            }
        }
        return ResultService::failure();
    }
}