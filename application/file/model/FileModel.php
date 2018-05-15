<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/13
 * Time: 下午1:57
 */

namespace app\file\model;


use think\Model;

class FileModel extends Model
{
    protected $autoWriteTimestamp=true;
    protected $name='file';
}