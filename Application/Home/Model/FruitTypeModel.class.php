<?php
namespace Home\Model;
use Think\Model;
class FruitTypeModel extends Model {
    public function getFruitType()
    {
        return $this->select();
    }

    public function addFruitType($name)
    {
        $data['name'] = $name;
        return $this->data($data)->add();
    }
}
