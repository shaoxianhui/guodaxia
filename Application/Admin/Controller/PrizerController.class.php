<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class PrizerController extends Controller {
    public function index($draw, $columns, $start, $length, $order, $search) {
        $column = $order[0]['column'];
        $order_name = $columns[$column]['data'];
        $dir = $order[0]['dir'];
        $users = D('Wechat/Prizer')->getPrizer($start, $length, $order_name.' '.$dir, $search['value']);
        $data['draw'] = $draw;
        $data['recordsTotal'] = D('Wechat/Prizer')->getCount($search['value']);
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $users;
        $this->ajaxReturn($data);
    }

    public function crud($action = 'none', $data = null, $id = null) {
        switch($action) {
        case 'edit':
            D('Wechat/Prizer')->where('id='.$id)->save($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        default:
            break;
        }
    }
}
