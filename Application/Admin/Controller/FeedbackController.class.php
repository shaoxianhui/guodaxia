<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class FeedbackController extends Controller {
    public function index($draw, $columns, $start, $length, $order, $search) {
        $column = $order[0]['column'];
        $order_name = $columns[$column]['data'];
        $dir = $order[0]['dir'];
        $feedbacks = D('Home/Feedback')->getFeedback($start, $length, $order_name.' '.$dir);
        $data['draw'] = $draw;
        $data['recordsTotal'] = D('Home/Feedback')->getCount();
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $feedbacks;
        $this->ajaxReturn($data);
    }
}
