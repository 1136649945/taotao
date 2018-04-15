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
class LinkController extends HomeController {

	//系统首页
    public function link(){
        $channel = D('Channel')->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,7,16)");
        $this->assign('crumb',$this->crumb($channel, "1,7,16"));//面包屑
        $channel = D('Channel')->lists("id,pid,url,".TITLE,"hide=0 and status=1 and (id=7 or block=26)");
        $this->assign('link',$channel);//友情链接菜单展示
        $channel = D('Channel')->lists("id,pid,url,".TITLE,"hide=0 and status=1 and (id=19 or block=25)");
        $this->assign('relate',$channel);//相关连接菜单展示
        $picture = D('Channelpicture')->picture("path","block=15 and hide=0");
        $this->assign("picture",$picture);//banner图
        $this->display();
    }
    //系统首页
    public function linklist(){
        $block = I("post.block",-1);
        if($block>0){
            $data = D("Channelpicture")->lists("url,path,title","hide=0 and block=".$block );
            $this->ajaxReturn($data,"json");
        }
    }
}