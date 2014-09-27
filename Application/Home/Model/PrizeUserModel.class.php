<?php
namespace Home\Model;
use Think\Model;
class PrizeUserModel extends Model {
    public function addPrizeOfUser($openId, $prizeId) {
        $user = D('User')->getUser($openId);
        if($user !== null) {
            $data['userId'] = $user['id'];
            $data['prizeId'] = $prizeId;
            $data['ctime'] = time();
            $this->data($data)->add();
        }
    }

    public function received($userId) {
        $this->where('userId='.$userId)->order('ctime desc')->limit(1)->setField('received', 1);
    }

    public function addLocationAndPhone($openId, $location, $phone) {
        $user = D('User')->getUser($openId);
        if($user !== null) {
            $prizeOfUser = $this->where('userId='.$user['id'])->order('ctime desc')->find();
            if($prizeOfUser !== null) {
                $prizeOfUser['location'] = $location;
                $prizeOfUser['phone'] = $phone;
                $this->data($prizeOfUser)->save();
            }
        }
    }
}
