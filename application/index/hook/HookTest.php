<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/7
 * Time: 22:20
 */

namespace app\index\hook;



class HookTest
{
    public $config=array('before_index','after_index');
    public function beforeIndex(){
        echo 'before';
    }
    public function afterIndex(){
        echo 'after';
    }
}