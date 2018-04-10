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

        $channel = D('Channel')->lists(true,"hide=0 and status=1 and block=1");
        $this->assign('channelf',$channel);//一级导航
        $channel = D('Channel')->lists(true,"hide=0 and status=1 and (block=10 or block=27)");
        $this->assign('channels',$channel);//二级导航
        $lang = substr(LANG_SET,0,2);
        if("zh"==$lang){
            $this->assign('title','title');
        }else{
            $this->assign('title','title'.$lang);
        }
        $this->display();
    }

}