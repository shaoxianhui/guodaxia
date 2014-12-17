<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class OrderModel extends RelationModel {

    protected $_link = array(
        'OrderItem' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'OrderItem',
            'foreign_key' => 'orderId',
            'mapping_name' => 'orderItems',
            'mapping_order' => 'id desc'
        )
    );

    public function getOrder($cdate) {
        $where['cdate'] = $cdate;
        return $this->relation(true)->where($where)->select();
    }

    public function addOrder($customer, $cdate, $earliest, $latest, $comment) {
        $data['customer'] = $customer;
        $data['cdate'] = $cdate;
        $data['earliest'] = $earliest;
        $data['latest'] = $latest;
        $data['comment'] = $comment;
        return $this->data($data)->add();
    }

    public function updateOrder($orderId, $customer, $cdate, $earliest, $latest, $comment) {
        $data['customer'] = $customer;
        $data['cdate'] = $cdate;
        $data['earliest'] = $earliest;
        $data['latest'] = $latest;
        $data['comment'] = $comment;
        return $this->where('id='.$orderId)->data($data)->save();
    }

    public function deleteOrder($orderId) {
        return $this->relation(true)->delete($orderId);
    }
}
