<?php
/**
 * Created by PhpStorm.
 * User: wangluyu
 * Date: 18/5/14
 * Time: 上午10:15
 */

namespace app\portal\model;


use think\Model;

class SingleModel extends Model
{
    protected $autoWriteTimestamp=true;
    protected $name='single';

    public function template(){
        return $this->belongsTo('TemplateModel','template_id');
    }
}