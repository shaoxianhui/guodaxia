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
}
