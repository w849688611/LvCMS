<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/13
 * Time: 下午5:46
 */

namespace app\util\controller;


use app\service\ResultService;
use think\captcha\Captcha;
use think\Controller;
use think\Request;

class CaptchaBase extends Controller
{
    /**获取验证码 返回的直接是图片，需要前端配合一下，将生成时间作为id传过来，并在前端保存，验证的时候带id验证
     * @param Request $request
     * @return \think\Response
     */
    public function get(Request $request){
        $id=$request->has('id')?$request->param('id'):0;
        $length=$request->has('length')?$request->param('length'):5;
        $imageHeight=$request->has('height')?$request->param('height'):0;
        $imageWidth=$request->has('width')?$request->param('width'):0;
        $config=[
            'imageH'=>$imageHeight,
            'imageW'=>$imageWidth,
            'length'=>$length
        ];
        $captcha=new Captcha($config);
        return $captcha->entry($id);
    }

    /**验证验证码
     * @param Request $request
     * @return \think\response\Json
     */
    public function check(Request $request){
        $code=$request->has('code')?$request->param('code'):'';
        $id=$request->has('id')?$request->param('id'):0;
        $captcha=new Captcha();
        $result=$captcha->check($code,$id);
        if($result){
            return ResultService::success();
        }
        return ResultService::failure();
    }
}