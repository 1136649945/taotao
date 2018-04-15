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
class OrganizationController extends HomeController {

    //系统首页
    public function organization(){
        $channel = D('Channel')->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,15,55)");
        $this->assign('crumb',$this->crumb($channel,"1,15,55"));//面包屑
        $channel = D('Channel')->lists("id,pid,url,".TITLE,"hide=0 and status=1 and (id=15 or block=27)");
        $this->assign('organization',$channel);//学生组织菜单展示
        $picture = D('Channelpicture')->picture("path","block=22 and hide=0");
        $this->assign("picture",$picture);//banner图
        $picture = D('Channelpicture')->picture("path","block=50 and hide=0");
        $this->assign("rollpicture",$picture);//banner图
        $this->display();
    }
}