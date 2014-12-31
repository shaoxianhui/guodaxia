<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class OrderItemController extends Controller {
    public function index($orderId = null, $draw, $columns, $start, $length, $order, $search) {

        $column = $order[0]['column'];
        $order_name = $columns[$column]['data'];
        $dir = $order[0]['dir'];
        $orders = D('OrderItem')->getOrderItem($orderId);
        $data['draw'] = $draw;
        $data['recordsTotal'] = D('OrderItem')->getCount($orderId);
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $orders;
        $this->ajaxReturn($data);
    }

    public function crud($orderId, $action = 'none', $data = null, $id = null) {
        $result['fieldErrors'] = array();
        switch($action) {
        case 'create':
        case 'edit':
            if(strlen($data['product']) <= 0)
                array_push($result['fieldErrors'], array('name' => 'product', 'status' => '产品名称不能为空'));
            if(!is_numeric($data['quantity'])) {
                array_push($result['fieldErrors'], array('name' => 'quantity', 'status' => '数量不能为非数字'));
            }
            if(!is_numeric($data['money'])) {
                array_push($result['fieldErrors'], array('name' => 'money', 'status' => '金额不能为非数字'));
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
            D('OrderItem')->where('id='.$id)->save($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'create':
            $data['orderId'] = $orderId;
            $id = D('OrderItem')->add($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'remove':
            D('OrderItem')->delete(join(',', $id));
            $result['id'] = $id;
            $this->ajaxReturn($result);
            break;
        default:
            break;
        }
    }
}
