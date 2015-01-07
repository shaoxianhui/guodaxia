<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class WxpayController extends Controller {
    public function order() {
		$pay = new \Org\Wechat\WxPay();
		if (!isset($_GET['code'])) {
			$url = $pay->createOauthUrlForCode("http://www.meirixianguo.com/wechat/wxpay/order");
            Header("Location: $url");
		} else {
			$code = $_GET['code'];
			$pay->setCode($code);
			$openid = $pay->getOpenId();
            $pay->setParameter("openid","$openid");//商品描述
            $pay->setParameter("body","贡献一分钱");//商品描述
            $timeStamp = time();
            $out_trade_no = C('WECHAT.appid')."$timeStamp";
            $pay->setParameter("out_trade_no","$out_trade_no");//商户订单号 
            $pay->setParameter("total_fee","1");//总金额
            $pay->setParameter("notify_url",'http://www.meirixianguo.com/wechat/wxpay/notify');//通知地址 
            $pay->setParameter("trade_type","JSAPI");//交易类型
            $prepay_id = $pay->getPrepayId();
            $pay->setPrepayId($prepay_id);
            $jsApiParameters = $pay->getParameters();
            $this->assign('jsApiParameters', $jsApiParameters);
            $this->display();
		}
    }

    public function notify() {

    }

    public function success() {
        $this->display();
    }
}
