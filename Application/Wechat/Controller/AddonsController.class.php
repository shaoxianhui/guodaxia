<?php
namespace Wechat\Controller;
use Think\Controller;
class AddonsController extends Controller {
    public function oAuth() {
        $weObj = new \Org\Wechat\Wechat(C('WECHAT'));
        $access_token = $weObj->getOauthAccessToken();
        if($access_token) {
            $this->assign('openId', $access_token['openid']);
            $this->display($_GET['state']);
        } else {
            die('no access!');
        }
        
    }
    public function ggk() {
    }

    public function dzp() {
    }

    public function dzp_data() {
        $data['success'] = true;
        $data['prizetype'] = 2;
        $data['sn'] = 'sn';
        $this->ajaxReturn($data);
    }
}
