<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class FruitPriceModel extends RelationModel {

    protected $_link = array(
        'Fruit' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Fruit',
            'foreign_key' => 'fruitId',
            'mapping_name' => 'fruit'
        )
    );

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

    public function updateFruitPrice($fruitPriceId, $minPrice, $maxPrice, $cdate) {
        $data['minPrice'] = $minPrice;
        $data['maxPrice'] = $maxPrice;
        $data['cdate'] = $cdate;
        return $this->where('id='.$fruitPriceId)->data($data)->save();
    }
}
