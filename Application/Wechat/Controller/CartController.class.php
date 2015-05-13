<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class CartController extends Controller {
    public function index($code = null) {
		$pay = new \Org\Wechat\WxPay();
		if ($code == null) {
			$url = $pay->createOauthUrlForCode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            Header("Location: $url");
		} else {
			$openId = $pay->getOpenId($code);
		$cart = new \Org\Util\Cart($openId);
            $this->assign('openId', $openId);
        $start = array(1, 1, 1, 1, 1, 3, 2);
        $count = array(5, 4, 3, 2, 1, 5, 5);
        $week = date('w');
        $data = array();
        for($c = 0; $c < $count[$week]; $c++) {
            $nextdate  = date('n.d', strtotime("+".($c + $start[$week])." day"));
            $nextweek  = date('w', strtotime("+".($c + $start[$week])." day"));
            $map['week'] = $nextweek;
            $ps = M('Product')->where($map)->select();
            if($ps !== null) {
                foreach($ps as &$p) {
                    $p['isAtCart'] = $cart->isAtCart($p['id']);
                }
                $data[$nextdate] = array('week' => $nextweek, 'ps'=>$ps);
            }
        }

        $this->assign('products', $data);
        $this->assign('weekname', array('周日','周一','周二','周三','周四','周五','周六'));
            $cart = new \Org\Util\Cart($openId);
            $this->display();
        }
    }
    public function order_submit($openId = null) {
		$cart = new \Org\Util\Cart($openId);
        $this->assign('openId', $openId);
        $info = $cart->get_cart_info();
        $week = date('w');
        $min = array(1, 2, 3, 4, 5, 1, 1);
        $jiange = array(0, 0, 0, 0, 0, 5, 6);
        $ps = array();
        foreach($info['products_list'] as $p) {
            if($p['week'] < $min[$week]) {
                continue;
            }
            $nextdate  = date('n.d', strtotime("+".($p['week'] - $week + $jiange[$week])." day"));
            if(!isset($ps[$p['week']]))
                $ps[$p['week']] = array('day'=>$nextdate, 'ps' => array());
            array_push($ps[$p['week']]['ps'], $p);
        }
        ksort($ps);
        $this->assign('ps', $ps);
        $this->assign('money', $info['total_price']);
        $this->assign('weekname', array('周日','周一','周二','周三','周四','周五','周六'));
        $this->display();
    }
    public function add($openId = null, $product_id = null) {
		$cart = new \Org\Util\Cart('openId');
        $this->ajaxReturn($cart->add_product($product_id));
    }
    public function dec($openId = null, $product_id = null) {
		$cart = new \Org\Util\Cart('openId');
        $this->ajaxReturn($cart->dec_product($product_id));

    }
    public function del($openId = null, $product_id = null) {
		$cart = new \Org\Util\Cart('openId');
        $this->ajaxReturn($cart->delete_product($product_id));

    }
    public function clear() {
		$cart = new \Org\Util\Cart('openId');
        $cart->empty_cart();
    }
    public function createOrder($openId = null) {

		$cart = new \Org\Util\Cart('openId');
        $info = $cart->get_cart_info();
        if($info['total_price']){

		$pay = new \Org\Wechat\WxPay();

        $timeStamp = time();
        $out_trade_no = C('WECHAT.appid').substr($openId, -4)."$timeStamp";

        $data['openId'] = $openId;
        $data['trade'] = $out_trade_no;
        $data['locationId'] = 1;
        $data['name'] = 'name';
        $data['phone'] = 'phone';
        $data['cdate'] = date('Y-m-d');
        $data['ctime'] = time();
        $id = M('Order')->data($data)->add();

        // 填充已知数据
        $pay->setParameter("openid","$openId");
        $pay->setParameter("body","办公室水果预约");
        $pay->setParameter("attach",$id);
        $pay->setParameter("out_trade_no","$out_trade_no"); 
        $pay->setParameter("notify_url","http://$_SERVER[HTTP_HOST]/wechat/cart/notify");
        $pay->setParameter("trade_type","JSAPI");
        $pay->setParameter("total_fee",floor($info['total_price'] * 100));
        //$pay->setParameter("total_fee",1);
        $pay->getPrepayId();
        $result['jsApiParameters'] = json_decode($pay->getParameters());
        $result['test'] = $pay->getParameters();
        $result['openId'] = $openId;
        $result['success'] = true;
        $cart->empty_cart();
        $this->ajaxReturn($result);
        } else {
        $result['success'] = false;
        $result['message'] = 'session null!';
        $this->ajaxReturn($result);

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
                $order = M('Order')->find($data['attach']);
                if($order != null && $order['payment'] == 0) {
                    $order['payment'] = $data['total_fee'];
                    M('Order')->save($order);
                }
            }
        }
        echo $returnXml;
    }

    public function order($openId = 123) {
        if($openId !== null) {
            $cart = new \Org\Util\Cart($openId);
            $this->assign('openId', $openId);
            $this->assign('info', $cart->get_cart_info());
            $this->assign('date', date('Y-n-d'));
            $this->display();
        }
    }
}
