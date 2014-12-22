<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class OrderItemModel extends RelationModel {

    protected $_link = array(
        'Order' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Order',
            'foreign_key' => 'orderId',
            'mapping_name' => 'order'
        )
    );

    public function getOrderItem($orderId) {
        $where['orderId'] = $orderId;
        return $this->where($where)->select();
    }

    public function addOrderItem($orderId, $product, $quantity, $money, $comment) {
        $order = M('Order')->find($orderId);
        if($order !== null) {
            $data['orderId'] = $orderId;
            $data['product'] = $product;
            $data['quantity'] = $quantity;
            $data['money'] = $money;
            $data['comment'] = $comment;
            $result['result'] = $this->data($data)->add();
            $result['message'] = '创建'.$order['cdate'].' '.$order['customer'].'的'.$result['result'].'号订单项!';
        } else {
            $result['result'] = 0;
            $result['message'] = '订单项创建失败';
        }
        return $result;
    }

    public function updateOrderItem($orderItemId, $product, $quantity, $money, $comment) {
        $item = $this->relation(true)->find($orderItemId);
        if($item !== null) {
            $data['product'] = $product;
            $data['quantity'] = $quantity;
            $data['money'] = $money;
            $data['comment'] = $comment;
            $result['result'] = $this->where('id='.$orderItemId)->data($data)->save();
            $result['message'] = '更新'.$item['order']['cdate'].' '.$item['order']['customer'].'的'.$orderItemId.'号订单项!';
        } else {
            $result['result'] = 0;
            $result['message'] = '订单项更新失败';
        }
        return $result;
    }

    public function deleteOrderItem($orderItemId) {
        $item = $this->relation(true)->find($orderItemId);
        if($item !== null) {
            $result['result'] = $this->where('id='.$orderItemId)->delete();
            $result['message'] = '删除'.$item['order']['cdate'].' '.$item['order']['customer'].'的'.$orderItemId.'号订单项!';
        } else {
            $result['result'] = 0;
            $result['message'] = '订单项删除失败';
        }
        return $result;
    }
}
