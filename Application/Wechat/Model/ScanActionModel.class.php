<?php
namespace Wechat\Model;
use Think\Model;
class ScanActionModel extends Model {
    public function addScanAction($openId) {
        $time = time();
        $user = M('User');
        $where['openId'] = $openId;
        $u = $user->where($where)->find();
        if($u !== null) {
            $scan = $this->where('userId='.$u['id'].' and score<>0')->order('ctime desc')->find();
            if($scan !== null) {
                $interval = $time - $scan['ctime'];
                if($interval < 60 * 60 * 6) {
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
            $u['score'] = $u['score'] + 10;
            $user->save($u);
        }
        return 10;
    }
}
