<?php
namespace Home\Model;
use Think\Model;
class PrizeModel extends Model {
    public function getPrizeOfUser($openId)
    {
        $user = M('User');
        $where['openId'] = $openId;
        $u = $user->where($where)->find();
        if($u !== null)
        {
            $group = M('Group');
            $g = $group->where('qrScene='.$u['qrScene'])->find();
            if($g !== null)
            {
                $p_g = M('PrizeGroup');
                $prizes = $p_g->where('groupId='.$g['id'])->select();
                $ps = array();
                $count = 0;
                foreach($prizes as $p)
                {
                    $ps[$count] = $this->where('id='.$p['prizeId'])->find();
                    $count = $count + 1;
                }
                return $ps;
            }
        }
        return null;
    }
}
