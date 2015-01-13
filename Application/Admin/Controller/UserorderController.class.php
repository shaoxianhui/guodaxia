<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class UserorderController extends Controller {
    public function index($date = null, $locationId = 1, $draw, $columns, $start, $length, $order, $search) {
        if($date == null)
            $date = date('Y-m-d');
        $column = $order[0]['column'];
        $order_name = $columns[$column]['data'];
        $dir = $order[0]['dir'];
        $orders = D('Wechat/UserOrderItem')->getUserOrderItem($date, $locationId);
        $data['draw'] = $draw;
        $data['recordsTotal'] = D('Wechat/UserOrderItem')->getCount($date, $locationId);
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $orders;
        $this->ajaxReturn($data);
    }
}
