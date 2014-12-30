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

    public function getOrder($cdate, $order = 'id desc') {
        $where['cdate'] = $cdate;
        $orders = $this->relation(true)->where($where)->order($order)->select();
        if($orders === null)
            return array();
        return $orders;
    }

    public function getCount($cdate = null) {
        if($cdate == null) {
            return $this->count();
        } else {
            $where['cdate'] = $cdate;
            return $this->where($where)->count();
        }
    }

    public function addOrder($customer, $cdate, $earliest, $latest, $comment) {
        $data['customer'] = $customer;
        $data['cdate'] = $cdate;
        $data['earliest'] = $earliest;
        $data['latest'] = $latest;
        $data['comment'] = $comment;
        $result['result'] = $this->data($data)->add();
        $result['message'] = '创建'.$data['cdate'].' '.$data['customer'].'的'.$result['result'].'号订单!';
        return $result;
    }

    public function updateOrder($orderId, $customer, $cdate, $earliest, $latest, $comment) {
        $data['customer'] = $customer;
        $data['cdate'] = $cdate;
        $data['earliest'] = $earliest;
        $data['latest'] = $latest;
        $data['comment'] = $comment;
        $result['result'] = $this->where('id='.$orderId)->data($data)->save();
        $result['message'] = '更新'.$data['cdate'].' '.$data['customer'].'的'.$orderId.'号订单!';
        return $result;
    }

    public function deleteOrder($orderId) {
        $order = $this->find($orderId);
        if($order !== null) {
            $result['result'] = $this->relation(true)->delete($orderId);
            $result['message'] = '删除'.$order['cdate'].' '.$order['customer'].'的'.$order['id'].'号订单!';
        }
        return $result;
    }
}
