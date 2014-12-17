<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class OrderItemModel extends RelationModel {

    protected $_link = array(
        'Order' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'OrderItem',
            'foreign_key' => 'orderId',
            'mapping_name' => 'order'
        )
    );

    public function getOrderItem($orderId) {
        $where['orderId'] = $orderId;
        return $this->where($where)->select();
    }

    public function addOrderItem($orderId, $product, $quantity, $money, $comment) {
        $data['orderId'] = $orderId;
        $data['product'] = $product;
        $data['quantity'] = $quantity;
        $data['money'] = $money;
        $data['comment'] = $comment;
        return $this->data($data)->add();
    }

    public function updateOrderItem($orderItemId, $product, $quantity, $money, $comment) {
        $data['product'] = $product;
        $data['quantity'] = $quantity;
        $data['money'] = $money;
        $data['comment'] = $comment;
        return $this->where('id='.$orderItemId)->data($data)->save();
    }

    public function deleteOrderItem($orderItemId) {
        return $this->where('id='.$orderItemId)->delete();
    }
}
