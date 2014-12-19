<?php
namespace Admin\Model;
use Think\Model;
class CustomerModel extends Model {

    public function getCount() {
        return $this->count();
    }

    public function getCustomer($start = 0, $length = 10, $order = 'ctime asc') {
        $page = $start / $length + 1;
        $customer = $this->order($order)->page($page.','.$length)->select();
        if($customer === null)
            return array();
        return $customer;
        
    }
}
