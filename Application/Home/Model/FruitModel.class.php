<?php
namespace Home\Model;
use Think\Model\RelationModel;
class FruitModel extends RelationModel {

    protected $_link = array(
        'FruitType' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'FruitType',
            'foreign_key' => 'type',
            'mapping_name' => 'fruitType'
        ),
        'FruitPrice' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'FruitPrice',
            'foreign_key' => 'fruitId',
            'mapping_name' => 'fruitPrices',
            'mapping_order' => 'cdate desc'
        )
    );

    public function getFruit($fruitTypeId) {
        return $this->where('type='.$fruitTypeId)->select();
    }

    public function addFruit($fruitTypeId, $name, $level, $area, $unit, $description, $loss) {
        $data['name'] = $name;
        $data['level'] = $level;
        $data['type'] = $fruitTypeId;
        $data['area'] = $area;
        $data['unit'] = $unit;
        $data['description'] = $description;
        $data['loss'] = $loss;
        return $this->data($data)->add();
    }

    public function updateFruit($fruitId, $name, $level, $area, $unit, $description, $loss) {
        $data['name'] = $name;
        $data['level'] = $level;
        $data['area'] = $area;
        $data['unit'] = $unit;
        $data['description'] = $description;
        $data['loss'] = $loss;
        return $this->where('id='.$fruitId)->data($data)->save();
    }
}
