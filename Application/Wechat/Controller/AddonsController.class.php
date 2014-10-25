<?php
namespace Wechat\Controller;
use Think\Controller;
class AddonsController extends Controller {
    public function ggk() {
        $this->display();
    }

    public function dzp() {
        $this->display();
    }

    public function dzp_data() {
        $data['success'] = true;
        $data['prizetype'] = 2;
        $data['sn'] = 'sn';
        $this->ajaxReturn($data);
    }
}
