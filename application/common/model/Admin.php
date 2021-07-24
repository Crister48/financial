<?php
namespace app\common\model;
use think\Model;
/**
 * 课程
 */
class Admin extends Model
{
    static public function logOut()
    {
        // 销毁session中数据
        session('id', null);
        return true;
    }
     static public function isLogin()
    {
        $teacherId = session('id');

        // isset()和is_null()是一对反义词
        if (isset($teacherId)&&session('tag')==1) {
            return true;
        } else {
            return false;
        }
    }
}