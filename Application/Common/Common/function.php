<?php

// +----------------------------------------------------------------------

// | OneThink [ WE CAN DO IT JUST THINK IT ]

// +----------------------------------------------------------------------

// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.

// +----------------------------------------------------------------------

// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>

// +----------------------------------------------------------------------

// OneThink常量定义
const ONETHINK_VERSION = '1.1.141101';


/**
 * 系统公共库文件
 *
 * 主要定义系统公共函数库
 */

/**
 *
 * 检测用户是否登录
 *
 * @return integer 0-未登录，大于0-当前登录用户ID
 *        
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function is_login()
{
    if (empty(session('usercode'))) {
        return false;
    } else {
        return true;
    }
}


/**
 *
 * 检测当前用户是否为管理员
 *
 * @return boolean true-管理员，false-非管理员
 *        
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function is_admin()
{
    return session('role') ? true : false;
}
/**
 * 密码加密
 * @param $password
 */
function en_password($password){
   return base64_encode($password);
}
/**
 * 密码解密
 * @param  $password
 * @return string
 */
function de_password($password){
    return base64_decode($password);
}

/**
 *
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 *
 * @param string $str
 *            要分割的字符串
 *            
 * @param string $glue
 *            分割符
 *            
 * @return array
 *
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function str2arr($str, $glue = ',')
{
    return explode($glue, $str);
}

/**
 *
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 *
 * @param array $arr
 *            要连接的数组
 *            
 * @param string $glue
 *            分割符
 *            
 * @return string
 *
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function arr2str($arr, $glue = ',')
{
    return implode($glue, $arr);
}

/**
 *
 * 字符串截取，支持中文和其他编码
 *
 * @static
 *
 * @access public
 *        
 * @param string $str
 *            需要转换的字符串
 *            
 * @param string $start
 *            开始位置
 *            
 * @param string $length
 *            截取长度
 *            
 * @param string $charset
 *            编码格式
 *            
 * @param string $suffix
 *            截断显示字符
 *            
 * @return string
 *
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    if (function_exists("mb_substr"))
        
        $slice = mb_substr($str, $start, $length, $charset);
    
    elseif (function_exists('iconv_substr')) {
        
        $slice = iconv_substr($str, $start, $length, $charset);
        
        if (false === $slice) {
            
            $slice = '';
        }
    } else {
        
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        
        preg_match_all($re[$charset], $str, $match);
        
        $slice = join("", array_slice($match[0], $start, $length));
    }
    
    return $suffix ? $slice . '...' : $slice;
}

/**
 *
 * 系统加密方法
 *
 * @param string $data
 *            要加密的字符串
 *            
 * @param string $key
 *            加密密钥
 *            
 * @param int $expire
 *            过期时间 单位 秒
 *            
 * @return string
 *
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function think_encrypt($data, $key = '', $expire = 0)
{
    $key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    
    $data = base64_encode($data);
    
    $x = 0;
    
    $len = strlen($data);
    
    $l = strlen($key);
    
    $char = '';
    
    for ($i = 0; $i < $len; $i ++) {
        
        if ($x == $l)
            $x = 0;
        
        $char .= substr($key, $x, 1);
        
        $x ++;
    }
    
    $str = sprintf('%010d', $expire ? $expire + time() : 0);
    
    for ($i = 0; $i < $len; $i ++) {
        
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
    }
    
    return str_replace(array(
        '+',
        '/',
        '='
    ), array(
        '-',
        '_',
        ''
    ), base64_encode($str));
}

/**
 *
 * 系统解密方法
 *
 * @param string $data
 *            要解密的字符串 （必须是think_encrypt方法加密的字符串）
 *            
 * @param string $key
 *            加密密钥
 *            
 * @return string
 *
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function think_decrypt($data, $key = '')
{
    $key = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    
    $data = str_replace(array(
        '-',
        '_'
    ), array(
        '+',
        '/'
    ), $data);
    
    $mod4 = strlen($data) % 4;
    
    if ($mod4) {
        
        $data .= substr('====', $mod4);
    }
    
    $data = base64_decode($data);
    
    $expire = substr($data, 0, 10);
    
    $data = substr($data, 10);
    
    if ($expire > 0 && $expire < time()) {
        
        return '';
    }
    
    $x = 0;
    
    $len = strlen($data);
    
    $l = strlen($key);
    
    $char = $str = '';
    
    for ($i = 0; $i < $len; $i ++) {
        
        if ($x == $l)
            $x = 0;
        
        $char .= substr($key, $x, 1);
        
        $x ++;
    }
    
    for ($i = 0; $i < $len; $i ++) {
        
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    
    return base64_decode($str);
}

/**
 *
 * 数据签名认证
 *
 * @param array $data
 *            被认证的数据
 *            
 * @return string 签名
 *        
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function data_auth_sign($data)
{
    
    // 数据类型检测
    if (! is_array($data)) {
        
        $data = (array) $data;
    }
    
    ksort($data); // 排序
    
    $code = http_build_query($data); // url编码并生成query字符串
    
    $sign = sha1($code); // 生成签名
    
    return $sign;
}

/**
 *
 * 对查询结果集进行排序
 *
 * @access public
 *        
 * @param array $list
 *            查询结果
 *            
 * @param string $field
 *            排序的字段名
 *            
 * @param array $sortby
 *            排序类型
 *            
 *            asc正向排序 desc逆向排序 nat自然排序
 *            
 * @return array
 *
 *
 */
function list_sort_by($list, $field, $sortby = 'asc')
{
    if (is_array($list)) {
        
        $refer = $resultSet = array();
        
        foreach ($list as $i => $data)
            
            $refer[$i] = &$data[$field];
        
        switch ($sortby) {
            
            case 'asc': // 正向排序
                
                asort($refer);
                
                break;
            
            case 'desc': // 逆向排序
                
                arsort($refer);
                
                break;
            
            case 'nat': // 自然排序
                
                natcasesort($refer);
                
                break;
        }
        
        foreach ($refer as $key => $val)
            
            $resultSet[] = &$list[$key];
        
        return $resultSet;
    }
    
    return false;
}

/**
 *
 * 把返回的数据集转换成Tree
 *
 * @param array $list
 *            要转换的数据集
 *            
 * @param string $pid
 *            parent标记字段
 *            
 * @param string $level
 *            level标记字段
 *            
 * @return array
 *
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    
    // 创建Tree
    $tree = array();
    
    if (is_array($list)) {
        
        // 创建基于主键的数组引用
        
        $refer = array();
        
        foreach ($list as $key => $data) {
            
            $refer[$data[$pk]] = & $list[$key];
        }
        
        foreach ($list as $key => $data) {
            
            // 判断是否存在parent
            
            $parentId = $data[$pid];
            
            if ($root == $parentId) {
                
                $tree[] = & $list[$key];
            } else {
                
                if (isset($refer[$parentId])) {
                    
                    $parent = & $refer[$parentId];
                    
                    $parent[$child][] = & $list[$key];
                }
            }
        }
    }
    
    return $tree;
}

/**
 *
 * 将list_to_tree的树还原成列表
 *
 * @param array $tree
 *            原来的树
 *            
 * @param string $child
 *            孩子节点的键
 *            
 * @param string $order
 *            排序显示的键，一般是主键 升序排列
 *            
 * @param array $list
 *            过渡用的中间数组，
 *            
 * @return array 返回排过序的列表数组
 *        
 * @author yangweijie <yangweijiester@gmail.com>
 *        
 */
function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array())
{
    if (is_array($tree)) {
        
        foreach ($tree as $key => $value) {
            
            $reffer = $value;
            
            if (isset($reffer[$child])) {
                
                unset($reffer[$child]);
                
                tree_to_list($value[$child], $child, $order, $list);
            }
            
            $list[] = $reffer;
        }
        
        $list = list_sort_by($list, $order, $sortby = 'asc');
    }
    
    return $list;
}

/**
 *
 * 格式化字节大小
 *
 * @param number $size
 *            字节数
 *            
 * @param string $delimiter
 *            数字和单位分隔符
 *            
 * @return string 格式化后的带单位的大小
 *        
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function format_bytes($size, $delimiter = '')
{
    $units = array(
        'B',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB'
    );
    
    for ($i = 0; $size >= 1024 && $i < 5; $i ++)
        $size /= 1024;
    
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 *
 * 设置跳转页面URL
 *
 * 使用函数再次封装，方便以后选择不同的存储方式（目前使用cookie存储）
 *
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function set_redirect_url($url)
{
    cookie('redirect_url', $url);
}

/**
 *
 * 获取跳转页面URL
 *
 * @return string 跳转页URL
 *        
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function get_redirect_url()
{
    $url = cookie('redirect_url');
    
    return empty($url) ? __APP__ : $url;
}

/**
 *
 * 处理插件钩子
 *
 * @param string $hook
 *            钩子名称
 *            
 * @param mixed $params
 *            传入参数
 *            
 * @return void
 *
 */
function hook($hook, $params = array())
{
    \Think\Hook::listen($hook, $params);
}

/**
 *
 * 获取插件类的类名
 *
 * @param strng $name
 *            插件名
 *            
 */
function get_addon_class($name)
{
    $class = "Addons\\{$name}\\{$name}Addon";
    
    return $class;
}

/**
 *
 * 获取插件类的配置文件数组
 *
 * @param string $name
 *            插件名
 *            
 */
function get_addon_config($name)
{
    $class = get_addon_class($name);
    
    if (class_exists($class)) {
        
        $addon = new $class();
        
        return $addon->getConfig();
    } else {
        
        return array();
    }
}

/**
 *
 * 插件显示内容里生成访问插件的url
 *
 * @param string $url
 *            url
 *            
 * @param array $param
 *            参数
 *            
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function addons_url($url, $param = array())
{
    $url = parse_url($url);
    
    $case = C('URL_CASE_INSENSITIVE');
    
    $addons = $case ? parse_name($url['scheme']) : $url['scheme'];
    
    $controller = $case ? parse_name($url['host']) : $url['host'];
    
    $action = trim($case ? strtolower($url['path']) : $url['path'], '/');
    
    /* 解析URL带的参数 */
    
    if (isset($url['query'])) {
        
        parse_str($url['query'], $query);
        
        $param = array_merge($query, $param);
    }
    
    /* 基础参数 */
    
    $params = array(
        
        '_addons' => $addons,
        
        '_controller' => $controller,
        
        '_action' => $action
    );
    
    $params = array_merge($params, $param); // 添加额外参数
    
    return U('Addons/execute', $params);
}

/**
 *
 * 时间戳格式化
 *
 * @param int $time            
 *
 * @return string 完整的时间显示
 *        
 * @author huajie <banhuajie@163.com>
 *        
 */
function time_format($time = NULL, $format = 'Y-m-d H:i')
{
    $time = $time === NULL ? NOW_TIME : intval($time);
    
    return date($format, $time);
}

/**
 *
 * 根据用户ID获取用户名
 *
 * @param integer $uid
 *            用户ID
 *            
 * @return string 用户名
 *        
 */
function get_username($uid = 0)
{
    static $list;
    
    if (! ($uid && is_numeric($uid))) { // 获取当前登录用户名
        
        return session('user_auth.username');
    }
    
    /* 获取缓存数据 */
    
    if (empty($list)) {
        
        $list = S('sys_active_user_list');
    }
    
    /* 查找用户信息 */
    
    $key = "u{$uid}";
    
    if (isset($list[$key])) { // 已缓存，直接使用
        
        $name = $list[$key];
    } else { // 调用接口获取用户信息
        
        $User = new User\Api\UserApi();
        
        $info = $User->info($uid);
        
        if ($info && isset($info[1])) {
            
            $name = $list[$key] = $info[1];
            
            /* 缓存用户 */
            
            $count = count($list);
            
            $max = C('USER_MAX_CACHE');
            
            while ($count -- > $max) {
                
                array_shift($list);
            }
            
            S('sys_active_user_list', $list);
        } else {
            
            $name = '';
        }
    }
    
    return $name;
}

/**
 *
 * 根据用户ID获取用户昵称
 *
 * @param integer $uid
 *            用户ID
 *            
 * @return string 用户昵称
 *        
 */
function get_nickname($uid = 0)
{
    static $list;
    
    if (! ($uid && is_numeric($uid))) { // 获取当前登录用户名
        
        return session('user_auth.username');
    }
    
    /* 获取缓存数据 */
    
    if (empty($list)) {
        
        $list = S('sys_user_nickname_list');
    }
    
    /* 查找用户信息 */
    
    $key = "u{$uid}";
    
    if (isset($list[$key])) { // 已缓存，直接使用
        
        $name = $list[$key];
    } else { // 调用接口获取用户信息
        
        $info = M('Member')->field('nickname')->find($uid);
        
        if ($info !== false && $info['nickname']) {
            
            $nickname = $info['nickname'];
            
            $name = $list[$key] = $nickname;
            
            /* 缓存用户 */
            
            $count = count($list);
            
            $max = C('USER_MAX_CACHE');
            
            while ($count -- > $max) {
                
                array_shift($list);
            }
            
            S('sys_user_nickname_list', $list);
        } else {
            
            $name = '';
        }
    }
    
    return $name;
}

/**
 *
 * 获取分类信息并缓存分类
 *
 * @param integer $id
 *            分类ID
 *            
 * @param string $field
 *            要获取的字段名
 *            
 * @return string 分类信息
 *        
 */
function get_category($id, $field = null)
{
    static $list;
    
    /* 非法分类ID */
    
    if (empty($id) || ! is_numeric($id)) {
        
        return '';
    }
    
    /* 读取缓存数据 */
    
    if (empty($list)) {
        
        $list = S('sys_category_list');
    }
    
    /* 获取分类名称 */
    
    if (! isset($list[$id])) {
        
        $cate = M('Category')->find($id);
        
        if (! $cate || 1 != $cate['status']) { // 不存在分类，或分类被禁用
            
            return '';
        }
        
        $list[$id] = $cate;
        
        S('sys_category_list', $list); // 更新缓存
    }
    
    return is_null($field) ? $list[$id] : $list[$id][$field];
}

// 栏目列表
function category_list($id)
{
    if (empty($id))
        return false;
    
    $pid = M('Category')->where(array(
        'status' => 1,
        'id' => $id
    ))->getField('pid');
    
    if ($pid != 0) {
        
        $list = M('Category')->where(array(
            'status' => 1,
            'pid' => $pid
        ))
            ->order('sort desc,id asc')
            ->select();
    } else {
        
        $list = M('Category')->where(array(
            'status' => 1,
            'pid' => $id
        ))
            ->order('sort desc,id asc')
            ->select();
    }
    
    return $list;
}

/* 根据ID获取分类标识 */
function get_category_name($id)
{
    return get_category($id, 'name');
}

/* 根据ID获取分类描述 */
function get_category_des($id)
{
    return get_category($id, 'description');
}

/* 根据ID获取分类名称 */
function get_category_title($id)
{
    return get_category($id, 'title');
}

function get_category_icon($id)
{
    return get_category($id, 'icon');
}

function get_category_fuhao($id)
{
    return get_category($id, 'fuhao');
}

/**
 * 获取顶级模型信息
 */
function get_top_model($model_id = null)
{
    $map = array(
        'status' => 1,
        'extend' => 0
    );
    
    if (! is_null($model_id)) {
        
        $map['id'] = array(
            'neq',
            $model_id
        );
    }
    
    $model = M('Model')->where($map)
        ->field(true)
        ->select();
    
    foreach ($model as $value) {
        
        $list[$value['id']] = $value;
    }
    
    return $list;
}

/**
 *
 * 获取文档模型信息
 *
 * @param integer $id
 *            模型ID
 *            
 * @param string $field
 *            模型字段
 *            
 * @return array
 *
 */
function get_document_model($id = null, $field = null)
{
    static $list;
    
    /* 非法分类ID */
    
    if (! (is_numeric($id) || is_null($id))) {
        
        return '';
    }
    
    /* 读取缓存数据 */
    
    if (empty($list)) {
        
        $list = S('DOCUMENT_MODEL_LIST');
    }
    
    /* 获取模型名称 */
    
    if (empty($list)) {
        
        $map = array(
            'status' => 1,
            'extend' => 1
        );
        
        $model = M('Model')->where($map)
            ->field(true)
            ->select();
        
        foreach ($model as $value) {
            
            $list[$value['id']] = $value;
        }
        
        S('DOCUMENT_MODEL_LIST', $list); // 更新缓存
    }
    
    /* 根据条件返回数据 */
    
    if (is_null($id)) {
        
        return $list;
    } elseif (is_null($field)) {
        
        return $list[$id];
    } else {
        
        return $list[$id][$field];
    }
}

/**
 *
 * 解析UBB数据
 *
 * @param string $data
 *            UBB字符串
 *            
 * @return string 解析为HTML的数据
 *        
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 *        
 */
function ubb($data)
{
    
    // TODO: 待完善，目前返回原始数据
    return $data;
}

/**
 *
 * 记录行为日志，并执行该行为的规则
 *
 * @param string $action
 *            行为标识
 *            
 * @param string $model
 *            触发行为的模型名
 *            
 * @param int $record_id
 *            触发行为的记录id
 *            
 * @param int $user_id
 *            执行行为的用户id
 *            
 * @return boolean
 *
 * @author huajie <banhuajie@163.com>
 *        
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null)
{
    
    // 参数检查
    if (empty($action) || empty($model) || empty($record_id)) {
        
        return '参数不能为空';
    }
    
    if (empty($user_id)) {
        
        $user_id = is_login();
    }
    
    // 查询行为,判断是否执行
    
    $action_info = M('Action')->getByName($action);
    
    if ($action_info['status'] != 1) {
        
        return '该行为被禁用或删除';
    }
    
    // 插入行为日志
    
    $data['action_id'] = $action_info['id'];
    
    $data['user_id'] = $user_id;
    
    $data['action_ip'] = ip2long(get_client_ip());
    
    $data['model'] = $model;
    
    $data['record_id'] = $record_id;
    
    $data['create_time'] = NOW_TIME;
    
    // 解析日志规则,生成日志备注
    
    if (! empty($action_info['log'])) {
        
        if (preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)) {
            
            $log['user'] = $user_id;
            
            $log['record'] = $record_id;
            
            $log['model'] = $model;
            
            $log['time'] = NOW_TIME;
            
            $log['data'] = array(
                'user' => $user_id,
                'model' => $model,
                'record' => $record_id,
                'time' => NOW_TIME
            );
            
            foreach ($match[1] as $value) {
                
                $param = explode('|', $value);
                
                if (isset($param[1])) {
                    
                    $replace[] = call_user_func($param[1], $log[$param[0]]);
                } else {
                    
                    $replace[] = $log[$param[0]];
                }
            }
            
            $data['remark'] = str_replace($match[0], $replace, $action_info['log']);
        } else {
            
            $data['remark'] = $action_info['log'];
        }
    } else {
        
        // 未定义日志规则，记录操作url
        
        $data['remark'] = '操作url：' . $_SERVER['REQUEST_URI'];
    }
    
    M('ActionLog')->add($data);
    
    if (! empty($action_info['rule'])) {
        
        // 解析行为
        
        $rules = parse_action($action, $user_id);
        
        // 执行行为
        
        $res = execute_action($rules, $action_info['id'], $user_id);
    }
}

/**
 *
 * 解析行为规则
 *
 * 规则定义 table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 *
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 *
 * field->要操作的字段；
 *
 * condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 *
 * rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 *
 * cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 *
 * max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 *
 * 单个行为后可加 ； 连接其他规则
 *
 * @param string $action
 *            行为id或者name
 *            
 * @param int $self
 *            替换规则里的变量为执行用户的id
 *            
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 *        
 * @author huajie <banhuajie@163.com>
 *        
 */
function parse_action($action = null, $self)
{
    if (empty($action)) {
        
        return false;
    }
    
    // 参数支持id或者name
    
    if (is_numeric($action)) {
        
        $map = array(
            'id' => $action
        );
    } else {
        
        $map = array(
            'name' => $action
        );
    }
    
    // 查询行为信息
    
    $info = M('Action')->where($map)->find();
    
    if (! $info || $info['status'] != 1) {
        
        return false;
    }
    
    // 解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
    
    $rules = $info['rule'];
    
    $rules = str_replace('{$self}', $self, $rules);
    
    $rules = explode(';', $rules);
    
    $return = array();
    
    foreach ($rules as $key => &$rule) {
        
        $rule = explode('|', $rule);
        
        foreach ($rule as $k => $fields) {
            
            $field = empty($fields) ? array() : explode(':', $fields);
            
            if (! empty($field)) {
                
                $return[$key][$field[0]] = $field[1];
            }
        }
        
        // cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
        
        if (! array_key_exists('cycle', $return[$key]) || ! array_key_exists('max', $return[$key])) {
            
            unset($return[$key]['cycle'], $return[$key]['max']);
        }
    }
    
    return $return;
}

/**
 *
 * 执行行为
 *
 * @param array $rules
 *            解析后的规则数组
 *            
 * @param int $action_id
 *            行为id
 *            
 * @param array $user_id
 *            执行的用户id
 *            
 * @return boolean false 失败 ， true 成功
 *        
 * @author huajie <banhuajie@163.com>
 *        
 */
function execute_action($rules = false, $action_id = null, $user_id = null)
{
    if (! $rules || empty($action_id) || empty($user_id)) {
        
        return false;
    }
    
    $return = true;
    
    foreach ($rules as $rule) {
        
        // 检查执行周期
        
        $map = array(
            'action_id' => $action_id,
            'user_id' => $user_id
        );
        
        $map['create_time'] = array(
            'gt',
            NOW_TIME - intval($rule['cycle']) * 3600
        );
        
        $exec_count = M('ActionLog')->where($map)->count();
        
        if ($exec_count > $rule['max']) {
            
            continue;
        }
        
        // 执行数据库操作
        
        $Model = M(ucfirst($rule['table']));
        
        $field = $rule['field'];
        
        $res = $Model->where($rule['condition'])->setField($field, array(
            'exp',
            $rule['rule']
        ));
        
        if (! $res) {
            
            $return = false;
        }
    }
    
    return $return;
}

// 基于数组创建目录和文件
function create_dir_or_files($files)
{
    foreach ($files as $key => $value) {
        
        if (substr($value, - 1) == '/') {
            
            mkdir($value);
        } else {
            
            @file_put_contents($value, '');
        }
    }
}

if (! function_exists('array_column')) {

    function array_column(array $input, $columnKey, $indexKey = null)
    {
        $result = array();
        
        if (null === $indexKey) {
            
            if (null === $columnKey) {
                
                $result = array_values($input);
            } else {
                
                foreach ($input as $row) {
                    
                    $result[] = $row[$columnKey];
                }
            }
        } else {
            
            if (null === $columnKey) {
                
                foreach ($input as $row) {
                    
                    $result[$row[$indexKey]] = $row;
                }
            } else {
                
                foreach ($input as $row) {
                    
                    $result[$row[$indexKey]] = $row[$columnKey];
                }
            }
        }
        
        return $result;
    }
}

/**
 *
 * 获取表名（不含表前缀）
 *
 * @param string $model_id            
 *
 * @return string 表名
 *        
 * @author huajie <banhuajie@163.com>
 *        
 */
function get_table_name($model_id = null)
{
    if (empty($model_id)) {
        
        return false;
    }
    
    $Model = M('Model');
    
    $name = '';
    
    $info = $Model->getById($model_id);
    
    if ($info['extend'] != 0) {
        
        $name = $Model->getFieldById($info['extend'], 'name') . '_';
    }
    
    $name .= $info['name'];
    
    return $name;
}

/**
 *
 * 获取属性信息并缓存
 *
 * @param integer $id
 *            属性ID
 *            
 * @param string $field
 *            要获取的字段名
 *            
 * @return string 属性信息
 *        
 */
function get_model_attribute($model_id, $group = true, $fields = true)
{
    static $list;
    
    /* 非法ID */
    
    if (empty($model_id) || ! is_numeric($model_id)) {
        
        return '';
    }
    
    /* 获取属性 */
    
    if (! isset($list[$model_id])) {
        
        $map = array(
            'model_id' => $model_id
        );
        
        $extend = M('Model')->getFieldById($model_id, 'extend');
        
        if ($extend) {
            
            $map = array(
                'model_id' => array(
                    "in",
                    array(
                        $model_id,
                        $extend
                    )
                )
            );
        }
        
        $info = M('Attribute')->where($map)
            ->field($fields)
            ->select();
        
        $list[$model_id] = $info;
    }
    
    $attr = array();
    
    if ($group) {
        
        foreach ($list[$model_id] as $value) {
            
            $attr[$value['id']] = $value;
        }
        
        $model = M("Model")->field("field_sort,attribute_list,attribute_alias")->find($model_id);
        
        $attribute = explode(",", $model['attribute_list']);
        
        if (empty($model['field_sort'])) { // 未排序
            
            $group = array(
                1 => array_merge($attr)
            );
        } else {
            
            $group = json_decode($model['field_sort'], true);
            
            $keys = array_keys($group);
            
            foreach ($group as &$value) {
                
                foreach ($value as $key => $val) {
                    
                    $value[$key] = $attr[$val];
                    
                    unset($attr[$val]);
                }
            }
            
            if (! empty($attr)) {
                
                foreach ($attr as $key => $val) {
                    
                    if (! in_array($val['id'], $attribute)) {
                        
                        unset($attr[$key]);
                    }
                }
                
                $group[$keys[0]] = array_merge($group[$keys[0]], $attr);
            }
        }
        
        if (! empty($model['attribute_alias'])) {
            
            $alias = preg_split('/[;\r\n]+/s', $model['attribute_alias']);
            
            $fields = array();
            
            foreach ($alias as &$value) {
                
                $val = explode(':', $value);
                
                $fields[$val[0]] = $val[1];
            }
            
            foreach ($group as &$value) {
                
                foreach ($value as $key => $val) {
                    
                    if (! empty($fields[$val['name']])) {
                        
                        $value[$key]['title'] = $fields[$val['name']];
                    }
                }
            }
        }
        
        $attr = $group;
    } else {
        
        foreach ($list[$model_id] as $value) {
            
            $attr[$value['name']] = $value;
        }
    }
    
    return $attr;
}

/**
 *
 * 调用系统的API接口方法（静态方法）
 *
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 *
 * api('Admin/User/getName','id=5'); 调用Admin模块的User接口
 *
 * @param string $name
 *            格式 [模块名]/接口名/方法名
 *            
 * @param array|string $vars
 *            参数
 *            
 */
function api($name, $vars = array())
{
    $array = explode('/', $name);
    
    $method = array_pop($array);
    
    $classname = array_pop($array);
    
    $module = $array ? array_pop($array) : 'Common';
    
    $callback = $module . '\\Api\\' . $classname . 'Api::' . $method;
    
    if (is_string($vars)) {
        
        parse_str($vars, $vars);
    }
    
    return call_user_func_array($callback, $vars);
}

/**
 *
 * 根据条件字段获取指定表的数据
 *
 * @param mixed $value
 *            条件，可用常量或者数组
 *            
 * @param string $condition
 *            条件字段
 *            
 * @param string $field
 *            需要返回的字段，不传则返回整个数据
 *            
 * @param string $table
 *            需要查询的表
 *            
 * @author huajie <banhuajie@163.com>
 *        
 */
function get_table_field($value = null, $condition = 'id', $field = null, $table = null)
{
    if (empty($value) || empty($table)) {
        
        return false;
    }
    
    // 拼接参数
    
    $map[$condition] = $value;
    
    $info = M(ucfirst($table))->where($map);
    
    if (empty($field)) {
        
        $info = $info->field(true)->find();
    } else {
        
        $info = $info->getField($field);
    }
    
    return $info;
}

/**
 *
 * 获取链接信息
 *
 * @param int $link_id            
 *
 * @param string $field            
 *
 * @return 完整的链接信息或者某一字段
 *
 * @author huajie <banhuajie@163.com>
 *        
 */
function get_link($link_id = null, $field = 'url')
{
    $link = '';
    
    if (empty($link_id)) {
        
        return $link;
    }
    
    $link = M('Url')->getById($link_id);
    
    if (empty($field)) {
        
        return $link;
    } else {
        
        return $link[$field];
    }
}

/**
 *
 * 获取文档封面图片
 *
 * @param int $cover_id            
 *
 * @param string $field            
 *
 * @return 完整的数据 或者 指定的$field字段值
 *        
 * @author huajie <banhuajie@163.com>
 *        
 */
function get_cover($cover_id, $field = null)
{
    if (empty($cover_id)) {
        
        // return false;
        
        return __ROOT__ . '/Public/static/zj/tu/tu81.jpg';
    }
    
    $picture = M('Picture')->where(array(
        'status' => 1
    ))->getById($cover_id);
    
    if ($field == 'path') {
        
        if (! empty($picture['url'])) {
            
            $picture['path'] = $picture['url'];
        } else {
            
            $picture['path'] = __ROOT__ . $picture['path'];
        }
    }
    
    return empty($field) ? $picture : $picture[$field];
}

/**
 *
 * 检查$pos(推荐位的值)是否包含指定推荐位$contain
 *
 * @param number $pos
 *            推荐位的值
 *            
 * @param number $contain
 *            指定推荐位
 *            
 * @return boolean true 包含 ， false 不包含
 *        
 * @author huajie <banhuajie@163.com>
 *        
 */
function check_document_position($pos = 0, $contain = 0)
{
    if (empty($pos) || empty($contain)) {
        
        return false;
    }
    
    // 将两个参数进行按位与运算，不为0则表示$contain属于$pos
    
    $res = $pos & $contain;
    
    if ($res !== 0) {
        
        return true;
    } else {
        
        return false;
    }
}

/**
 *
 * 获取数据的所有子孙数据的id值
 *
 * @author 朱亚杰 <xcoolcc@gmail.com>
 *        
 */
function get_stemma($pids, Model &$model, $field = 'id')
{
    $collection = array();
    
    // 非空判断
    
    if (empty($pids)) {
        
        return $collection;
    }
    
    if (is_array($pids)) {
        
        $pids = trim(implode(',', $pids), ',');
    }
    
    $result = $model->field($field)
        ->where(array(
        'pid' => array(
            'IN',
            (string) $pids
        )
    ))
        ->select();
    
    $child_ids = array_column((array) $result, 'id');
    
    while (! empty($child_ids)) {
        
        $collection = array_merge($collection, $result);
        
        $result = $model->field($field)
            ->where(array(
            'pid' => array(
                'IN',
                $child_ids
            )
        ))
            ->select();
        
        $child_ids = array_column((array) $result, 'id');
    }
    
    return $collection;
}

/**
 *
 * 验证分类是否允许发布内容
 *
 * @param integer $id
 *            分类ID
 *            
 * @return boolean true-允许发布内容，false-不允许发布内容
 *        
 */
function check_category($id)
{
    if (is_array($id)) {
        
        $id['type'] = ! empty($id['type']) ? $id['type'] : 2;
        
        $type = get_category($id['category_id'], 'type');
        
        $type = explode(",", $type);
        
        return in_array($id['type'], $type);
    } else {
        
        $publish = get_category($id, 'allow_publish');
        
        return $publish ? true : false;
    }
}

/**
 *
 * 检测分类是否绑定了指定模型
 *
 * @param array $info
 *            模型ID和分类ID数组
 *            
 * @return boolean true-绑定了模型，false-未绑定模型
 *        
 */
function check_category_model($info)
{
    $cate = get_category($info['category_id']);
    
    $array = explode(',', $info['pid'] ? $cate['model_sub'] : $cate['model']);
    
    return in_array($info['model_id'], $array);
}

function get_adposname_fromid($id, $name = 'title')
{
    return M('Adspos')->where('pid=' . $id)->getField($name);
}

// 根据广告位id调取广告信息
function get_ads_fromid($id, $limit)
{
    $return = M('Ads')->where(array(
        'pid' => $id
    ))
        ->order('level asc')
        ->limit($limit)
        ->select();
    
    return $return;
}

function is_email($email)
{
    return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

// 根据文章id获取更多信息
function get_moreinfo($id, $field, $base = 0)
{
    if (empty($id))
        return false;
    
    $data1 = M('Document')->where(array(
        'id' => $id
    ))
        ->limit('1')
        ->select();
    
    $data2 = M('Model')->where(array(
        'id' => $data1[0]['model_id']
    ))
        ->limit('1')
        ->select();
    
    $data3 = M('Document' . ucfirst($data2[0]['name']))->where(array(
        'id' => $id
    ))
        ->limit('1')
        ->select();
    
    if (empty($field)) 

    {
        
        if ($base) {
            
            return $data1[0];
        } else {
            
            return $data3[0];
        }
    } else {
        
        if ($base) {
            
            return $data1[0][$field];
        } else {
            
            return $data3[0][$field];
        }
    }
}

function get_media($media_id, $field = null)
{
    $root = C('DOWNLOAD_UPLOAD.rootPath');
    
    if (empty($media_id)) {
        
        return false;
    }
    
    $picture = M('File')->where(array(
        'id' => $media_id
    ))->find();
    
    if ($field == 'path') {
        
        if (! empty($picture['savepath'])) {
            
            $picture['path'] = $root . $picture['savepath'] . $picture['savename'];
        }
    }
    
    return empty($field) ? $picture : $picture[$field];
}

function getpageinfo($cate_id)
{
    if ($cate_id) {
        
        return M('Page')->where(array(
            'cate_id' => intval($cate_id)
        ))->find();
    } else {
        
        return false;
    }
}

function getchildcate($cate)
{
    if ($cate) {
        
        return M('Category')->where(array(
            'pid' => intval($cate)
        ))->select();
    } else {
        
        return false;
    }
}

// 得到视频信息
function getvideoinfo($url)
{
    require ONETHINK_ADDON_PATH . 'VideoWall/urlParse.php';
    
    $urlParse = new \urlParse();
    
    $return = $urlParse->setvideo($url, '');
    
    return $return;
}

/*
 * function get_document_list($cid){
 *
 * if(empty($cid) || !is_numeric($cid)){
 *
 * return '';
 *
 * }
 *
 * $data = M('Document')->where(array('category_id'=>$cid,'status'=>1))->select();
 *
 * return empty($data) ? '' : $data;
 *
 * }
 */

/**
 *
 * 安全过滤函数
 *
 *
 *
 * @param
 *            $string
 *            
 * @return string
 *
 */
function safe_replace($string)
{
    $string = str_replace('%20', '', $string);
    
    $string = str_replace('%27', '', $string);
    
    $string = str_replace('%2527', '', $string);
    
    $string = str_replace('*', '', $string);
    
    $string = str_replace('"', '&quot;', $string);
    
    $string = str_replace("'", '', $string);
    
    $string = str_replace('"', '', $string);
    
    $string = str_replace(';', '', $string);
    
    $string = str_replace('<', '&lt;', $string);
    
    $string = str_replace('>', '&gt;', $string);
    
    $string = str_replace("{", '', $string);
    
    $string = str_replace('}', '', $string);
    
    $string = str_replace('\\', '', $string);
    
    return $string;
}

/**
 *
 * 返回经htmlspecialchars处理过的字符串或数组
 *
 * @param $obj 需要处理的字符串或数组            
 *
 * @return mixed
 *
 */
function new_html_special_chars($string)
{
    $encoding = 'utf-8';
    
    // if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
    
    if (! is_array($string))
        return htmlspecialchars($string, ENT_QUOTES, $encoding);
    
    foreach ($string as $key => $val)
        $string[$key] = new_html_special_chars($val);
    
    return $string;
}

// 得到分页
function get_page_s($tag)
{
    $cate = $tag['cate'];
    
    $listrow = $tag['listrow'];
    
    $search = $tag['search'];
    
    // return $country;
    
    $parse = $__PAGE__ = new \Think\Page(get_list_count_s($cate, 1, $search), $listrow);
    
    return $__PAGE__->show_desk();
    
    // return $parse;
}

function get_list_count_s($category, $status = 1, $search = null, $ntype = null)
{
    static $count;
    
    if (! isset($count[$category])) {
        
        $count[$category] = D('Document')->listCount_s($category, $status, $search, $ntype);
    }
    
    // dump($country);
    
    // dump($count[$category]);
    
    return $count[$category];
}

function get_dateformat_fromid($groupid)
{
    
    // M('Document')->Distinct()->where(array('category_id'=>$category_id))->select();
    
    // echo M('Document')->getLastSql();
    
    // $datearray = M('Document')->where(array('category_id'=>$category_id))->select("DISTINCT(from_unixtime(`create_time`,'%Y/%m'))");
    if ($groupid) {
        
        $datearray = M()->query("select DISTINCT(from_unixtime(`inputtime`,'%Y-%m-%d')) as yearofmonth from hjjy_message_group where groupid in (0," . $groupid . ")");
    } else {
        
        $datearray = M()->query("select DISTINCT(from_unixtime(`inputtime`,'%Y-%m-%d')) as yearofmonth from hjjy_message_group where groupid=0");
    }
    
    // dump($datearray);
    
    return $datearray;
}

function get_category_child($id, $limit = null)
{
    static $list;
    
    if ($id != 0) 

    {
        
        /* 非法分类ID */
        
        if (empty($id) || ! is_numeric($id)) {
            
            return '';
        }
        
        $cate = M('Category')->find($id);
        
        if (! $cate || 1 != $cate['status']) { // 不存在分类，或分类被禁用
            
            return '';
        }
    }
    
    /* 获取分类名称 */
    
    if ($limit) {
        $cate = M('Category')->where(array(
            'pid' => $id
        ))
            ->limit($limit)
            ->order('sort asc ,id asc')
            ->select();
    } else {
        $cate = M('Category')->where(array(
            'pid' => $id
        ))
            ->order('sort asc ,id asc')
            ->select();
    }
    $list[$id] = $cate;
    
    return is_null($field) ? $list[$id] : $list[$id][$field];
}

function get_document_list($cid, $limit = null)
{
    if (empty($cid) || ! is_numeric($cid)) {
        
        return '';
    }
    
    if (ishavechild($cid)) {
        
        /*
         * $tmp_category = get_category_child($cid);
         *
         * foreach($tmp_category as $k=>$v){
         *
         * $category[$k] = $v['id'];
         *
         * }
         *
         * $category = arr2str($category);
         */
        
        $category = get_c_str($cid);
    } else {
        
        $category = $cid;
    }
    
    if (! is_null($category)) {
        
        if (is_numeric($category)) {
            
            $map['category_id'] = $category;
        } else {
            
            $map['category_id'] = array(
                'in',
                str2arr($category)
            );
        }
    }
    
    $map['status'] = 1;
    
    if ($limit) {
        
        $data = M('Document')->where($map)
            ->order('level desc,update_time desc')
            ->limit($limit)
            ->select();
    } else {
        
        $data = M('Document')->where($map)
            ->order('level desc,update_time desc')
            ->select();
    }
    
    return empty($data) ? '' : $data;
}

// 得到当前栏目的最顶级栏目id
function get_thiscatid_top($id)
{
    if ($id) {
        
        $return = M('Category')->where(array(
            'id' => $id
        ))->find();
        
        for ($i = 0; $i <= 4; $i ++) {
            
            if ($return['pid'] != 0) 

            {
                
                $return = M('Category')->where(array(
                    'id' => $return['pid']
                ))->find();
                
                // $re[] = $return['id'];
                
                if ($return['pid'] == 0) {
                    
                    $pos = $return['id'];
                }
            }
        }
        
        return $pos;
    } else {
        
        return false;
    }
}

function get_navurl_fordesk($id, $symbol = ' > ')
{
    if ($id) {
        
        $return = M('Category')->where(array(
            'id' => $id
        ))->find();
        
        for ($i = 0; $i <= 6; $i ++) {
            
            if ($return['pid'] != 0) 

            {
                
                $return = M('Category')->where(array(
                    'id' => $return['pid']
                ))->find();
                
                $re[] = $return['id'];
                
                /*
                 * if($return['pid']!=0){
                 *
                 * $pos .= '<a href="'.U("Article/lists?category=".$return['name']).'">'.$return["title"].'</a>'.$symbol;
                 *
                 * }else{
                 *
                 * $pos .= '<a href="'.U("Article/index?category=".$return['name']).'">'.$return['title'].'</a>'.$symbol;
                 *
                 * }
                 */
            }
        }
        
        krsort($re);
        
        foreach ($re as $k => $v) 

        {
            
            $return = M('Category')->where(array(
                'id' => $v
            ))->find();
            
            if ($return['pid'] == 0) {
                
                $pos .= '<a href="' . U("article/index?category=" . $return['id']) . '">' . $return["title"] . '</a>' . $symbol;
            } else {
                
                // $pos .= '<a href="'.U("article/lists?category=".$return['id']).'">'.$return["title"].'</a>'.$symbol;
                
                $pos .= $return["title"] . $symbol;
            }
        }
        
        // $pos .= '<a href="'.U("article/lists?category=".$id).'">'.get_category_title($id).'</a>';
        
        $pos .= '<a href="">' . get_category_title($id) . '</a>';
        
        return $pos;
    } else {
        
        return false;
    }
}

function get_navurl_fordesk1($id, $symbol = ' > ')
{
    if ($id) {
        
        $return = M('Category')->where(array(
            'id' => $id
        ))->find();
        
        for ($i = 0; $i <= 6; $i ++) {
            
            if ($return['pid'] != 0) 

            {
                
                $return = M('Category')->where(array(
                    'id' => $return['pid']
                ))->find();
                
                $re[] = $return['id'];
                
                /*
                 * if($return['pid']!=0){
                 *
                 * $pos .= '<a href="'.U("Article/lists?category=".$return['name']).'">'.$return["title"].'</a>'.$symbol;
                 *
                 * }else{
                 *
                 * $pos .= '<a href="'.U("Article/index?category=".$return['name']).'">'.$return['title'].'</a>'.$symbol;
                 *
                 * }
                 */
            }
        }
        
        krsort($re);
        
        foreach ($re as $k => $v) 

        {
            
            $return = M('Category')->where(array(
                'id' => $v
            ))->find();
            
            if ($return['pid'] == 0) {
                
                $pos .= $return["title"] . $symbol;
            } else {
                
                $pos .= $return["title"] . $symbol;
            }
        }
        
        $pos .= '<a href="">' . get_category_title($id) . '</a>';
        
        return $pos;
    } else {
        
        return false;
    }
}

function ishavechild($id)
{
    if ($id) {
        
        $return = M('Category')->where(array(
            'pid' => $id
        ))->select();
        
        if (empty($return)) {
            
            return false;
        } else {
            
            return true;
        }
    } else {
        
        return false;
    }
}

// 得到当前栏目下所有子栏目的str集合
function get_c_str($category)
{
    $childs = get_category_child($category);
    
    foreach ($childs as $key => $v) :
        
        $tmp_childs[] = $v['id'];
    endforeach
    ;
    
    foreach ($tmp_childs as $key => $v) :
        
        $childs1 = get_category_child($v);
        
        foreach ($childs1 as $key1 => $v1) :
            
            $tmp_childs[] = $v1['id'];
        endforeach
        ;
    endforeach
    ;
    
    foreach ($tmp_childs as $key => $v) :
        
        $childs1 = get_category_child($v);
        
        foreach ($childs1 as $key1 => $v1) :
            
            $tmp_childs[] = $v1['id'];
        endforeach
        ;
    endforeach
    ;
    
    // dump(arr2str($tmp_childs));
    
    return arr2str($tmp_childs);
}

function new_stripslashes($string)
{
    if (! is_array($string))
        return stripslashes($string);
    
    foreach ($string as $key => $val)
        $string[$key] = new_stripslashes($val);
    
    return $string;
}

// 获取友情链接分类
function get_typeof_link()
{
    $return = M('Super_links_type')->order('level asc ')->select();
    
    return $return;
}

// 根据分类id 获取友情链接
function get_childof_link($id)
{
    if (empty($id))
        return false;
    
    $return = M('Super_links')->where(array(
        'tid' => $id,
        'status' => 1
    ))
        ->order('level asc ,id asc')
        ->select();
    
    return $return;
}

// 获取专栏列表
function get_column($limit = null)
{
    return M('Column')->where(array(
        'status' => 1
    ))
        ->limit($limit)
        ->select();
}

// 获取专题信息列表
function get_column_info($clid, $cid, $limit = null)
{
    return M('ColumnInfo')->where(array(
        'colid' => $clid,
        'cid' => $cid
    ))
        ->limit($limit)
        ->select();
}

function pagesdesk($count, $listrow)
{
    
    // return $country;
    $parse = $__PAGE__ = new \Think\Page($count, $listrow);
    
    return $__PAGE__->show();
    
    // return $parse;
}

function get_passtype()
{
    return M('Typeauction')->order('level asc')->select();
}

function get_passauction($id)
{
    return M('Passauction')->where(array(
        'type' => $id
    ))
        ->order('id desc')
        ->select();
}

function get_article_topone($cid, $limit = null)
{
    $tmp_data = get_document_list($cid);
    
    foreach ($tmp_data as $key => $val) {
        
        $thumb = get_moreinfo($val['id']);
        
        if ($thumb['thumb'] == 0) {
            
            unset($tmp_data[$key]);
        } else {
            
            $tmp_data[$key]['thumb'] = $thumb['thumb'];
            
            $tmp_data[$key]['d_des'] = $thumb['d_des'];
        }
    }
    
    if (count($tmp_data) > 0) {
        
        if ($limit) {
            
            return array_slice($tmp_data, 0, $limit);
        } else {
            
            return $tmp_data;
        }
    } else {
        
        return false;
    }
}

function str_cut($string, $length, $dot = '...')
{
    $strlen = strlen($string);
    if ($strlen <= $length)
        return $string;
    $string = str_replace(array(
        ' ',
        '&nbsp;',
        '&amp;',
        '&quot;',
        '&#039;',
        '&ldquo;',
        '&rdquo;',
        '&mdash;',
        '&lt;',
        '&gt;',
        '&middot;',
        '&hellip;'
    ), array(
        '∵',
        ' ',
        '&',
        '"',
        "'",
        '"',
        '"',
        '—',
        '<',
        '>',
        '·',
        '…'
    ), $string);
    $strcut = '';
    
    // $length = intval($length-strlen($dot)-$length/3);
    $n = $tn = $noc = 0;
    while ($n < strlen($string)) {
        $t = ord($string[$n]);
        if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
            $tn = 1;
            $n ++;
            $noc ++;
        } elseif (194 <= $t && $t <= 223) {
            $tn = 2;
            $n += 2;
            $noc += 2;
        } elseif (224 <= $t && $t <= 239) {
            $tn = 3;
            $n += 3;
            $noc += 2;
        } elseif (240 <= $t && $t <= 247) {
            $tn = 4;
            $n += 4;
            $noc += 2;
        } elseif (248 <= $t && $t <= 251) {
            $tn = 5;
            $n += 5;
            $noc += 2;
        } elseif ($t == 252 || $t == 253) {
            $tn = 6;
            $n += 6;
            $noc += 2;
        } else {
            $n ++;
        }
        if ($noc >= $length) {
            break;
        }
    }
    if ($noc > $length) {
        $n -= $tn;
    }
    $strcut = substr($string, 0, $n);
    $strcut = str_replace(array(
        '∵',
        '&',
        '"',
        "'",
        '"',
        '"',
        '—',
        '<',
        '>',
        '·',
        '…'
    ), array(
        ' ',
        '&amp;',
        '&quot;',
        '&#039;',
        '&ldquo;',
        '&rdquo;',
        '&mdash;',
        '&lt;',
        '&gt;',
        '&middot;',
        '&hellip;'
    ), $strcut);
    
    return $strcut . $dot;
}

function get_firstchild($id)
{
    $tmp_data = get_category_child($id);
    return $tmp_data[0][id];
}

// 得到产品属性
function get_attb_pro($model, $id = null, $field = null)
{
    if ($model || $id) {
        if ($id) {
            if ($field) {
                return M(ucfirst($model))->where(array(
                    'id' => $id
                ))
                    ->order('level asc')
                    ->getField($field);
            } else {
                return M(ucfirst($model))->where(array(
                    'id' => $id
                ))
                    ->order('level asc')
                    ->find();
            }
        } else {
            
            return M(ucfirst($model))->order('level asc')->select();
        }
    } else {
        return false;
    }
}

// 得到某品牌的产品
function get_pro_brand($brand, $limit = null)
{
    if ($brand) {
        if (is_array($brand)) {
            $result = M('DocumentProduct')->where($brand)->select();
        } else {
            $result = M('DocumentProduct')->where(array(
                'brand' => $brand
            ))->select();
        }
        foreach ($result as $k => $v) {
            $status = M('Document')->where(array(
                'id' => $v[id]
            ))->find();
            if ($status['status'] != 1) {
                unset($result[$k]);
            } else {
                $result[$k] = array_merge($result[$k], $status);
            }
        }
        if ($limit) {
            return array_slice($result, 0, $limit);
        } else {
            return $result;
        }
    } else {
        return false;
    }
}
// 得到下载中心分类
function get_download_type($category)
{
    if ($category) {
        $tmp_data = M('Document')->where(array(
            'category_id' => $category,
            'status' => 1
        ))
            ->order('level desc')
            ->select();
        return assoc_unique($tmp_data, 'description');
    } else {
        return false;
    }
}
// 根据下载分类得到所有
function get_dtype_list($type)
{
    if ($type) {
        return M('Document')->where(array(
            'status' => 1,
            'description' => $type
        ))
            ->order('level desc')
            ->select();
    } else {
        return false;
    }
}
// 二维数组去重
function assoc_unique(&$arr, $key)
{
    $rAr = array();
    for ($i = 0; $i < count($arr); $i ++) {
        if (! isset($rAr[$arr[$i][$key]])) {
            $rAr[$arr[$i][$key]] = $arr[$i];
        }
    }
    return array_values($rAr);
}

function get_top($filed, $limit = 10, $category)
{
    $limit = intval($limit);
    $map = array(
        'status' => '1',
        'pid' => 0
    );
    if ($category) {
        if (! is_null($category)) {
            if (is_numeric($category)) {
                $map['category_id'] = $category;
            } else {
                $map['category_id'] = array(
                    'in',
                    str2arr($category)
                );
            }
        }
        $data = M('Document')->where($map)
            ->order($filed . ' desc')
            ->limit($limit)
            ->select();
    } else {
        $data = M('Document')->where($map)
            ->order($filed . ' desc')
            ->limit($limit)
            ->select();
    }
    return $data;
}

function get_ajaxpage($model, $tag, $listrow, $isajax = 1, $flag = null)
{
    
    // return $country;
    $parse = $__PAGE__ = new \Think\Page(get_list_count_ajax($model, $tag), $listrow);
    if ($isajax) {
        return $__PAGE__->show_ajax($flag);
    } else {
        return $__PAGE__->show_desk();
    }
    // return $parse;
}

function get_list_count_ajax($model, $map)
{
    // static $count;
    // if(!isset($count[$category])){
    $count = M($model)->where($map)->count('id');
    // }
    // dump($country);
    // dump($count[$category]);
    return $count;
}

function get_ajax_default($model, $tag, $listrow)
{
    return M($model)->where($tag)
        ->limit($listrow)
        ->select();
}