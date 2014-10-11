<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index() {
        if(D('AdminManager')->isLogged()) {
            $this->redirect('Admin/Index/dashboard');
        } else {
            $this->redirect('Admin/Index/login');
        }
    }

    public function doLogin($adminName, $password, $remember = false) {
        if(D('AdminManager')->login($adminName, $password, $remember)) {
            $this->success('登陆成功', 'dashboard');
        } else {
            $this->error('登陆失败', 'login', 3);
        }
    }

    public function user_table() {
        $users = D('Wechat/User')->getUsers();
        $this->assign('users', $users);
        $this->display();
    }

    public function prize_of_user_table($page = 1) {
        $users = D('Wechat/PrizeUser')->getUserForPrize($page);
        $this->assign('users', $users);
        $this->display();
    }
}
