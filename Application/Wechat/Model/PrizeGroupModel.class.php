<?php
namespace Wechat\Model;
use Think\Model;
class PrizeGroupModel extends Model {
    public function getPrizeGroupOfUser($openId) {
        $user = M('User');
        $where['openId'] = $openId;
        $u = $user->where($where)->find();
        if($u !== null) {
            $group = M('Group');
            $g = $group->where('qrScene='.$u['qrScene'])->find();
            if($g !== null) {
                $prizes = $this->where('groupId='.$g['id'])->order('probability')->select();
                return $prizes;
            }
        }
        return null;
    }
}
