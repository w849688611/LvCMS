<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 12:12
 */

namespace app\service;


class ResultService
{
    const Success='200';
    const Failure='400';

    public static function makeResult($status=ResultService::Success,$msg='',$data='',$err='0'){
        $result=[
            'status'=>$status,
            'msg'=>$msg,
            'data'=>$data,
            'err'=>$err
        ];
        return json($result);
    }
    public static function success($msg='',$data=[]){
        return self::makeResult(self::Success,$msg,$data);
    }
    public static function failure($msg='',$data=[]){
        return self::makeResult(self::Failure,$msg,$data);
    }
}