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

    public function dashboard() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/login');
        }
    }

    public function user_table() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/login');
        }
    }

    public function prize_of_user_table() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/login');
        }
    }

    public function statistics() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/login');
        }
    }

    public function calendar() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/login');
        }
    }

    public function profile() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
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

    public function users() {
        $column = $_GET['order'][0]['column'];
        $order = $_GET['columns'][$column]['data'];
        $dir = $_GET['order'][0]['dir'];
        $users = D('Wechat/User')->getUsers($_GET['start'], $_GET['length'], $order.' '.$dir);
        $data['draw'] = $GET['draw'];
        $data['recordsTotal'] = D('Wechat/User')->getCount();
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $users;
        $this->ajaxReturn($data);
    }

    public function prize_of_user() {
        $column = $_GET['order'][0]['column'];
        $order = $_GET['columns'][$column]['data'];
        $dir = $_GET['order'][0]['dir'];
        $users = D('Wechat/PrizeUser')->getUserForPrize($page);
        $data['draw'] = $GET['draw'];
        $data['recordsTotal'] = D('Wechat/PrizeUser')->getCount();
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $users;
        $this->ajaxReturn($data);
    }
}
