<?php
namespace app\common\model;
use think\Model;
/**
 * 课程
 */
class Record extends Model
{
    public function getTypeAttr($value)
    {
        $status = array('0'=>'借款','1'=>'贷款');
        $sex = $status[$value];
        if (isset($sex))
        {
            return $sex;
        } else {
            return $status[0];
        }
    } 
    public function Klass()
    {
        return $this->belongsTo('Klass');
    }
    public function getCreateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }
}