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

    public function dzp($openId = "oGulKs0s3IAdDEF9sd0Nki7MoYp8") {
        $user = D('User')->getUser($openId);
        $result = D('DzpUser')->getUserAction($user);
        if(is_numeric($result)) {
            $maxCount = D('DzpConfig')->getConfigByName('maxCount');
            if($result >= $maxCount) {
                $this->assign('enableDzp', false);
                $this->assign('message', '已超过抽奖次数');
            } else {
                $this->assign('enableDzp', true);
                $this->assign('count', $maxCount - $result);
            }
        } else {
            $this->assign('enableDzp', false);
            $this->assign('message', '您已中得奖品：'.$result['description']);
        }
        $prizes = D('DzpPrize')->getPrizes();
        $rule = D('DzpConfig')->getConfigByName('rule');
        $this->assign('openId', $openId);
        $this->assign('prizes', $prizes);
        $this->assign('rule', $rule);
        $this->display();
    }

    public function dzp_data($openId = "oGulKs0s3IAdDEF9sd0Nki7MoYp8") {
        $user = D('User')->getUser($openId);
        if($user !== null) {
            $action['userId'] = $user['id'];
            $prize = D('DzpPrize')->isPrize();
            if($prize !== null) {
                $action['prizeId'] = $prize['id'];
                $data['success'] = true;
                $data['prize'] = $prize['id'];
                D('DzpUser')->add($action);
                $this->ajaxReturn($data);
            }
        }
        $data['success'] = false;
        D('DzpUser')->add($action);
        $this->ajaxReturn($data);
    }
}
