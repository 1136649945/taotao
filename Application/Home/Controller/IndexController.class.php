<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

	//系统首页
    public function index(){
        $this->assign("scroll",D('Channelpicture')->picture("path","block=12 and hide=0"));//首页轮播图
        $channel = D('Channel')->lists("id,pid,url,gattr1,".TITLE,"hide=0 and status=1 and (block=30 or pid=63)");
        $this->assign('depset',$channel);//部门设置
        $this->display();
    }

}