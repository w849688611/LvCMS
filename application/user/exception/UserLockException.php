<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/10
 * Time: 上午11:50
 */

namespace app\user\exception;


use app\lib\exception\BaseException;

class UserLockException extends BaseException
{
    public $err='30001';
    public $msg='账号已锁死';
}