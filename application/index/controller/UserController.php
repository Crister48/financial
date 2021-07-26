<?php
namespace app\index\controller;
use think\Controller;
use think\Request; 
use think\Db;
use app\common\model\User;
use app\common\model\Klass;
class UserController extends Index1Controller
{
    public function index(){
        $User=User::get(session('id'));
        $this->assign('User',$User);
        return $this->fetch();
    }
    public function editmoney(){
        $User=User::get(session('id'));
        $this->assign('User',$User);
        return $this->fetch();
    }
    public function updatemoney(){
        $teacherid = input('post.id');
        $oldPassword = input('post.oldPassword');
        $password = input('post.password');

        $Teacher = User::get($teacherid);

        if(is_null($Teacher)) {
            return $this->error('未获取到任何用户');
        }
        $newPasswordAgain = input('post.newPasswordAgain');


        //判断密码是否正确
        
        if($oldPassword != $Teacher->password) {
           return $this->error('密码错误', url('editmoney'));
        }

        

        

        //判断两次新密码是否一致
         if($newPasswordAgain != $password) {
           return $this->error('两次输入的存款不一致', url('editmoney'));
        }

        
        // var_dump(Teacher)
        $Teacher->money=$password;
        if(!$Teacher->save()) {
            return $this->error('更新失败', url('ecitmoney'));
        }
         return $this->success('修改成功', url('index'));
    }
    public function edit(){
        $User=User::get(session('id'));
        $this->assign('User',$User);
        return $this->fetch();
    }
    public function update(){
         $teacherid = input('post.id');
        $oldPassword = input('post.oldPassword');
        $password = input('post.password');

        $Teacher = User::get($teacherid);

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