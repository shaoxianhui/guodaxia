<?php
namespace Home\Model;
use Think\Model;
class PrizeModel extends Model {
    public function getPrizeOfUser($openId) {
        $prizeGroup = D('PrizeGroup')->getPrizeGroupOfUser($openId);
        if($prizeGroup !== null) {
            $prizes = array();
            $count = 0;
            foreach($prizeGroup as $p) {
                $prizes[$count] = $this->where('id='.$p['prizeId'])->find();
                $count = $count + 1;
            }
            return $prizes;
        }
        return null;
    }

    public function isPrize($openId, $probability) {
        $prizeGroup = D('PrizeGroup')->getPrizeGroupOfUser($openId);
        $prize = null;
        if($prizeGroup !== null) {
            foreach($prizeGroup as $p) {
                if($probability < $p['probability']) {
                    $prize = $this->where('id='.$p['prizeId'])->find();
                    if($prize['quantity'] > 0) {
                        $prize['quantity'] = $prize['quantity'] - 1;
                        $this->save($prize);
                        D('PrizeUser')->addPrizeOfUser($openId, $p['prizeId']);
                        return $prize;
                    } else {
                        continue;
                    }
                }
            }
        }
        return $prize;
    }
}
