<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class UserController extends Controller {
    public function index($draw, $columns, $start, $length, $order, $search) {
        $column = $order[0]['column'];
        $order_name = $columns[$column]['data'];
        $dir = $order[0]['dir'];
        $users = D('Wechat/User')->getUsers($start, $length, $order_name.' '.$dir);
        $data['draw'] = $draw;
        $data['recordsTotal'] = D('Wechat/User')->getCount();
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $users;
        $this->ajaxReturn($data);
    }

    public function crud($action = 'none', $data = null, $id = null) {
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
}
