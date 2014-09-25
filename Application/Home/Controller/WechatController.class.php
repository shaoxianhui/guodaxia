<?php
namespace Home\Controller;
use Think\Controller;
use Think\Log;
class WechatController extends Controller {
    private $weObj;
    public function __construct()
    {
        $options = array(
            'token'=>'meirixianguo',
            'appid'=>'wx5a89b696654c4d57',
            'appsecret'=>'07ca0ceb25d5303254434db6c1c7541e',
        );
        $this->weObj = new \Org\Wechat\Wechat($options);
    }

    public function index() {
        $this->weObj->valid();
        $type = $this->weObj->getRev()->getRevType();
        switch($type) {
        case \Org\Wechat\Wechat::MSGTYPE_TEXT:
            if(strstr($this->weObj->getRevContent(), "更换果切"))
            {
                $products = D('Product')->getProductOfUser($this->weObj->getRevFrom());
                if($products !== null)
                {
                    $msg = "";
                    $count = 1;
                    foreach($products as $p)
                    {
                        $msg = $msg.$count.':'.$p['name']."\r\n";
                        $count = $count + 1;
                    }
                    $this->weObj->text("请选择果盘种类:\r\n".trim($msg))->reply();
                }
            }
            else
            {
                $this->weObj->text("您好，欢迎使用果大侠微信服务，可发送命令：更换果切")->reply();
            }
            break;
        case \Org\Wechat\Wechat::MSGTYPE_EVENT:
            $event = $this->weObj->getRevEvent();
            if($event !== false)
            {
                switch(strtolower($event['event']))
                {
                case 'subscribe':
                    $add_or_update = D('User')->addUser($this->weObj->getRevFrom(), $this->weObj->getRevSceneId());
                    if($this->weObj->getRevSceneId() !== false)
                    {
                        $group = M('Group');
                        $g = $group->where('qrScene='.$this->weObj->getRevSceneId())->find();
                        if($g !== null)
                        {
                            $this->weObj->updateGroupMembers($g['groupId'], $this->weObj->getRevFrom());
                        }
                    }
                    if($add_or_update)
                    {
                        $this->weObj->text("您好，欢迎关注果大侠官方微信！")->reply();
                    } else {
                        $this->weObj->text("您好！欢迎回来继续关注果大侠官方微信！")->reply();
                    }
                    break;
                case 'unsubscribe':
                    $where['openId'] = $this->weObj->getRevFrom();
                    $user = M('User');
                    $user->subscribe = 0;
                    $user->where($where)->save();
                    break;
                case 'scan':
                    $score = D('ScanAction')->addRecord($this->weObj->getRevFrom());
                    if($score !== 0)
                    {
                        $r = rand(1, 100) / 100;
                        $prize = D('Prize')->isPrize($this->weObj->getRevFrom(), $r);
                        if($prize !== null)
                        {
                            $this->weObj->text("您好，扫描二维码获得奖品！")->reply();
                        } else {
                            $this->weObj->text("您好，扫描二维码获得积分！")->reply();
                        }
                    } else {
                        $this->weObj->text("您好，感谢您的关注！")->reply();
                    }
                    break;
                case 'click':
                    switch($event['key'])
                    {
                    case 'MENU_KEY_PRODUCT':
                        $news = D('News')->getNews(array(1, 2));
                        $this->weObj->news($news)->reply();
                        break;
                    case 'MENU_KEY_COMPANY':
                        $news = D('News')->getNews(array(1, 2));
                        $this->weObj->news($news)->reply();
                        break;
                    case 'MENU_KEY_ORDER':
                        $this->weObj->text("你好！在线下单！")->reply();
                        break;
                    case 'MENU_KEY_PRIZE':
                        $prizes = D('Prize')->getPrizeOfUser($this->weObj->getRevFrom());
                        if($prizes !== null)
                        {
                            $prize_news = array();
                            $count = 0;
                            foreach($prizes as $p)
                            {
                                $prize_news[$count] = array('Title' => $p['description'],
                                                            'PicUrl' => getWeChatImageUrl($p['picture']));
                                $count = $count + 1;
                            }
                            $this->weObj->news($prize_news)->reply();
                        }
                        break;
                    case 'MENU_KEY_SERVICE':
                        $this->weObj->reply(array('ToUserName' => $this->weObj->getRevFrom(),
                                                    'FromUserName' => $this->weObj->getRevTo(),
                                                    'CreateTime' => time(),
                                                    'MsgType' => 'transfer_customer_service'));
                        break;
                    default:
                        $this->weObj->text("你好！未知消息！")->reply();
                    }
                    break;
                default:
                }
            }
            break;
        case \Org\Wechat\Wechat::MSGTYPE_IMAGE:
            break;
        default:
            $this->weObj->text("help info")->reply();
        }
    }

    public function createMenu() {
        $newmenu = array(
            "button" => array(
                array('name'=>'产品介绍', 'sub_button'=>array(
                    array('type'=>'click', 'name'=>'产品介绍', 'key'=>'MENU_KEY_PRODUCT'),
                    array('type'=>'click', 'name'=>'公司介绍', 'key'=>'MENU_KEY_COMPANY')
                )
            ),
            array('type'=>'click','name'=>'在线预订','key'=>'MENU_KEY_ORDER'),
            array('name'=>'优惠活动', 'sub_button'=>array(
                array('type'=>'click', 'name'=>'本期奖品', 'key'=>'MENU_KEY_PRIZE'),
                array('type'=>'click', 'name'=>'联系客服', 'key'=>'MENU_KEY_SERVICE')
            )
        )
    )
);
        $result = $this->weObj->createMenu($newmenu);
        echo $result;
    }

    public function getQrcode() {
        $ticket = $this->weObj->getQRCode($_GET["scene_id"], 1);
        redirect($this->weObj->getQRUrl($ticket["ticket"]));
    }

    public function getUserList() {
        $users = $this->weObj->getUserList();
        var_dump($users);
    }

    public function getToken() {
        $result = $this->weObj->checkAuth();
        var_dump($result);
    }

    public function test() {
        $data['fruitId'] = 1;
        $data['minPrice'] = 4;
        $data['maxPrice'] = 5;
        $data['cdate'] = '2014-9-5';
        M('FruitPrice')->add($data);
    }
}
