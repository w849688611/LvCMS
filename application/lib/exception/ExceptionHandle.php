<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 11:47
 */

namespace app\lib\exception;


use Exception;
use think\exception\Handle;

class ExceptionHandle extends Handle
{
    public function render(Exception $e)
    {
        if($e instanceof BaseException){
            $result=$e->getExceptionResult();
            return json($result,$result['code']);
        }
        else{
            return parent::render($e);
        }
    }
}