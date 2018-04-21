<?php
/**
 * Created by PhpStorm.
 * User: nicexixi
 * Date: 2018/4/7
 * Time: 23:33
 */

namespace app\admin\hook;


use think\Route;

class AdminHook
{
    public $config=array('before_index','after_index','plugin_route');
    public function beforeIndex(){
        echo 'before';
    }
    public function afterIndex(){
        echo 'after';
    }
    public function pluginRoute(){

    }
}