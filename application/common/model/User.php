<?php
namespace app\common\model;
use think\Model;
/**
 * 课程
 */
class User extends Model
{
    static public function isLogin()
    {
        $teacherId = session('id');

        // isset()和is_null()是一对反义词
        if (isset($teacherId)&&session('tag')==2) {
            return true;
        } else {
            return false;
        }
    }
}