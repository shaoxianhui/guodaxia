<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class SmsController extends Controller {
    private $weObj;
    public function __construct()
    {
        parent::__construct();
        $options = array(
            'token'=>'meirixianguo',
            'appid'=>'wx0f3313fd2a9eed6e',
            'appsecret'=>'5WpAyyPu4RtWtZ6qufgnBuaAGvZJ26wwgJuy0k7fYAaYDyR8X0UHEabkPw-H0ugV',
 			'encodingaeskey'=>'dkPjxDa2pNi9xYa1dbakWn7kMxXajQxF9rDteEevP83',
 			'agentid'=>'12',
        );
        $this->weObj = new \Org\Wechat\QyWechat($options);
    }

    public function index() {
        $this->weObj->valid();
        $type = $this->weObj->getRev()->getRevType();
        switch($type) {
        case \Org\Wechat\Wechat::MSGTYPE_TEXT:
            $this->weObj->text('hello sms!')->reply();
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
                    case 'SMS_QP10':
                        $result = @\Common\Library\Weimi\WeimiSMS::sendNotifySMS(2);
                        $this->weObj->text($result)->reply();
                        break;
                    case 'SMS_QP11':
                        $result = @\Common\Library\Weimi\WeimiSMS::sendNotifySMS(1);
                        $this->weObj->text($result)->reply();
                        break;
                    case 'SMS_AQY':
                        $result = @\Common\Library\Weimi\WeimiSMS::sendNotifySMS(3);
                        $this->weObj->text($result)->reply();
                        break;
                    case 'SMS_QP6':
                        $result = @\Common\Library\Weimi\WeimiSMS::sendNotifySMS(4);
                        $this->weObj->text($result)->reply();
                        break;
                    case 'SMS_JS450':
                        $result = @\Common\Library\Weimi\WeimiSMS::sendNotifySMS(5);
                        $this->weObj->text($result)->reply();
                        break;
                    case 'SMS_HY':
                        $result = @\Common\Library\Weimi\WeimiSMS::sendNotifySMS(6);
                        $this->weObj->text($result)->reply();
                        break;
                    case 'SMS_SZSM10':
                        $result = @\Common\Library\Weimi\WeimiSMS::sendNotifySMS(7);
                        $this->weObj->text($result)->reply();
                        break;
                    case 'SMS_SZSM16':
                        $result = @\Common\Library\Weimi\WeimiSMS::sendNotifySMS(8);
                        $this->weObj->text($result)->reply();
                        break;
                    case 'SMS_YX':
                        $this->weObj->text('hello YX!')->reply();
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
            $this->weObj->text('hello sms!')->reply();
            break;
        default:
            $this->weObj->text('hello sms!')->reply();
        }
    }

    public function publish($content) {
        $message = array( "touser" => "@all",
            "safe" => "0",
            "agentid" => "10",
            "msgtype" => "text",
            "text" => array(
                "content" => $content,
        ));
        $this->weObj->sendMessage($message);
    }

    public function test($userId = 'ljl') {
    }
}
