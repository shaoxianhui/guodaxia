<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class IndexController extends Controller {
    private $weObj;
    public function __construct()
    {
        parent::__construct();
        $options = array(
            'token'=>'meirixianguo',
            'appid'=>'wx5a89b696654c4d57',
            'appsecret'=>'d7e8b0a65784d18a4e4cd3d437818fec',
        );
        $this->weObj = new \Org\Wechat\Wechat($options);
    }

    public function index() {
        $this->weObj->valid();
        $type = $this->weObj->getRev()->getRevType();
        switch($type) {
        case \Org\Wechat\Wechat::MSGTYPE_TEXT:
            D('Command')->executeAll($this->weObj->getRevContent(), $this);
            if(preg_match('/^gdx$/i', $this->weObj->getRevContent())) {
                $products = D('Product')->getProductNewsOfUser($this->weObj->getRevFrom());
                if($products !== null) {
                    $this->weObj->news($products)->reply();
                } else {
                    $this->weObj->text(D('Text')->getText(8))->reply();
                }
            } else if(preg_match('/^gdx\+(\d+)$/i', $this->weObj->getRevContent(), $m)) {
                D('ProductUser')->addProductOfUser($this->weObj->getRevFrom(), $m[1]);
                $this->weObj->text(D('Text')->getText(7))->reply();
            } else if(preg_match('/^领奖\+.*$/i', $this->weObj->getRevContent(), $m)) {
                $list = explode('+', $m[0]);
                if(count($list) == 6) {
                    D('PrizeUser')->addLocationAndPhone($this->weObj->getRevFrom(), $list[3], $list[1], $list[2], $list[4], $list[5]);
                    $message = D('Text')->getText(9);
                    /* $message = preg_replace('/\{1\}/i', $list[2], $message); */
                    /* $message = preg_replace('/\{2\}/i', $list[4], $message); */
                    $this->weObj->text($message)->reply();
                } else {
                    $this->weObj->text(D('Text')->getText(12))->reply();
                }
            } else if(preg_match('/^我来兑奖$/i', $this->weObj->getRevContent())) {
                $prizes = D('Prize')->getPrizeOfUser($this->weObj->getRevFrom());
                if($prizes !== null) {
                    $prize_news = array();
                    $count = 0;
                    foreach($prizes as $p) {
                        $des = explode('</br>', $p['description']);
                        $prize_news[$count] = array('Title' => $p['name'],
                                                    'PicUrl' => getWeChatImageUrl($p['picUrl']),
                                                    'Description' => $des[0],
                                                    'Url' => $p['url'] == null ? getWeChatDetailUrl($p['id'], 'prize') : $p['url']);
                        $count = $count + 1;
                    }
                    $this->weObj->news($prize_news)->reply();
                }
            } else {
                $this->weObj->text(D('Text')->getText(1))->reply();
            }
            break;
        case \Org\Wechat\Wechat::MSGTYPE_EVENT:
            $event = $this->weObj->getRevEvent();
            if($event !== false) {
                switch(strtolower($event['event'])) {
                case 'subscribe':
                    $add_or_update = D('User')->addUser($this->weObj->getRevFrom(), $this->weObj->getRevSceneId());
                    if($this->weObj->getRevSceneId() !== false) {
                        $group = M('Group');
                        $g = $group->where('qrScene='.$this->weObj->getRevSceneId())->find();
                        if($g !== null) {
                            $this->weObj->updateGroupMembers($g['groupId'], $this->weObj->getRevFrom());
                        }
                    }
                    if($add_or_update) {
                        $this->weObj->text(D('Text')->getText(1))->reply();
                    } else {
                        $this->weObj->text(D('Text')->getText(2))->reply();
                    }
                    break;
                case 'unsubscribe':
                    $where['openId'] = $this->weObj->getRevFrom();
                    $user = M('User');
                    $user->subscribe = 0;
                    $user->where($where)->save();
                    break;
                case 'scan':
                    D('User')->changeSceneOfUser($this->weObj->getRevFrom(), $this->weObj->getRevSceneId());
                    $score = D('ScanAction')->addScanAction($this->weObj->getRevFrom());
                    if($score !== 0) {
                        $r = rand(1, 100) / 100;
                        $prize = D('Prize')->isPrize($this->weObj->getRevFrom(), $r);
                        if($prize !== null) {
                            $this->weObj->text(D('Text')->getText(5))->reply();
                        } else {
                            $this->weObj->text(D('Text')->getText(6))->reply();
                        }
                    } else {
                        $this->weObj->text(D('Text')->getText(4))->reply();
                    }
                    break;
                case 'click':
                    switch($event['key']) {
                    case 'MENU_KEY_PRODUCT':
                        $news = D('News')->getNews(array(1));
                        $this->weObj->news($news)->reply();
                        break;
                    case 'MENU_KEY_COMPANY':
                        $news = D('News')->getNews(array(2));
                        $this->weObj->news($news)->reply();
                        break;
                    case 'MENU_KEY_ORDER':
                        $this->weObj->text(D('Text')->getText(11))->reply();
                        break;
                    case 'MENU_KEY_PRIZE':
                        $prizes = D('Prize')->getPrizeOfUser($this->weObj->getRevFrom());
                        if($prizes !== null) {
                            $prize_news = array();
                            $count = 0;
                            foreach($prizes as $p) {
                                $des = explode('</br>', $p['description']);
                                $prize_news[$count] = array('Title' => $p['name'],
                                                            'PicUrl' => getWeChatImageUrl($p['picUrl']),
                                                            'Description' => $des[0],
                                                            'Url' => $p['url'] == null ? getWeChatDetailUrl($p['id'], 'prize') : $p['url']);
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
                        $data['touser'] = $this->weObj->getRevFrom();
                        $data['msgtype'] = 'text';
                        $data['text'] = array('content' => D('Text')->getText(10));
                        $this->weObj->sendCustomMessage($data);
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
            $this->weObj->text(D('Text')->getText(3))->reply();
        }
    }

    public function createMenu() {
        $result = $this->weObj->createMenu(D('Menu')->getMenus());
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

    public function detail($id = 1, $type = 'product') {
        switch($type) {
        case 'product':
            $product = M('Product')->find($id);
            if($product !== null) {
                $content['title'] = $product['name'];
                $content['picUrl'] = getWeChatImageUrl($product['picUrl']);
                $content['description'] = $product['description'];
            }
            break;
        case 'prize':
            $prize = M('Prize')->find($id);
            if($prize !== null) {
                $content['title'] = $prize['name'];
                $content['picUrl'] = getWeChatImageUrl($prize['picUrl']);
                $content['description'] = $prize['description'];
            }
            break;
        case 'news':
            $news = M('News')->find($id);
            if($news !== null) {
                $content['title'] = $news['title'];
                $content['picUrl'] = getWeChatImageUrl($news['picUrl']);
                $content['description'] = $news['description'];
            }
            break;
        default:
            break;
        }
        $this->assign('content', $content);
        $this->display();
    }

    public function sendCustomMessage($userId) {
        $user = M('User')->find($userId);
        if($user !== null) {
            $data['touser'] = $user['openId'];
            $data['msgtype'] = 'text';
            $data['text'] = array('content' => $this->weObj->getRevContent());
            $this->weObj->sendCustomMessage($data);
        }
    }

    public function test($openId = 'oGulKs0s3IAdDEF9sd0Nki7MoYp8') {
    }
}
