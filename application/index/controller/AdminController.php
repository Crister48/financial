<?php
namespace app\index\controller;
use think\Controller;
use think\Request; 
use think\Db;
use app\common\model\Admin;
use app\common\model\User;
class AdminController extends IndexController
{
    public function index(){
        $User=Admin::get(session('id'));
        $this->assign('User',$User);
        return $this->fetch();
    }
        public function edit(){
        $User=Admin::get(session('id'));
        $this->assign('User',$User);
        return $this->fetch();
    }
    public function lookuser(){
        $Usera=Admin::get(session('id'));
        $this->assign('User',$Usera);
        $User=new User;
        $users=$User->select();
        $this->assign('users',$users);
        return $this->fetch();
    }
    public function reset(){
        $id = Request::instance()->param('id/d'); // “/d”表示将数值转化为“整形”

        if (is_null($id) || 0 === $id) {
            return $this->error('未获取到ID信息');
        }

        
        $Teacher = User::get($id);

        
        if (is_null($Teacher)) {
            return $this->error('不存在id为' . $id . '的用户，删除失败');
        }

        $Teacher->password="000000";
        $Teacher->save($Teacher->getData());
        // 进行跳转
        return $this->success('重置成功，新密码为 000000', url('lookuser'));
    }
    public function delete()
    {
        // 获取pathinfo传入的ID值.
        $id = Request::instance()->param('id/d'); // “/d”表示将数值转化为“整形”

        if (is_null($id) || 0 === $id) {
            return $this->error('未获取到ID信息');
        }

        // 获取要删除的对象
        $Teacher = User::get($id);

        // 要删除的对象不存在
        if (is_null($Teacher)) {
            return $this->error('不存在id为' . $id . '的用户，删除失败');
        }

        // 删除对象
        if (!$Teacher->delete()) {
            return $this->error('删除失败:' . $Teacher->getError());
        }

        // 进行跳转
        return $this->success('删除成功', url('lookuser'));
    }
    public function update(){
         $teacherid = input('post.id');
        $oldPassword = input('post.oldPassword');
        $password = input('post.password');

        $Teacher = Admin::get($teacherid);

        if(is_null($Teacher)) {
            return $this->error('未获取到任何用户');
        }
        $newPasswordAgain = input('post.newPasswordAgain');


        //判断旧密码是否正确
        
        if($oldPassword != $Teacher->password) {
           return $this->error('旧密码错误', url('edit'));
        }

        //判断新旧密码是否一致
        if($oldPassword === $password) {
           return $this->error('新旧密码一致', url('edit'));
        }

        //判断新密码是否符合要求必须由字母
        if (!preg_match('/[a-zA-Z]/', $password)) {
            return $this->error('新密码必须包含字母', url('edit'));
        }

        //判断两次新密码是否一致
         if($newPasswordAgain != $password) {
           return $this->error('两次输入的新密码不一致', url('edit'));
        }

        // 判断新密码位数是否符合标准c
        if(strlen($password) < 6 || strlen($password)>25) {
            return $this->error('密码长度应为6到25之间', url('edit'));
        }
        // var_dump(Teacher)
        $Teacher->password=$password;
        if(!$Teacher->save()) {
            return $this->error('密码更新失败', url('update'));
        }
        session('id', null);
         return $this->success('修改成功，请重新登录', url('login/'));
    }
}
  