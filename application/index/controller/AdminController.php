<?php
namespace app\index\controller;
use think\Controller;
use think\Request; 
use think\Db;
use app\common\model\Admin;
class AdminController extends IndexController
{
    public function index(){

        return $this->fetch();
    }

}   