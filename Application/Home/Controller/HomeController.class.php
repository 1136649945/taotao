<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}


    protected function _initialize(){
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置
        if(C('WEB_SITE_CLOSE')){
            $title = "title";
            $description = "description";
            if("zh"!=LANG_SET){
                $title = $title.LANG_SET;
                $description = $description.LANG_SET;
            }
            define('TITLE',$title);
            define('DESCR',$description);
            $this->assign("title",TITLE);
            $this->assign("descr",DESCR);
            $channel = D('Channel')->lists("id,pid,url,".TITLE,"hide=0 and status=1 and block=1");
            $this->assign('channelf',$channel);//一级导航
            $channel = D('Channel')->lists("id,pid,url,".TITLE,"hide=0 and status=1 and (block=10 or block=27)");
            $this->assign('channels',$channel);//二级导航
            $channel = D('Channel')->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and (block=10 or block=1)");
            $this->assign('channelfs',$channel);//手机端使用一级二级导航
            $channel = D("Channel")->getChannel("block,url,".TITLE,"hide=0 and status=1 and block in (31,32,33,34,36,37,38,39,40,41,42,43)");
            $this->assign("fixedchannel",$channel);//固定字段
        }
        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
    }

	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
		is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}

}
