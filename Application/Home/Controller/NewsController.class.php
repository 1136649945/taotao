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
class NewsController extends HomeController {

	//系统首页
    public function news(){
        $channel = D('Channel')->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,13,49)");
        $this->assign('crumb',$this->crumb($channel,"1,13,49"));//面包屑
        $channel = D('Channel')->getChannel("url,".TITLE,"hide=0 and status=1 and pid=13");
        $this->assign('news',$channel);//新闻通告菜单展示
        $picture = D('Channelpicture')->picture("path","block=20 and hide=0");
        $this->assign("picture",$picture);//banner图
        $picture = D('Channelpicture')->picture("path","block=51 and hide=0");
        $this->assign("scollpicture",$picture);//banner图
        $document = $this->document(TITLE,72,1);
        $this->assign("document1",$document);
        $document = $this->document(TITLE,73,1);
        $this->assign("document2",$document);
        $this->display();
    }
}