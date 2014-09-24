<?php
namespace Home\Model;
use Think\Model;
class PrizeUserModel extends Model {
    public function addRecord($userId, $prizeId) {
        $data['userId'] = $userId;
        $data['prizeId'] = $prizeId;
        $data['ctime'] = time();
        $this->data($data)->add();
    }

    public function received($userId) {
        $this->where('userId='.$userId)->order('ctime desc')->limit(1)->setField('received', 1);
    }
}
