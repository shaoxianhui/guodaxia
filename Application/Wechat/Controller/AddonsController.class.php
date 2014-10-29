<?php
namespace Wechat\Controller;
use Think\Controller;
class AddonsController extends Controller {
    public function ggk() {
        $this->display();
    }

    public function dzp($openId = "openId") {
        $prizes = D('DzpPrize')->getPrizes();
        $rule = D('DzpConfig')->getConfigByName('rule');
        $this->assign('openId', $openId);
        $this->assign('prizes', $prizes);
        $this->assign('rule', $rule);
        $this->display();
    }

    public function dzp_data() {
        $r = rand(1, 100) / 100;
        $prize = D('DzpPrize')->isPrize($r);
        if($prize == null) {
            $data['success'] = false;
            $this->ajaxReturn($data);
        } else {
            $data['prizetype'] = $prize['description'];
            $data['sn'] = 'sn';
            $this->ajaxReturn($data);
        }
    }
}
