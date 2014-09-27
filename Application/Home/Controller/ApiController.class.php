<?php
namespace Home\Controller;
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
        $result = D('PrizeUser')->getUserForPrize($page);
        return $result;
    }
}
