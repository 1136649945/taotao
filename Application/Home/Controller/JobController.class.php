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
class JobController extends HomeController {

    //系统首页
    public function job(){
        $channel = D('Channel')->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,14,51,84)");
        $this->assign('crumb',$this->crumb($channel,"84,1,14,51"));//面包屑
        $channel = D('Channel')->lists("id,pid,url,".TITLE,"hide=0 and status=1 and (id=14 or block=27)");
        $this->assign('job',$channel);//实习就业菜单展示
        $picture = D('Channelpicture')->picture("path","block=21 and hide=0");
        $this->assign("picture",$picture);//banner图
        $this->display();
    }
}