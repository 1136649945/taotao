<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

/**
 * 分类模型
 */
class ChannelModel extends Model{

	/**
	 * 获取导航列表，支持多级导航
	 * @param  boolean $field 要列出的字段
	 * @return array          导航树
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function lists($field = true,$where="1=1"){
		$list = $this->cache(true,C('DATA_CACHE_TIME'))->field($field)->where($where)->order('sort')->select();
		return list_to_tree($list, 'id', 'pid', '_');
	}
	/**
	 * 获取导航列表，支持多级导航
	 * @param  boolean $field 要列出的字段
	 * @return array          导航树
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function getChannel($field = true,$where="1=1"){
	    return $this->cache(true,C('DATA_CACHE_TIME'))->field($field)->where($where)->order('sort')->select();
	}

}
