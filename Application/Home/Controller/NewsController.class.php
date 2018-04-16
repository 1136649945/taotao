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
    public function news($id=null,$category_id=72){
        $Cha = D('Channel');
        $Chap = D('Channelpicture');
        //面包屑
        $channel = $Cha->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,13,49)");
        $this->assign('crumb',$this->crumb($channel,"1,13,49"));
        //新闻通告菜单展示
        $channel = $Cha->getChannel("url,category_id,".TITLE,"hide=0 and status=1 and pid=13");
        $this->assign('news',$channel);
        //banner图
        $picture = $Chap->picture("path","block=20 and hide=0");
        $this->assign("picture",$picture);
        //轮播图
        $picture = $Chap->picture("path","block=51 and hide=0");
        $this->assign("scollpicture",$picture);
        //推荐新闻
        $Doc = D("Document");
        $document = $Doc->doclists("m.category_id in (72,73)","m.create_time desc","0,6");
        $this->assign("document",$document);
//         //推荐新闻
//         $document = $this->document(TITLE,73,1);
//         $this->assign("document2",$document);
        //分类id
        $this->assign("category_id",$category_id);
        //文章id
        $this->assign("id",$id);
        //获取文章
        if($id!=null){
            $this->assign("content",$Doc->docdetail("m.id=".$id));
        }else{
            $data = $Doc->doclists("m.category_id=".$category_id,"m.create_time desc");
            $this->assign("contarr",json_encode($data));
        }
        $this->display();
    }
}