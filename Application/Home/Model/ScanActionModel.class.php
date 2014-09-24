<?php
namespace Home\Model;
use Think\Model;
class ScanActionModel extends Model {
    public function addRecord($openId) {
        $time = time();
        $user = M('User');
        $where['openId'] = $openId;
        $u = $user->where($where)->find();
        if($u !== null) {
            $scan = $this->where('userId='.$u['id'].' and score<>0')->order('ctime desc')->find();
            if($scan !== null) {
                $interval = $time - $scan['ctime'];
                if($interval < 60 * 60 * 12) {
                    $data['userId'] = $u['id'];
                    $data['score'] = 0;
                    $data['ctime'] = $time;
                    $this->data($data)->add();
                    return 0;
                }
            }
            $data['userId'] = $u['id'];
            $data['score'] = 10;
            $data['ctime'] = $time;
            $this->data($data)->add();
        }
        return 10;
    }
}
