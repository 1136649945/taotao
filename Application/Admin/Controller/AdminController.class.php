<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台首页控制器
 * 
 */
class AdminController extends Controller {
    /**
     * 后台控制器初始化
     */
    protected function _initialize(){
        // 获取当前用户ID
        if(defined('UID')) return ;
        define('UID',is_login());
        if( !UID ){// 还没登录 跳转到登录页面
            echo "<script type='text/javascript'>";
            echo "window.top.location.href='/admin.php/Login/login'";
            echo "</script>";
            exit();
        }
        // 是否是超级管理员
        //         define('IS_ROOT',   is_administrator());
        //         if(!IS_ROOT){
        //            $this->error('403:禁止访问');
        //         }
    }
    public function index(){
        $this->display();
    }
    
}
