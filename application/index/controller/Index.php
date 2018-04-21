<?php
namespace app\index\controller;

use app\admin\model\AdminModel;
use app\lib\exception\BaseException;
use app\service\TokenService;
use think\Controller;
use think\Exception;
use think\Request;
use think\View;

class Index extends Controller
{
    public function index(Request $request)
    {
        $view=new View();
      return $view->fetch();
    }

}
