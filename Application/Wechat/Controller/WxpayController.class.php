<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class WxpayController extends Controller {
    public function order( $code = null) {
		$pay = new \Org\Wechat\WxPay();
		if ($code == null) {
			$url = $pay->createOauthUrlForCode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            Header("Location: $url");
		} else {
			$openId = $pay->getOpenId($code);

            $pay->setParameter("openid","$openId");
            $pay->setParameter("body","办公室水果预约");
            $timeStamp = time();
            $out_trade_no = C('WECHAT.appid').substr($openId, -4)."$timeStamp";
            $pay->setParameter("out_trade_no","$out_trade_no"); 
            $pay->setParameter("total_fee","1");
            $pay->setParameter("notify_url","http://$_SERVER[HTTP_HOST]/wechat/wxpay/notify");
            $pay->setParameter("trade_type","JSAPI");
            $pay->getPrepayId();
            $jsApiParameters = $pay->getParameters();

            $map['openId'] = $openId;
            $order = M('UserOrder')->where($map)->order('ctime desc')->find();
            if($order != null) {
                $this->assign("name", $order['name']);
                $this->assign("phone", $order['phone']);
                $this->assign("locationId", $order['locationId']);
            } else {
                $user = D('User')->getUser($openId);
                if($user != null) {
                    $map['qrScene'] = $user['qrScene'];
                    $location = M('Location')->where($map)->find();
                    if($location != null) {
                        $this->assign("locationId", $location['id']);
                    }
                }
            }

            $moneys = array(25, 20, 15, 10, 5, 25, 25);
            $this->assign('money', $moneys[date('w')]);

            $this->assign('openId', $openId);
            $this->assign('trade', $out_trade_no);

            $locations = M('Location')->where('valid=1')->select();
            $this->assign('locations', $locations);

            $this->assign('jsApiParameters', $jsApiParameters);
            $this->display();
		}
    }

    public function notify() {
		$notify = new \Org\Wechat\WxPay();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
        $notify->saveData($xml);
        
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        $data = $notify->getData();
        if($data['return_code'] == 'SUCCESS') {
            if($data['result_code'] == 'SUCCESS') {
                $map['trade'] = $data['out_trade_no'];
                $order = M('UserOrder')->where($map)->find();
                if($order != null) {
                    $order['payment'] = $data['total_fee'];
                    M('UserOrder')->save($order);
                    D('UserOrder')->createOrderItem($order);
                    $weObj = new \Org\Wechat\Wechat(C('WECHAT'));
                    /* $message['touser'] = $order['openId']; */
                    /* $message['msgtype'] = 'text'; */
                    /* $message['text'] = array('content' => "您已预定水果成功"); */
                    /* $weObj->sendCustomMessage($message); */
                    $message['touser'] = $order['openId'];
                    $message['template_id'] = 'gEdLfypbMwa-c3mLb2-aCxOFMz9OGI-565k718QGt0c';
                    $message['url'] = 'www.baidu.com';
                    $message['topcolor'] = '#00FF00';
                    $message['data']['first'] = array('value' => '您好，欢迎购买果大侠产品。', 'color' => '#000000');
                    $message['data']['product'] = array('value' => '果大侠整果', 'color' => '#000000');
                    $message['data']['price'] = array('value' => ($order['payment'] / 100).'元', 'color' => '#000000');
                    $message['data']['time'] = array('value' => $order['cdate'], 'color' => '#000000');
                    $message['data']['remark'] = array('value' => '记得及时取回水果', 'color' => '#000000');
                    $weObj->sendTemplateMessage($message);
                }
            }
        }
        echo $returnXml;
    }

    public function success() {
        $this->display();
    }

    public function createOrder($openId, $trade, $locationId, $name, $phone) {
        $data['openId'] = $openId;
        $data['trade'] = $trade;
        $data['locationId'] = $locationId;
        $data['name'] = $name;
        $data['phone'] = $phone;
        $data['cdate'] = date('Y-m-d');
        $data['ctime'] = time();
        M('UserOrder')->data($data)->add();
        $result['success'] = true;
        $this->ajaxReturn($result);
    }

    public function test() {
        $locations = M('Location')->where('valid=1')->select();
        $this->assign('locations', $locations);
        $this->assign("locationId", 2);
        $jsApiParameters = "{}";
        $this->assign('jsApiParameters', $jsApiParameters);
        $openid = "dfdlfldgf";
        $moneys = array(25, 20, 15, 10, 5, 25, 25);
        $this->assign('money', $moneys[date('w')]);
        $this->assign('openId', $openid);
        $this->display();
    }
}
