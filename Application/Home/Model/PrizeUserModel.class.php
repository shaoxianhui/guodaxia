<?php
namespace Home\Model;
use Think\Model;
class PrizeUserModel extends Model {
    public function AddRecord($userId, $prizeId)
    {
        $data['userId'] = $userId;
        $data['prizeId'] = $prizeId;
        $data['ctime'] = time();
        $this->data($data)->add();
    }

    public function Received($userId)
    {
        $this->where('userId='.$userId)->order('ctime desc')->limit(1)->setField('received', 1);
    }
}
