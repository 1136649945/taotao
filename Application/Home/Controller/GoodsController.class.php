<?php
namespace Home\Controller;
use Think\Controller;
class GoodsController extends Controller {
     /**
     * 控制器方法的访问
     * 网址/入口文件/分组/控制器/方法
     * http://localhost:8080/taotao/index.php/home/index/index
     */
    public function showlist(){
        //跨控制器访问
//         var_dump($_GET);
//         $mode = M("model");
//         echo $mode->max('name')."<br/>";
//         echo $mode->min('name')."<br/>";
//         echo $mode->sum('id')."<br/>";
//         echo $mode->count()."<br/>";
//         echo "<br/>";
//         var_dump($_POST);
//         $list = M()->query("select * from zscy_model");
//         $list = M()->execute("delete|update");
//         $list = M("model")->limit(3)->select();
        $list = M("model")->select();
        $this->assign("core",60);
        $this->assign("list_grid",$list);
        $this->display();
        
    }
    public function _empty(){
        //控制器方法找不到的空操作
        echo "非法操作";
    }
}