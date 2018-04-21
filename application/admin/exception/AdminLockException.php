<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/8
 * Time: 15:16
 */

namespace app\admin\exception;


use app\lib\exception\BaseException;

class AdminLockException extends BaseException
{
    public $err='20001';
    public $msg='账号已锁死';
}