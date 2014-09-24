<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
    public function getUser($openId) {
        $where['openId'] = $openId;
        return $this->where($where)->find();
    }

    public function addUser($openId, $qrScene) {
        $add_or_update = true;
        $user_data['openId'] = $openId;
        $user = $this->where($user_data)->find();
        if($user !== null) {
            $user['subscribe'] = 1;
            if($qrScene !== false) {
                $user['qrScene'] = $qrScene;
            }
            $this->save($user);
            $add_or_update = false;
        } else {
            $user_data['subscribe'] = 1;
            if($qrScene !== false) {
                $user_data['qrScene'] = $qrScene;
            }
            $this->data($user_data)->add();
        }
        return $add_or_update;
    }
}
