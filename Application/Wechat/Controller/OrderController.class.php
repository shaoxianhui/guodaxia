<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class OrderController extends Controller {
    private $weObj;
    public function __construct()
    {
        parent::__construct();
        $options = array(
            'token'=>'meirixianguo',
            'appid'=>'wx0f3313fd2a9eed6e',
            'appsecret'=>'5WpAyyPu4RtWtZ6qufgnBuaAGvZJ26wwgJuy0k7fYAaYDyR8X0UHEabkPw-H0ugV',
 			'encodingaeskey'=>'dkPjxDa2pNi9xYa1dbakWn7kMxXajQxF9rDteEevP83',
 			'agentid'=>'5',
        );
        $this->weObj = new \Org\Wechat\QyWechat($options);
    }

    public function index() {
        $this->weObj->valid();
        $type = $this->weObj->getRev()->getRevType();
        switch($type) {
        case \Org\Wechat\Wechat::MSGTYPE_TEXT:
            $this->weObj->text('hello order!')->reply();
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
                    switch($event['key']) {
                    case 'TASK_TASK':
                        $this->weObj->text('hello order!')->reply();
                        break;
                    }
                    break;
                case 'location':
                    break;
                default:
                }
            }
            break;
        case \Org\Wechat\Wechat::MSGTYPE_IMAGE:
            $this->weObj->text('hello order!')->reply();
            break;
        default:
            $this->weObj->text('hello order!')->reply();
        }
    }

    public function publish($content) {
        $message = array( "touser" => "@all",
            "safe" => "0",
            "agentid" => "9",
            "msgtype" => "text",
            "text" => array(
                "content" => $content,
        ));
        $this->weObj->sendMessage($message);
    }

    public function test($userId = 'ljl') {
        $o = D('Admin/Order')->relation(false)->find(2);
        $c = A('Order');
        $c->publish(json_encode($o));
    }
}
