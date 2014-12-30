<?php
namespace Wechat\Model;
use Think\Model;
class PrizerModel extends Model {
    public function addPrizer($openId, $phone, $content) {
        $user = D('User')->getUser($openId);
        if($user !== null) {
            $data['userId'] = $user['id'];
            $prize = D('Prize')->getCurrentPrize();
            if($prize != null) {
                $data['prizeId'] = $prize['id'];
            }
            $data['phone'] = $phone;
            $data['content'] = $content;
            $data['ctime'] = time();
            $this->data($data)->add();
        }
    }

    public function received($id, $received) {
        return $this->where('id='.$id)->setField('received', $received);
    }

    public function getCount($search = null) {
        if($search == null || $search == '') {
            return $this->count();
        } else {
            $map['phone'] = array('like', '%'.$search.'%');
            return $this->where($map)->count();
        }
    }

    public function getPrizer($start = 0, $length = 10, $order = 'ctime desc', $search = null) {
        $page = $start / $length + 1;
        if($search == null || $search == '') {
            $prizer = $this->order($order)->page($page.','.$length)->select();
        } else {
            $map['phone'] = array('like', '%'.$search.'%');
            $prizer = $this->where($map)->order($order)->page($page.','.$length)->select();
        }
        if($prizer === null)
            return array();
        for($i = 0; $i < count($prizer); $i++) {
            $prize = M('Prize')->find($prizer[$i]['prizeId']);
            if($prize != null) {
                $prizer[$i]['prizeName'] = $prize['name'];
            } else {
                $prizer[$i]['prizeName'] = '未知';
            }
        }
        return $prizer;
    }

    public function convert() {
        $ps = M('PrizeUser')->select();
        foreach($ps as $p) {
            $data['userId'] = $p['userId'];
            $data['prizeId'] = $p['prizeId'];
            $data['phone'] = $p['phone'];
            $data['content'] = '领奖+'.$p['company'].'+'.$p['location'].'+'.$p['name'].'+'.$p['phone'].'+'.$p['advice'];
            $data['ctime'] = $p['ctime'];
            $this->data($data)->add();
        }
        
    }
}
