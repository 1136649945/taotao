<?php
namespace Common\Controller;

use Think\Controller;
class ExeclController extends Controller
{
    //导入
    public function read($file){  
        import("Org.Yufan.ExcelReader");
        $ExcelReader=new \ExcelReader();
        $arr=$ExcelReader->reader_excel($file);
    }

    //导出
    public function write($row){
    	import("ORG.Yufan.Excel");
    	$xls = new \Excel_XML('UTF-8', false, 'datalist');
    	$xls->addArray($row);
    	$xls->generateXML("data");
    }
}

