<?php
namespace Wechat\Model;
use Think\Model;
class ProductGroupModel extends Model {
    public function getProductGroupOfUser($openId) {
        $user = M('User');
        $where['openId'] = $openId;
        $u = $user->where($where)->find();
        if($u !== null) {
            $group = M('Group');
            $g = $group->where('qrScene='.$u['qrScene'])->find();
            if($g !== null) {
                $products = $this->where('groupId='.$g['id'])->select();
                return $products;
            }
        }
        return null;
    }
}
