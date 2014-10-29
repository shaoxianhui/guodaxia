<?php
namespace Wechat\Model;
use Think\Model;
class DzpPrizeModel extends Model {
    public function getPrizes() {
        $prizes = $this->select();
        return $prizes;
    }

    public function isPrize($probability) {
        $prizes = $this->where('quantity>0')->order('probability')->select();
        foreach($prizes as $prize) {
            if($probability < $prize['probability']) {
                    $prize['quantity'] = $prize['quantity'] - 1;
                    $this->save($prize);
                    return $prize;
            }
        }
        return null;
    }
}
