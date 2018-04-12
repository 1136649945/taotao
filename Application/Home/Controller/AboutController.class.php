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
class AboutController extends HomeController {

	//系统首页
    public function about(){
        $channel = D('Channel')->lists("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,25)");
        $this->assign('crumb',$channel);//首页关于我们导航
        $channel = D('Channel')->lists("id,pid,url,".TITLE,"hide=0 and status=1 and (id=2 or block=27)");
        $this->assign('about',$channel);//关于我们菜单展示
        $picture = D('Channelpicture')->picture("path","block=13 and hide=0");
        $this->assign("picture",$picture);//banner图
        $document =  D('Document')->lists("create_time,cover_id,".TITLE.",".DESCR,"category_id=99 and display=1 and status=1","0,3");
        $this->assign('document',$document);//校院一角
        
        
//         $document =  D('Document')->lists("create_time,cover_id,".TITLE.",".DESCR,"category_id=98 and display=1 and status=1","0,6");
//         $this->assign('document',$document);//特色项目
//         $document =  D('Document')->lists("create_time,cover_id,".TITLE.",".DESCR,"category_id=73 and display=1 and status=1","0,6");
//         $this->assign('document1',$document);//通知公告
//         $document =  D('Document')->lists("create_time,cover_id,".TITLE.",".DESCR,"category_id=72 and display=1 and status=1","0,3");
//         $this->assign('document2',$document);//学院新闻
        $this->display();
    }

}