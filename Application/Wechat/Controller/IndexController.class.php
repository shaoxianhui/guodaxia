<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class IndexController extends Controller {
    private $weObj;
    public function __construct()
    {
        parent::__construct();
        $this->weObj = new \Org\Wechat\Wechat(C('WECHAT'));
    }

    public function index() {
        $this->weObj->valid();
        $type = $this->weObj->getRev()->getRevType();
        switch($type) {
        case \Org\Wechat\Wechat::MSGTYPE_TEXT:
            D('TextMessage')->addTextMessage($this->weObj->getRevFrom(), $this->weObj->getRevContent(), $this->weObj->getRevCtime());
            D('Command')->executeAll($this->weObj->getRevContent(), $this);
            if(preg_match('/.*(\d{11,}).*/i', $this->weObj->getRevContent(), $m)) {
                D('Prizer')->addPrizer($this->weObj->getRevFrom(), $m[1], $m[0]);
                $message = D('Text')->getText(9);
                $this->weObj->text($message)->reply();
            } else {
                //$this->weObj->text(D('Text')->getText(1))->reply();
            }
            break;
        case \Org\Wechat\Wechat::MSGTYPE_EVENT:
            $event = $this->weObj->getRevEvent();
            if($event !== false) {
                switch(strtolower($event['event'])) {
                case 'subscribe':
                    $info = $this->weObj->getUserInfo($this->weObj->getRevFrom());
                    $add_or_update = D('User')->addUser($this->weObj->getRevFrom(),  $info['nickname'], $this->weObj->getRevSceneId());
                    if(is_numeric($this->weObj->getRevSceneId())) {
                        D('ScanAction')->addScanAction($this->weObj->getRevFrom());
                        $location = M('Location')->where('qrScene='.$this->weObj->getRevSceneId())->find();
                        if($location !== null) {
                            $this->weObj->updateGroupMembers($location['groupId'], $this->weObj->getRevFrom());
                        }
                        $this->weObj->text(D('Text')->getText(13))->reply();
                    }
                    if($add_or_update) {
                        $this->weObj->text(D('Text')->getText(13))->reply();
                    } else {
                        $this->weObj->text(D('Text')->getText(13))->reply();
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
                        /* $r = rand(1, 100) / 100; */
                        /* $prize = D('Prize')->isPrize($this->weObj->getRevFrom(), $r); */
                        /* if($prize !== null) { */
                        /*     $des = explode('</br>', $prize['description']); */
                        /*     $prize_news[0] = array('Title' => $prize['name'], */
                        /*                                 'PicUrl' => getWeChatImageUrl($prize['picUrl']), */
                        /*                                 'Description' => '恭喜您！您扫描二维码中奖啦！'.$des[0], */
                        /*                                 'Url' => $prize['url'] == null ? getWeChatDetailUrl($prize['id'], 'prize') : $prize['url']); */
                        /*     $this->weObj->news($prize_news)->reply(); */
                        /* } else { */
                        /*     $this->weObj->text(D('Text')->getText(4))->reply(); */
                        /* } */
                        $this->weObj->text(D('Text')->getText(13))->reply();
                    } else {
                        $this->weObj->text(D('Text')->getText(13))->reply();
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
                    case 'MENU_KEY_INSTRUCTION':
                        $news = D('News')->getNews(array(4));
                        $this->weObj->news($news)->reply();
                        break;
                    case 'MENU_KEY_ORDER':
                        $this->weObj->text(D('Text')->getText(11))->reply();
                        break;
                    case 'MENU_KEY_PRIZE':
                        $news = D('News')->getNews(array(5));
                        $this->weObj->news($news)->reply();
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
        dump(D('Menu')->getMenus());
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

    public function sendCustomMessage($userId, $Qy) {
        $user = M('User')->find($userId);
        if($user !== null) {
            $data['touser'] = $user['openId'];
            $data['msgtype'] = 'text';
            $data['text'] = array('content' => $this->weObj->getRevContent());
            $this->weObj->sendCustomMessage($data);
        }
        if($Qy != null && $Qy != '') {
            A($Qy)->publish($this->weObj->getRevContent());
        }
    }

    public function test($openId = 'oGulKs0s3IAdDEF9sd0Nki7MoYp8') {
        //D('Prizer')->convert();
        /* @\Common\Library\PhpQrCode\TQrCode::png('http://www.meirixianguo.com', false, 'H', 20); */
        /* $count = @\Common\Library\Weimi\WeimiSMS::sendNotifySMS(1); */
        /* echo $count; */
        /* echo date('w', strtotime('2015-1-2 +1 day')); */
    }

    public function OAuth($code = null) {
        if($code == null) {
            $url = $this->weObj->getOauthRedirect("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '', 'snsapi_base');
            Header("Location: $url");
        }
        $access_token = $this->weObj->getOauthAccessToken();
        if($access_token) {
            dump($access_token);
        } else {
            die('no access!');
        }
    }
	
	public function jssdk() {
        $jssdk = new \Org\Wechat\JSSDK(C('WECHAT.appid'), C('WECHAT.appsecret'));
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage', $signPackage);
        $this->display();
    }
}
