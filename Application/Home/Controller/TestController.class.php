<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends Controller {
     /**
     * 控制器方法的访问
     * 网址/入口文件/分组/控制器/方法
     * http://localhost:8080/taotao/index.php/home/index/index
     */
    public function test(){
        echo __SELF__."<br/>";
        //读取config.php内容
        print (C("TMPL_PARSE_STRING")['__STATIC__'])."<br/>";
    }
}