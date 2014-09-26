<?php
namespace Home\Model;
use Think\Model;
class FruitTypeModel extends Model {
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
