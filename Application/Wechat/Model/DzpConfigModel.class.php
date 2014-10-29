<?php
namespace Wechat\Model;
use Think\Model;
class DzpConfigModel extends Model {
    public function getConfigByName($name) {
        $where['name'] = $name;
        $config = $this->where($where)->find();
        return $config['text'];
    }
}
