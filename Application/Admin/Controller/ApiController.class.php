<?php
namespace Admin\Controller;
use Think\Controller\RpcController;
class ApiController extends RpcController {
    public function login($username, $password) {
        $admin = D('Admin/Admin')->getAdmin($username, $password);
        if($admin !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function getFruitType() {
        $fruitType = D('FruitType')->getFruitType();
        return $fruitType;
    }

    public function getFruit($fruitTypeId) {
        $fruits = D('Fruit')->getFruit($fruitTypeId);
        if($fruits === null) {
            return array();
        }
        return $fruits;
    }

    public function getFruitPrice($fruitId) {
        $fruitPrice = D('FruitPrice')->getFruitPrice($fruitId);
        if($fruitPrice === null) {
            return array();
        }
        return $fruitPrice;
    }

    public function addFruitType($name) {
        $id = D('FruitType')->addFruitType($name);
        return $id;
    }

    public function addFruit($fruitTypeId, $name, $level, $area, $unit, $description, $loss) {
        $id = D('Fruit')->addFruit($fruitTypeId, $name, $level, $area, $unit, $description, $loss);
        return $id;
    }

    public function addFruitPrice($fruitId, $minPrice, $maxPrice, $cdate) {
        $id = D('FruitPrice')->addFruitPrice($fruitId, $minPrice, $maxPrice, $cdate);
        return $id;
    }

    public function updateFruitType($fruitTypeId, $name) {
        $id = D('FruitType')->updateFruitType($fruitTypeId, $name);
        return $id;
    }

    public function updateFruit($fruitId, $name, $level, $area, $unit, $description, $loss) {
        $id = D('Fruit')->updateFruit($fruitId, $name, $level, $area, $unit, $description, $loss);
        return $id;
    }

    public function updateFruitPrice($fruitPriceId, $minPrice, $maxPrice, $cdate) {
        $id = D('FruitPrice')->updateFruitPrice($fruitPriceId, $minPrice, $maxPrice, $cdate);
        return $id;
    }

    public function getUserForPrize($page) {
        $result = D('Wechat/PrizeUser')->getUserForPrize(($page - 1) * 10);
        return $result;
    }

    public function updateReceived($id, $received) {
        $result = M('Wechat/PrizeUser')->where('id='.$id)->setField('received', $received);
        return $result;
    }

    public function getOrder($cdate) {
        $orders = D('Order')->getOrder($cdate);
        return $orders;
    }

    public function getOrderItem($orderId) {
        $items = D('OrderItem')->getOrderItem($orderId);
        return $items;
    }

    public function addOrder($customer, $cdate, $earliest, $latest, $comment) {
        $id = D('Order')->addOrder($customer, $cdate, $earliest, $latest, $comment);
        return $id;
    }

    public function updateOrder($orderId, $customer, $cdate, $earliest, $latest, $comment) {
        $id = D('Order')->updateOrder($orderId, $customer, $cdate, $earliest, $latest, $comment);
        return $id;
    }

    public function deleteOrder($orderId) {
        $id = D('Order')->deleteOrder($orderId);
        return $id;
    }

    public function addOrderItem($orderId, $product, $quantity, $money, $comment) {
        $id = D('OrderItem')->addOrderItem($orderId, $product, $quantity, $money, $comment);
        return $id;
    }

    public function updateOrderItem($orderItemId, $product, $quantity, $money, $comment) {
        $id = D('OrderItem')->updateOrderItem($orderItemId, $product, $quantity, $money, $comment);
        return $id;
    }

    public function deleteOrderItem($orderItemId) {
        $id = D('OrderItem')->deleteOrderItem($orderItemId);
        return $id;
    }
}
