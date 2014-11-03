<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class CheckinController extends Controller {
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
            $this->weObj->text('hello checkin!')->reply();
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
                    case 'CHECKIN_CHECKIN':
                        $currentTime = time();
                        $currentDate = getdate($currentTime);
                        if($currentDate['hours'] != 8) {
                            $this->weObj->text('请在每天的八点签到！！')->reply();
                        }
                        $userId = $this->weObj->getRevFrom();
                        $checkin = D('Checkin')->getCheckin($userId);
                        if($checkin !== null) {
                            $checkinDate = getdate($checkin['ctime']);
                            if($checkinDate['year'] == $currentDate['year'] && 
                                $checkinDate['mon'] == $currentDate['mon'] && 
                                $checkinDate['mday'] == $currentDate['mday']) {
                                    $this->weObj->text('今日签到已成功！！')->reply();
                                }
                        }
                        $location = D('EmployeeLocation')->getLocation($userId);
                        if($location !== null) {
                            if(abs($currentTime - $location['ctime']) < 20) {
                                $ls = D('CheckinConfig')->getLocations($userId);
                                foreach($ls as $l) {
                                    if(getDistance($location['x'], $location['y'], $l['latitude'], $l['longitude']) < 100) {
                                        D('Checkin')->addCheckin($userId, $l['id']);
                                        $this->weObj->text('签到成功！！')->reply();
                                    }
                                }
                            }
                        }
                        $this->weObj->text('签到失败！！')->reply();
                        break;
                    }
                    break;
                case 'location':
                    $location = $this->weObj->getRevEventGeo();
                    if($location !== false) {
                        D('EmployeeLocation')->addLocation($this->weObj->getRevFrom(), $location);
                    }
                    break;
                default:
                }
            }
            break;
        case \Org\Wechat\Wechat::MSGTYPE_IMAGE:
            $this->weObj->text('hello checkin!')->reply();
            break;
        default:
            $this->weObj->text('hello checkin!')->reply();
        }
    }

    public function broadcast() {
        $message = array( "touser" => "@all",
            "safe" => "0",
            "agentid" => "1",
            "msgtype" => "text",
            "text" => array(
                "content" => "你好，不要忘记签到哦！",
        ));
        $this->weObj->sendMessage($message);
    }

    public function test($userId = 'ljl') {
    }
}
