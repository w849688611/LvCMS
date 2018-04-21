<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/9
 * Time: 19:03
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code=401;
    public $status=400;
    public $msg='token已过期或无效';
    public $err='90001';
}