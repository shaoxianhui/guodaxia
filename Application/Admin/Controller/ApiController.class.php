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

    public function getPrizer($page) {
        $result = D('Wechat/Prizer')->getPrizer(($page - 1) * 10);
        return $result;
    }

    public function updateReceived($id, $received) {
        $result = D('Wechat/Prizer')->received($id, $received);
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
        $data = D('Order')->addOrder($customer, $cdate, $earliest, $latest, $comment);
        A('Wechat/Order')->publish($data['message']);
        return $data['result'];
    }

    public function updateOrder($orderId, $customer, $cdate, $earliest, $latest, $comment) {
        $data = D('Order')->updateOrder($orderId, $customer, $cdate, $earliest, $latest, $comment);
        A('Wechat/Order')->publish($data['message']);
        return $data['result'];
    }

    public function deleteOrder($orderId) {
        $data = D('Order')->deleteOrder($orderId);
        A('Wechat/Order')->publish($data['message']);
        return $data['result'];
    }

    public function addOrderItem($orderId, $product, $quantity, $money, $comment) {
        $data = D('OrderItem')->addOrderItem($orderId, $product, $quantity, $money, $comment);
        A('Wechat/Order')->publish($data['message']);
        return $data['result'];
    }

    public function updateOrderItem($orderItemId, $product, $quantity, $money, $comment) {
        $data = D('OrderItem')->updateOrderItem($orderItemId, $product, $quantity, $money, $comment);
        A('Wechat/Order')->publish($data['message']);
        return $data['result'];
    }

    public function deleteOrderItem($orderItemId) {
        $data = D('OrderItem')->deleteOrderItem($orderItemId);
        A('Wechat/Order')->publish($data['message']);
        return $data['result'];
    }
}
