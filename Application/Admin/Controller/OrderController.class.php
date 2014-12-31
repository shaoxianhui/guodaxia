<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class OrderController extends Controller {
    public function index($date = null, $draw, $columns, $start, $length, $order, $search) {
        if($date == null)
            $date = date('Y-m-d');
        $column = $order[0]['column'];
        $order_name = $columns[$column]['data'];
        $dir = $order[0]['dir'];
        $orders = D('Order')->getOrder($date, $order_name.' '.$dir);
        $data['draw'] = $draw;
        $data['recordsTotal'] = D('Order')->getCount($date);
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $orders;
        $this->ajaxReturn($data);
    }

    public function crud($action = 'none', $data = null, $id = null) {
        $result['fieldErrors'] = array();
        switch($action) {
        case 'create':
        case 'edit':
            if(strlen($data['customer']) <= 0)
                array_push($result['fieldErrors'], array('name' => 'customer', 'status' => '客户名称不能为空'));
            if(!empty($result['fieldErrors'])) {
                $this->ajaxReturn($result);
            }
            break;
        default:
            break;
        }
        switch($action) {
        case 'edit':
            D('Order')->where('id='.$id)->save($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'create':
            $id = D('Order')->add($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'remove':
            D('Order')->relation(true)->delete(join(',', $id));
            $result['id'] = $id;
            $this->ajaxReturn($result);
            break;
        default:
            break;
        }
    }
}
