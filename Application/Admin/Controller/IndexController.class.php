<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
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

    public function doLogout() {
        D('AdminManager')->logout();
        $this->success('退出成功', 'login');
    }

    public function user($action = 'none', $data = null, $id = null) {
        switch($action) {
        case 'edit':
        case 'create':
            $result['fieldErrors'] = array();
            if(!is_numeric($data['score'])) {
                array_push($result['fieldErrors'], array('name' => 'score', 'status' => '积分不能为非数字'));
            }
            if(!is_numeric($data['qrScene'])) {
                array_push($result['fieldErrors'], array('name' => 'qrScene', 'status' => '场景值不能为非数字'));
            }
            if(!empty($result['fieldErrors'])) {
                $this->ajaxReturn($result);
            }
            break;
        default:
            break;
        }
        switch($action) {
        case 'edit':
            D('User')->where('id='.$id)->save($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'create':
            $id = D('User')->add($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'remove':
            D('User')->delete(join(',', $id));
            $result['id'] = $id;
            $this->ajaxReturn($result);
            break;
        default:
            break;
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
