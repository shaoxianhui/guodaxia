<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class FruitTypeModel extends RelationModel {

    protected $_link = array(
        'Fruit' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'Fruit',
            'foreign_key' => 'type',
            'mapping_name' => 'fruits',
            'mapping_order' => 'id'
        )
    );

    public function getFruitType() {
        return $this->select();
    }

    public function addFruitType($name) {
        $data['name'] = $name;
        return $this->data($data)->add();
    }

    public function updateFruitType($fruitTypeId, $name) {
        $data['name'] = $name;
        return $this->where('id='.$fruitTypeId)->data($data)->save();
    }
}
