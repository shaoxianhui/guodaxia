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
}
