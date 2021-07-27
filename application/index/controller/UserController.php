<?php
namespace app\index\controller;
use think\Controller;
use think\Request; 
use think\Db;
use app\common\model\User;
use app\common\model\Klass;
use app\common\model\Record;
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
        if(!$Teacher->validate()->save($Teacher->getData())) {
            return $this->error('更新失败,金额不能为空', url('editmoney'));
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
    public function addrecord(){
        $id = Request::instance()->param('id/d');

        // 判断是否存在当前记录
        if (is_null($User = User::get($id))) {
            return $this->error('未找到ID为' . $id . '的记录');
        }

        // 取出班级列表
        $klasses = Klass::all();
        $this->assign('klasses', $klasses);

        $this->assign('User', $User);
        return $this->fetch();
    }
    public function insertrecord(){
        $name=input('post.name');
        $type=input('post.type');
        $klass=input('post.klass_id');
        $record=new Record;
        $record->name=$name;
        $record->type=$type;
        $record->klass_id=$klass;
        $record->user_id=session('id');
        $record->num=input('post.num');
        $record->another=input('post.another');
        $record->create_time=input('post.create_time');
        $user=User::get(session('id'));
        if($type==0){
            $user->money=$user->money-$record->num;
        }
        else{
            $user->money=$user->money+$record->num;
        }
        if(!$record->validate()->save($record->getData())){
            return $this->error('添加失败，字段不能为空',url('index'));
        }
        $user->save($user->getData());
        return $this->success('添加成功', url('index'));
    }
    public function addrecord1(){
        $id = Request::instance()->param('id/d');

        // 判断是否存在当前记录
        if (is_null($User = User::get($id))) {
            return $this->error('未找到ID为' . $id . '的记录');
        }

        // 取出班级列表
        $klasses = Klass::all();
        $this->assign('klasses', $klasses);

        $this->assign('User', $User);
        return $this->fetch();
    }
    public function mingxi(){
        $Usera=user::get(session('id'));
        $this->assign('User',$Usera);
        $id=session('id');
            $tag=Request::instance()->param('tag');

            $pageSize = 5; // 每页显示5条数据

            // 实例化Teacher
            $Teacher = new record; 
            // 定制查询信息
            $teache=new Record;
            $teachers=$Teacher->select();
            if(!is_null($tag)){
                if($tag==1){
                    $teachers=Db::name('Record')->whereTime('create_time', 'week')->select(); 
                }
                elseif ($tag==2) {
                    $teachers=Db::name('Record')->whereTime('create_time', 'month')->select(); 
                }
                elseif($tag==3){
                    $teachers=Db::name('Record')->whereTime('create_time', 'year')->select(); 
                }
                elseif($tag==4){
                    $teachers=$Teacher->select();
                }
            }
            $record=array();
           
            for($i=0;$i<count($teachers);$i++){
                $record[$i]=Record::get($teachers[$i]['id']) ;
            }
        
            $klasses=array();
            $records=array();
            for($i=0,$j=0;$i<count($teachers);$i++){
                if($record[$i]->user_id==$id){
                    $records[$j]=$record[$i];
                    $j++;
                }
            }


            // 按条件查询数据并调用分页
            $record = $Teacher->paginate($pageSize);

            // 向V层传数据
            $this->assign('records', $records);

            // 取回打包后的数据
            $htmls = $this->fetch();
        return $this->fetch();
    }
}