<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 11:48
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    public $code='200';//请求状态码
    public $status='400';//请求结果码 200成功 400失败
    public $msg='';//返回的信息
    public $err='0';//错误码
    public function __construct($params=[])
    {
        if(is_array($params)){
            if(array_key_exists('status',$params)){
                $this->status=$params['status'];
            }
            if(array_key_exists('msg',$params)){
                $this->msg=$params['msg'];
            }
            if(array_key_exists('err',$params)){
                $this->err=$params['err'];
            }
            if(array_key_exists('code',$params)){
                $this->code=$params['code'];
            }
        }
    }
    public function getExceptionResult(){
        return array(
            'code'=>$this->code,
            'status'=>$this->status,
            'msg'=>$this->msg,
            'err'=>$this->err
        );
    }
}