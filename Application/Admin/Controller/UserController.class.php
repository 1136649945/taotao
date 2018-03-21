<?php
namespace Admin\Controller;

class UserController extends AdminController
{

    public function user()
    {
        if (is_admin()) {
            $this->display();
        } else {
            echo "<script type='text/javascript'>";
            echo "window.top.location.href='/admin.php/Index/index'";
            echo "</script>";
            exit();
        }
    }

    /**
     * 添加和编辑用户
     */
    public function add_edit_user()
    {
        if (is_admin()) {
            if (I("get.userid")) {
                $user = new \Admin\Model\AdminModel();
                $this->assign("user", $user->getUserInfo(I("get.userid")));
            }
            $this->display();
        } else {
            echo "<script type='text/javascript'>";
            echo "window.top.location.href='/admin.php/Index/index'";
            echo "</script>";
            exit();
        }
    }

    /**
     * 查询用户
     */
    public function queryUser()
    {
        if (is_admin()) {
            if (IS_POST) {
                $User = M('admin'); // 实例化User对象
                $where = "1=1";
                $p = 0;
                $size = 20;
                if (I("post.p")) {
                    $p = I("post.p");
                }
                if (I("post.username")) {
                    $where = $where . " and name like '%" . I("post.name") . "%'";
                }
                if (I("post.usercode")) {
                    $where = $where . " and usercode like '%" . I("post.usercode") . "%'";
                }
                $list = $User->where($where)
                    ->order('create_time')
                    ->limit($p * $size, $size)
                    ->select();
                $count = $User->where($where)->count(); // 查询满足要求的总记录数
                $data = array();
                $data['count'] = $count;
                $data['list'] = $list;
                $this->ajaxReturn(json_encode($data), 'json');
            }
        } else {
            echo "<script type='text/javascript'>";
            echo "window.top.location.href='/admin.php/Index/index'";
            echo "</script>";
            exit();
        }
    }
}