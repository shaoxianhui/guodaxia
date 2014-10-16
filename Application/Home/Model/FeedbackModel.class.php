<?php
namespace Home\Model;
use Think\Model;
class FeedbackModel extends Model {

    public function addFeedback($name, $email, $phone, $content) {
        $data['name'] = $name;
        $data['email'] = $email;
        $data['phone'] = $phone;
        $data['content'] = $content;
        $data['ctime'] = time();
        return $this->data($data)->add();
    }

    public function getCount() {
        return $this->count();
    }

    public function getFeedback($start = 0, $length = 10, $order = 'ctime asc') {
        $page = $start / $length + 1;
        $feedback = $this->order($order)->page($page.','.$length)->select();
        if($feedback === null)
            return array();
        return $feedback;
        
    }
}
