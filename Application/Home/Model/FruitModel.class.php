<?php
namespace Home\Model;
use Think\Model;
class FruitModel extends Model {
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
