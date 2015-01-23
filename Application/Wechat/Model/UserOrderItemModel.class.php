<?php
namespace Wechat\Model;
use Think\Model\RelationModel;
class UserOrderItemModel extends RelationModel {

    protected $_link = array(
        'Order' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'UserOrder',
            'foreign_key' => 'orderId',
            'mapping_name' => 'order'
        )
    );

    public function getCount($paydate = null, $locationId = 1) {
        if($paydate == null)
            $paydate = date('Y-m-d');
        $where['paydate'] = $paydate;
        $where['locationId'] = $locationId;
        return $this->where($where)->count();
    }

    /* public function getCount($orderId) { */
    /*     $where['orderId'] = $orderId; */
    /*     return $this->where($where)->count(); */
    /* } */

    public function getUserOrderItem($paydate = null, $locationId = 1, $start, $length) {
        $page = $start / $length + 1;
        if($paydate == null)
            $paydate = date('Y-m-d');
        $where['paydate'] = $paydate;
        $where['locationId'] = $locationId;
        $items = $this->relation(true)->where($where)->page($page.','.$length)->select();
        if($items == null)
            $items = array();
        return $items;
    }

    /* public function addOrderItem($orderId, $product, $quantity, $money, $comment) { */
    /*     $order = M('Order')->find($orderId); */
    /*     if($order !== null) { */
    /*         $data['orderId'] = $orderId; */
    /*         $data['product'] = $product; */
    /*         $data['quantity'] = $quantity; */
    /*         $data['money'] = $money; */
    /*         $data['comment'] = $comment; */
    /*         $result['result'] = $this->data($data)->add(); */
    /*         $result['message'] = '创建'.$order['cdate'].' '.$order['customer'].'的'.$result['result'].'号订单项!'; */
    /*     } else { */
    /*         $result['result'] = 0; */
    /*         $result['message'] = '订单项创建失败'; */
    /*     } */
    /*     return $result; */
    /* } */

    /* public function updateOrderItem($orderItemId, $product, $quantity, $money, $comment) { */
    /*     $item = $this->relation(true)->find($orderItemId); */
    /*     if($item !== null) { */
    /*         $data['product'] = $product; */
    /*         $data['quantity'] = $quantity; */
    /*         $data['money'] = $money; */
    /*         $data['comment'] = $comment; */
    /*         $result['result'] = $this->where('id='.$orderItemId)->data($data)->save(); */
    /*         $result['message'] = '更新'.$item['order']['cdate'].' '.$item['order']['customer'].'的'.$orderItemId.'号订单项!'; */
    /*     } else { */
    /*         $result['result'] = 0; */
    /*         $result['message'] = '订单项更新失败'; */
    /*     } */
    /*     return $result; */
    /* } */

    /* public function deleteOrderItem($orderItemId) { */
    /*     $item = $this->relation(true)->find($orderItemId); */
    /*     if($item !== null) { */
    /*         $result['result'] = $this->where('id='.$orderItemId)->delete(); */
    /*         $result['message'] = '删除'.$item['order']['cdate'].' '.$item['order']['customer'].'的'.$orderItemId.'号订单项!'; */
    /*     } else { */
    /*         $result['result'] = 0; */
    /*         $result['message'] = '订单项删除失败'; */
    /*     } */
    /*     return $result; */
    /* } */
}
