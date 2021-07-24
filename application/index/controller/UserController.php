<?php
namespace app\index\controller;
use think\Controller;
use think\Request; 
use think\Db;
use app\common\model\User;
class UserController extends Index1Controller
{
    public function index(){

        return $this->fetch();
    }

}