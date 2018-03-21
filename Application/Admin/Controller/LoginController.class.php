<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台首页控制器
 *
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class LoginController extends Controller
{

    /**
     * 后台用户登录
     *
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function login($username = null, $password = null, $verify = null)
    {
        if (IS_POST) {
            /* 检测验证码 TODO: */
            if (! check_verify($verify)) {
                $this->error('验证码输入错误！');
            }
            $User = new \Admin\Model\AdminModel();
            $uid = $User->login($username, $password);
            if (0 < $uid) { 
                /* 登录成功 */
               $this->redirect('Index/index');
            } else { // 登录失败
                switch ($uid) {
                    case - 1:
                        $error = '用户不存在或被禁用！';
                        break; // 系统级别禁用
                    case - 2:
                        $error = '密码错误！';
                        break;
                    default:
                        $error = '未知错误！';
                        break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }
        } else {
            $this->display();
        }
    }

    /* 退出登录 */
    public function logout()
    {
        if (is_login()) {
            session('[destroy]');
            $this->success('退出成功！', U('login'));
        } else {
            $this->redirect('login');
        }
    }

    public function verify()
    {
        $config = array(
            'useCurve' => false, // 是否画混淆曲线
            'useNoise' => false, // 是否添加杂点
            'length' => 4, // 验证码位数
            'codeSet' => '2345678ABCDEFGHJKLMNPQRTUVWXY',
            'fontttf' => '2.ttf'
        ); // 验证码字体，不设置随机获取

        $verify = new \Think\Verify($config);
        $verify->entry(1);
    }
}
