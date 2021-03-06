<?php
namespace Wechat\Model;
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

    public function addLocationAndPhone($openId, $name, $company, $location, $phone, $advice) {
        $user = D('User')->getUser($openId);
        if($user !== null) {
            $prizeOfUser = $this->where('userId='.$user['id'].' and received=0')->order('ctime desc')->find();
            if($prizeOfUser !== null) {
                $prizeOfUser['userId'] = $user['id'];
                $prizeOfUser['location'] = $location;
                $prizeOfUser['phone'] = $phone;
                $prizeOfUser['name'] = $name;
                $prizeOfUser['company'] = $company;
                $prizeOfUser['advice'] = $advice;
                $this->data($prizeOfUser)->save();
            } else {
                $prizeOfUser['userId'] = $user['id'];
                $prizeOfUser['prizeId'] = 1;
                $prizeOfUser['location'] = $location;
                $prizeOfUser['phone'] = $phone;
                $prizeOfUser['name'] = $name;
                $prizeOfUser['company'] = $company;
                $prizeOfUser['advice'] = $advice;
                $prizeOfUser['ctime'] = time();
                $this->data($prizeOfUser)->add();
            }
        }
    }

    public function getUserForPrize($start = 0, $length = 10, $order = 'ctime asc') {
        $page = $start / $length + 1;
        $prizeOfUser = $this->order($order)->page($page.','.$length)->select();
        if($prizeOfUser === null)
            return array();
        for($i = 0; $i < count($prizeOfUser); $i++) {
            $prize = M('Prize')->find($prizeOfUser[$i]['prizeId']);
            $prizeOfUser[$i]['prizeName'] = $prize['name'];
        }
        return $prizeOfUser;
    }

    public function getCount() {
        return $this->count();
    }
}
