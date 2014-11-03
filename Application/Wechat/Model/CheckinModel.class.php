<?php
namespace Wechat\Model;
use Think\Model;
class CheckinModel extends Model {
    public function addCheckin($userId, $locationId) {
        $data['userId'] = $userId;
        $data['locationId'] = $locationId;
        $data['ctime'] = time();
        $this->add($data);
    }

    public function getCheckin($userId) {
        $where['userId'] = $userId;
        $checkin = $this->where($where)->order('id desc')->find();
        return $checkin;
    }
}
