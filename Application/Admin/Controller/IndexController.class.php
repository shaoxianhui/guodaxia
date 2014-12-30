<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class IndexController extends Controller {
    public function index() {
        if(D('AdminManager')->isLogged()) {
            $this->redirect('Admin/Index/information');
        } else {
            $this->redirect('Admin/Index/signin');
        }
    }

    public function doLogin($adminName, $password, $remember = false) {
        if(D('AdminManager')->login($adminName, $password, $remember)) {
            $this->success('登陆成功', 'information');
        } else {
            $this->error('登陆失败', 'signin', 3);
        }
    }
	
    public function doLogout() {
        D('AdminManager')->logout();
        $this->success('退出成功', 'signin');
    }

    public function information() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/signin');
        }
    }

    public function prizer_list() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/signin');
        }
    }

    public function order_list($date = null) {
        if(D('AdminManager')->isLogged()) {
            if($date == null)
                $date = date('Y-m-d');
            $this->assign('date', $date);
            $order = D('Order')->getOrder($date);
            $this->assign('order', $order);
            $this->display();
        } else {
            $this->redirect('Admin/Index/signin');
        }
    }

    public function order_edit($date = null) {
        if(D('AdminManager')->isLogged()) {
            if($date == null)
                $date = date('Y-m-d');
            $this->assign('date', $date);
            $this->display();
        } else {
            $this->redirect('Admin/Index/signin');
        }
    }

    public function customer_edit() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/signin');
        }
    }

    public function fruit_edit() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/signin');
        }
    }
}
