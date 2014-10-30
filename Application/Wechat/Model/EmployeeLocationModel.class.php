<?php
namespace Wechat\Model;
use Think\Model;
class EmployeeLocationModel extends Model {
    public function addLocation($userId, $location) {
        $data['userId'] = $userId;
        $data['latitude'] = $location['x'];
        $data['longitude'] = $location['y'];
        $data['precision'] = $location['precision'];
        $data['ctime'] = time();
        $this->add($data);
    }
    public function getLocation($userId) {
        $where['userId'] = $userId;
        $data = $this->where($where)->order('id desc')->find();
        if($data !== null) {
            $location['x'] = $data['latitude'];
            $location['y'] = $data['longitude'];
            $location['precision'] = $data['precision'];
            $location['ctime'] = $data['ctime'];
            return $location;
        }
        return null;
    }
}
