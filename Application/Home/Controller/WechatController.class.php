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
            $this->weObj->text("hello, I'm wechat")->reply();
            exit;
            break;
        case \Org\Wechat\Wechat::MSGTYPE_EVENT:
            $event = $this->weObj->getRevEvent();
            if($event !== false)
            {
                switch(strtolower($event['event']))
                {
                case 'subscribe':
                    $message = "你好！欢迎关注果大侠官方微信！";
                    $user = M('User');
                    $user_data['openId'] = $this->weObj->getRevFrom();
                    $u = $user->where($user_data)->find();
                    if($u !== null)
                    {
                        $u['subscribe'] = 1;
                        if($this->weObj->getRevSceneId() !== false)
                        {
                            $u['qrScene'] = $this->weObj->getRevSceneId();
                        }
                        $user->save($u);
                        $message = "你好！欢迎回来继续关注果大侠官方微信！";
                    } else {
                        $user_data['subscribe'] = 1;
                        if($this->weObj->getRevSceneId() !== false)
                        {
                            $user_data['qrScene'] = $this->weObj->getRevSceneId();
                        }
                        $user_data['id'] = $user->data($user_data)->add();
                    }
                    if($this->weObj->getRevSceneId() !== false)
                    {
                        $group = M('Group');
                        $g = $group->where('qrScene='.$this->weObj->getRevSceneId())->find();
                        if($g !== null)
                        {
                            $this->weObj->updateGroupMembers($g['groupId'], $this->weObj->getRevFrom());
                        }
                    }
                    $this->weObj->text($message)->reply();
                    break;
                case 'unsubscribe':
                    $where['openId'] = $this->weObj->getRevFrom();
                    $user = M('User');
                    $user->subscribe = 0;
                    $user->where($where)->save();
                    break;
                case 'scan':
                    $time = time();
                    $user = M('User');
                    $where['openId'] = $this->weObj->getRevFrom();
                    $u = $user->where($where)->find();
                    if($u !== null)
                    {
                        $scan = M('ScanAction');
                        $s = $scan->where('userId='.$u['id'].' and score<>0')->order('ctime desc')->find();
                        if($s !== null)
                        {
                            $interval = $time - $s['ctime'];
                            if($interval < 60 * 60 * 12)
                            {
                                $this->weObj->text("你好！欢迎扫描二维码赢取奖品！")->reply();
                                $data['userId'] = $u['id'];
                                $data['score'] = 0;
                                $data['ctime'] = $time;
                                $scan->data($data)->add();
                                return;
                            }
                        }
                        $data['userId'] = $u['id'];
                        $data['score'] = 10;
                        $data['ctime'] = $time;
                        $scan->data($data)->add();
                        $r = rand(1, 100) / 100;
                        $this->weObj->text("你好！欢迎扫描二维码赢取奖品！"."幸运概率=".$r)->reply();
                    }
                    break;
                case 'click':
                    switch($event['key'])
                    {
                    case 'MENU_KEY_PRODUCT':
                        $news = array(
                            "0"=>array(
                                'Title'=>'产品介绍',
                                'Description'=>'鲜果切具有100%即开即食，水果种类一次多样搭配等特点，非常适合繁忙的工作之余享用，想吃多少吃多少，完全省去',
                                'PicUrl'=>'http://meirixianguo.com/heroesguo/Public/images/img1.jpg',
                                'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODAzMTAwMg==&mid=200662061&idx=1&sn=7c7dd1f7da9b127bb477561fd5808bc0#rd'
                            )
                        );
                        $this->weObj->news($news)->reply();
                        break;
                    case 'MENU_KEY_COMPANY':
                        $this->weObj->text("你好！公司介绍！")->reply();
                        break;
                    case 'MENU_KEY_ORDER':
                        $this->weObj->text("你好！在线下单！")->reply();
                        break;
                    case 'MENU_KEY_PRIZE':
                        $this->weObj->text("你好！领取奖品！")->reply();
                        break;
                    case 'MENU_KEY_SERVICE':
                        $this->weObj->text("你好！联系客服！")->reply();
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
            $news = array(
                "0"=>array(
                    'Title'=>'msg title',
                    'Description'=>'summary text',
                    'PicUrl'=>'http://182.92.163.69/mrxg/Public/test.jpg',
                    'Url'=>'http://182.92.163.69'
                )
            );
            $this->weObj->news($news)->reply();
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
                array('type'=>'click', 'name'=>'领取奖品', 'key'=>'MENU_KEY_PRIZE'),
                array('type'=>'click', 'name'=>'联系客服', 'key'=>'MENU_KEY_SERVICE')
            )
        )
    )
);
        $result = $this->weObj->createMenu($newmenu);
        echo $result;
    }

    public function getMenu() {
        $result = $this->weObj->getMenu();
        var_dump($result);
    }

    public function createGroup() {
        $result = $this->weObj->createGroup($_GET["name"]);
        echo $result;
    }

    public function getGroup() {
        $result = $this->weObj->getGroup();
        var_dump($result);
    }

    public function getQrcode() {
        $ticket = $this->weObj->getQRCode($_GET["scene_id"]);
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

    public function testDB() {
        $message = "你好！欢迎关注果大侠官方微信！";
        $user = M('User');
        $user_data['openId'] = "hello123";
        $u = $user->where($user_data)->find();
        if($u !== null)
        {
            $u['subscribe'] = 1;
            $user->save($u);
            $message = "你好！欢迎回来继续关注果大侠官方微信！";
        } else {
            $user_data['subscribe'] = 1;
            $user_data['id'] = $user->data($user_data)->add();
        }
        echo $message;
    }

    public function test() {
        $user = M('User');
        $u = $user->where('id=1')->find();

        $data = array('touser' => $u['openId'], 'msgtype' => 'text', 'text' => array('content' => 'hello world'));
        $this->weObj->sendCustomMessage($data);
    }
}
