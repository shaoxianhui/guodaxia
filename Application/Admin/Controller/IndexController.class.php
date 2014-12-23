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

    public function order_list() {
        if(D('AdminManager')->isLogged()) {
            $this->display();
        } else {
            $this->redirect('Admin/Index/signin');
        }
    }

    public function order_edit() {
        if(D('AdminManager')->isLogged()) {
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
            D('Wechat/User')->where('id='.$id)->save($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'create':
            $id = D('Wechat/User')->add($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'remove':
            D('Wechat/User')->delete(join(',', $id));
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
        $users = D('Wechat/PrizeUser')->getUserForPrize($_GET['start'], $_GET['length'], $order.' '.$dir);
        $data['draw'] = $GET['draw'];
        $data['recordsTotal'] = D('Wechat/PrizeUser')->getCount();
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $users;
        $this->ajaxReturn($data);
    }

    public function prizer() {
        $column = $_GET['order'][0]['column'];
        $order = $_GET['columns'][$column]['data'];
        $dir = $_GET['order'][0]['dir'];
        $users = D('Wechat/Prizer')->getPrizer($_GET['start'], $_GET['length'], $order.' '.$dir);
        $data['draw'] = $GET['draw'];
        $data['recordsTotal'] = D('Wechat/Prizer')->getCount();
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $users;
        $this->ajaxReturn($data);
    }

    public function feedback() {
        $column = $_GET['order'][0]['column'];
        $order = $_GET['columns'][$column]['data'];
        $dir = $_GET['order'][0]['dir'];
        $feedbacks = D('Home/Feedback')->getFeedback($_GET['start'], $_GET['length'], $order.' '.$dir);
        $data['draw'] = $GET['draw'];
        $data['recordsTotal'] = D('Home/Feedback')->getCount();
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $feedbacks;
        $this->ajaxReturn($data);
    }

    public function customer() {
        $column = $_GET['order'][0]['column'];
        $order = $_GET['columns'][$column]['data'];
        $dir = $_GET['order'][0]['dir'];
        $customers = D('Customer')->getCustomer($_GET['start'], $_GET['length'], $order.' '.$dir);
        $data['draw'] = $GET['draw'];
        $data['recordsTotal'] = D('Customer')->getCount();
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $customers;
        $this->ajaxReturn($data);
    }

    public function customer_crud($action = 'none', $data = null, $id = null) {
        $result['fieldErrors'] = array();
        switch($action) {
        case 'create':
            $c = D('Customer')->where('name=\''.$data['name'].'\'')->find();
            if($c != null) {
                array_push($result['fieldErrors'], array('name' => 'name', 'status' => '客户名称已存在'));
            }
        case 'edit':
            if(!is_numeric($data['longitude'])) {
                array_push($result['fieldErrors'], array('name' => 'longitude', 'status' => '经度错误'));
            }
            if(!is_numeric($data['latitude'])) {
                array_push($result['fieldErrors'], array('name' => 'latitude', 'status' => '纬度错误'));
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
            D('Customer')->where('id='.$id)->save($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'create':
            $id = D('Customer')->add($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'remove':
            D('Customer')->delete(join(',', $id));
            $result['id'] = $id;
            $this->ajaxReturn($result);
            break;
        default:
            break;
        }
    }
}
