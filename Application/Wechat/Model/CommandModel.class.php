<?php
namespace Wechat\Model;
use Think\Model;
class CommandModel extends Model {
    public function executeAll($content, $callback) {
        $commands = S('commands');
        if($commands == null) {
            $commands = $this->select();
            S('commands', $commands, 86400);
        }
        foreach($commands as $c) {
            if(preg_match($c['regex'], $content)) {
                $callback->sendCustomMessage($c['toUserId']);
            }
        }
    }
}
