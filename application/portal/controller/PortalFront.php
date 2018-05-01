<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/4/25
 * Time: 下午9:50
 */

namespace app\portal\controller;


use think\Controller;
use think\Request;

/**专门用于前端数据获取（非管理端）
 * Class PortalFront
 * @package app\portal\controller
 */
class PortalFront extends Controller
{
    //根据id获取栏目相关信息
    public function getCategory(Request $request){

    }
    //根据栏目id获取栏目下内容，需要经过筛选，剔除不可见内容，检测是否有page参数，若有则分页返回，若无则返回全部
    public function getPostOfCategory(Request $request){

    }
    //根据id获取post，若内容不可见则返回不可见（需要设置俩钩子）
    public function getPost(Request $request){

    }
    //根据内容id获取评论，同样检测是否分页，由于会有树形结构评论，因此分页是按照一级评论分页的
    public function getCommentOfPost(Request $request){

    }
    public function getNav(Request $request){

    }
    public function getSlide(Request $request){

    }
    public function getFriendLink(Request $request){

    }
}