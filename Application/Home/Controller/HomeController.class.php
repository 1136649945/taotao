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
        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
            exit();
        }
        if(C('WEB_SITE_CLOSE')){
            $title = "title";
            $description = "description";
            $content = "content";
            if("zh"!=LANG_SET){
                $title = $title.LANG_SET;
                $description = $description.LANG_SET;
                $content = $content.LANG_SET;
            }
            define('TITLE',$title);
            define('DESCR',$description);
            define('CONTENT',$content);
            $this->assign("title",TITLE);
            $this->assign("descr",DESCR);
            $this->assign("lang_set",LANG_SET);
            //一级导航
            $channel = D('Channel')->lists("id,pid,target,url,".TITLE,"hide=0 and status=1 and block=1");
            $this->assign('channelf',$channel);
            //二级导航
            $channel = D('Channel')->lists("id,pid,target,url,".TITLE,"hide=0 and status=1 and (block=10 or block=27)");
            $this->assign('channels',$channel);
            //手机端使用一级二级导航
            $channel = D('Channel')->getChannel("id,target,pid,url,".TITLE,"hide=0 and status=1 and (block=10 or block=1)");
            $this->assign('channelfs',$channel);
            //固定字段
            $words = D("Words")->cache(true,C('DATA_CACHE_TIME'))->field("id,".TITLE)->order("id")->select();
            $word = array();
            foreach ($words as $val){
                $word["w".$val['id']] = $val[TITLE];
            }
            $this->assign("words",$word);
            $param = $_SERVER['QUERY_STRING']; 
            if( stripos($param,C("VAR_LANGUAGE")."=")===false){
                if(strlen($param)==0){
                    $param = C("VAR_LANGUAGE")."=".LANG_SET;
                }else{
                    $param = $param."&".C("VAR_LANGUAGE")."=".LANG_SET;
                }
                echo "<script type='text/javascript'> location.href='http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$param."'</script>";
            }
        }
    }

	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
		is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}
	
	/* 面包屑 */
	protected function crumb($arr,$ids){
	   $ids = split(",",$ids);
	   $crumb = array();
	   foreach ($ids as $val){
	       foreach($arr as $value){
	           if($val==$value["id"]){
	               array_push($crumb, $value);
	           }
	       }
	   }
	   return $crumb;
	}
}
