<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class QyController extends Controller {
    private $weObj;
    public function __construct()
    {
        parent::__construct();
        $options = array(
            'token'=>'meirixianguo',
            'appid'=>'wx0f3313fd2a9eed6e',
            'appsecret'=>'5WpAyyPu4RtWtZ6qufgnBuaAGvZJ26wwgJuy0k7fYAaYDyR8X0UHEabkPw-H0ugV',
 			'encodingaeskey'=>'dkPjxDa2pNi9xYa1dbakWn7kMxXajQxF9rDteEevP83',
 			'agentid'=>'1',
        );
        $this->weObj = new \Org\Wechat\QyWechat($options);
    }

    public function index() {
        $this->weObj->valid();
        $type = $this->weObj->getRev()->getRevType();
        switch($type) {
        case \Org\Wechat\Wechat::MSGTYPE_TEXT:
            $this->weObj->text('hello world!')->reply();
            break;
        case \Org\Wechat\Wechat::MSGTYPE_EVENT:
            $event = $this->weObj->getRevEvent();
            if($event !== false) {
                switch(strtolower($event['event'])) {
                case 'subscribe':
                    break;
                case 'unsubscribe':
                    break;
                case 'scan':
                    break;
                case 'click':
                    break;
                default:
                }
            }
            break;
        case \Org\Wechat\Wechat::MSGTYPE_IMAGE:
            $this->weObj->text('hello world!')->reply();
            break;
        default:
            $this->weObj->text('hello world!')->reply();
        }
    }

    public function createMenu() {
        $result = $this->weObj->createMenu(D('Menu')->getMenus());
        echo $result;
    }

    public function test($openId = 'oGulKs0s3IAdDEF9sd0Nki7MoYp8') {
        $this->display();
    }
}
