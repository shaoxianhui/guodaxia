<?php
namespace Home\Model;
use Think\Model;
class FruitPriceModel extends Model {
    public function getFruitPrice($fruitId) {
        return $this->where('fruitId='.$fruitId)->select();
    }

    public function addFruitPrice($fruitId, $minPrice, $maxPrice, $cdate) {
        $data['fruitId'] = $fruitId;
        $data['minPrice'] = $minPrice;
        $data['maxPrice'] = $maxPrice;
        $data['cdate'] = $cdate;
        return $this->data($data)->add();
    }
}
