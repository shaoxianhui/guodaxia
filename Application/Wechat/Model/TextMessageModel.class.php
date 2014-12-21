<?php
namespace Wechat\Model;
use Think\Model;
class TextMessageModel extends Model {
    public function addTextMessage($openId, $content, $ctime) {
        $data['openId'] = $openId;
        $data['content'] = $content;
        $data['ctime'] = $ctime;
        $this->data($data)->add();
    }
}
