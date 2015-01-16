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
            // 换取openId
			$openId = $pay->getOpenId($code);

            // 填充已知数据
            $pay->setParameter("openid","$openId");
            $pay->setParameter("body","办公室水果预约");
            $timeStamp = time();
            $out_trade_no = C('WECHAT.appid').substr($openId, -4)."$timeStamp";
            $pay->setParameter("out_trade_no","$out_trade_no"); 
            $pay->setParameter("notify_url","http://$_SERVER[HTTP_HOST]/wechat/wxpay/notify");
            $pay->setParameter("trade_type","JSAPI");

            // 计算价格
            $moneys = array(25, 20, 15, 10, 5, 25, 25);
            $money = $moneys[date('w')] * 100;

            // 查找代金券
            $where['openId'] = $openId;
            $where['valid'] = 1;
            $user_voucher = M('UserVoucher')->where($where)->order('ctime asc')->find();
            if($user_voucher != null) {
                $voucher = M('Voucher')->find($user_voucher['voucherId']);
                $pay->setParameter("attach", $user_voucher['id']);
                $money -= $voucher['money'];
                if($money <= 0) {
                    $money = 1;
                }
                $this->assign('money', ($money / 100).'元(使用了一张5元代金券)');
            } else {
                $this->assign('money', ($money / 100).'元');
            }
            $pay->setParameter("total_fee","$money");

            // 获得支付ID
            $pay->getPrepayId();

            // 生成package
            $jsApiParameters = $pay->getParameters();
            $this->assign('jsApiParameters', $jsApiParameters);

            // 查找用户手机号，名字，地点
            $this->assign("locationId", 1);
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
            $this->assign('openId', $openId);
            $this->assign('trade', $out_trade_no);

            // 填充地点信息
            $locations = M('Location')->where('valid=1')->select();
            $this->assign('locations', $locations);

            // 支持JSSDK的验证签名
            $jssdk = new \Org\Wechat\JSSDK(C('WECHAT.appid'), C('WECHAT.appsecret'));
            $signPackage = $jssdk->GetSignPackage();
            $this->assign('signPackage', $signPackage);

            $this->display();
		}
    }

    public function notify() {
		$notify = new \Org\Wechat\WxPay();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
        Log::write($xml, Log::INFO);
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
                // 确认收款
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
                    $message['url'] = 'http://mp.weixin.qq.com/s?__biz=MzAwODAzMTAwMg==&mid=200805974&idx=1&sn=36f55c2d0a9a9051bba2065f3ec4ce4b#rd';
                    $message['topcolor'] = '#00FF00';
                    $message['data']['first'] = array('value' => '您好，欢迎购买果大侠产品。', 'color' => '#000000');
                    $message['data']['product'] = array('value' => '果大侠整果', 'color' => '#000000');
                    $message['data']['price'] = array('value' => ($order['payment'] / 100).'元', 'color' => '#000000');
                    $message['data']['time'] = array('value' => $order['cdate'], 'color' => '#000000');
                    $message['data']['remark'] = array('value' => '记得及时取回水果', 'color' => '#000000');
                    $weObj->sendTemplateMessage($message);

                    // 销毁代金券
                    if(isset($data['attach'])) {
                        M('UserVoucher')->where('id='.$data['attach'])->setField('valid', 0);
                        unset($message);
                        $message['touser'] = $order['openId'];
                        $message['template_id'] = '2W160vGYDJmucz17GtSs-wTTXnXghpmGQCmx5RCTCgI';
                        $message['url'] = 'http://mp.weixin.qq.com/s?__biz=MzAwODAzMTAwMg==&mid=200805974&idx=1&sn=36f55c2d0a9a9051bba2065f3ec4ce4b#rd';
                        $message['topcolor'] = '#00FF00';
                        $message['data']['first'] = array('value' => '您好，你使用了一张果大侠代金券！', 'color' => '#000000');
                        $message['data']['keyword1'] = array('value' => 100000 + $order['id'], 'color' => '#000000');
                        $message['data']['keyword2'] = array('value' => ($data['total_fee'] / 100).'元', 'color' => '#000000');
                        $message['data']['keyword3'] = array('value' => '果大侠代金券', 'color' => '#000000');
                        $message['data']['keyword4'] = array('value' => 100000 + $data['attach'], 'color' => '#000000');
                        $message['data']['keyword5'] = array('value' => date('Y-m-d'), 'color' => '#000000');
                        $message['data']['remark'] = array('value' => '代金券在购买果大侠产品时自动减免相应金额，果大侠具有永久解释权', 'color' => '#000000');
                        $weObj->sendTemplateMessage($message);
                    }
                }
            }
        }
        echo $returnXml;
    }

    public function success($openId) {
        // 支持JSSDK的验证签名
        $jssdk = new \Org\Wechat\JSSDK(C('WECHAT.appid'), C('WECHAT.appsecret'));
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage', $signPackage);
        $this->assign('openId', $openId);
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

    public function getVoucher($openId = null, $voucherId = 1) {
        $voucher = M('Voucher')->find($voucherId);
        if($voucher == null) {
            $result['success'] = false;
            $result['message'] = '代金券获得已下线！';
            $this->ajaxReturn($result);
        } else {
            $map['openId'] = $openId;
            $map['voucherId'] = $voucherId;
            $count = M('UserVoucher')->where($map)->count();
            if($count < $voucher['ulimit']) {
                $data['openId'] = $openId;
                $data['voucherId'] = $voucherId;
                $data['valid'] = 1;
                $data['ctime'] = time();
                $id = M('UserVoucher')->data($data)->add();
                $weObj = new \Org\Wechat\Wechat(C('WECHAT'));
                $message['touser'] = $openId;
                $message['template_id'] = 'ftB_fcU7tcQkx5he06K51e_DnB3ue_jrHF-pXywR-lw';
                $message['url'] = 'http://mp.weixin.qq.com/s?__biz=MzAwODAzMTAwMg==&mid=200805974&idx=1&sn=36f55c2d0a9a9051bba2065f3ec4ce4b#rd';
                $message['topcolor'] = '#00FF00';
                $message['data']['first'] = array('value' => '您好，你已获得果大侠代金券一张！', 'color' => '#000000');
                $message['data']['keyword1'] = array('value' => '果大侠代金券', 'color' => '#000000');
                $message['data']['keyword2'] = array('value' => 100000 + $id, 'color' => '#000000');
                $message['data']['keyword3'] = array('value' => date('Y-m-d'), 'color' => '#000000');
                $message['data']['keyword4'] = array('value' => '永久', 'color' => '#000000');
                $message['data']['remark'] = array('value' => '代金券在购买果大侠产品时自动减免相应金额，果大侠具有永久解释权', 'color' => '#000000');
                $weObj->sendTemplateMessage($message);
                $result['success'] = true;
                $result['message'] = $voucher['description'];
                $this->ajaxReturn($result);

            } else {
                $result['success'] = false;
                $result['message'] = '您已达到代金券的上线！';
                $this->ajaxReturn($result);

            }
        }

    }
}
