<?php
namespace app\index\controller;
use think\Controller;
use think\Request; 
use app\common\model\User;
use app\common\model\Admin;
use app\common\model\Klass;
class LoginController extends Controller
{
    public function index()
    {
        
        return $this->fetch();
    }
    
    // 处理用户提交的登录数据  
    public function login()
    {
        
        // 接收post信息
        $postData = Request::instance()->post();

        // 验证用户名是否存在
        $map = array('username'  => $postData['username']);
        $User = Admin::get($map);
        $tag=1;
        if(is_null($User)){
            $User = User::get($map);
            $tag=2;
        }
        // $User要么是一个对象，要么是null。
        if (!is_null($User) && $User->getData('password') === $postData['password']) {
            // 用户名密码正确，将userId存session，并跳转至用户界面
            if($tag===1){
                        session('tag',1);
                        session('id', $User->getData('id'));
                        return $this->success('登录成功', url('admin/index?id='.$User->getData('id')));
                    }
            if($tag===2){
                        session('tag',2);
                        session('id', $User->getData('id'));
                        return $this->success('登录成功', url('user/index?id='.$User->getData('id')));
                    }
        } else {
            // 用户名不存在，跳转到登录界面。
            return $this->error('用户名或密码错误', url('index'));
        }
    }
//$tag===1管理员，$tag===2用户
// 验证用户名是否存在
// 验证密码是否正确
// 用户名密码正确 ，将teacherId 存session
// 用户名密码错误，跳转到登录界面 
    public function logout(){
        if(Admin::logout()){
            return $this->success('注销成功',url('login/index'));
        }
        else{
            return $this->error('注销失败',url(''));
        }
    }
    public function registerindex(){
         $postData = Request::instance()->post();

        // 验证用户名是否存在
        $map = array('username'  => $postData['username']);
        $User = Admin::get($map);
        if(is_null($User)){
            $User = User::get($map);
        }
        if (!is_null($User)) {
            return $this->error('用户名已存在', url('index'));
        }
        $users=new User();
        $users->username=$postData['username'];
        $users->password=$postData['password'];
        $users->save($users->getData());
        return $this->success('注册成功',url('login/index'));
    }
    public function register(){
        return $this->fetch();
    }
}