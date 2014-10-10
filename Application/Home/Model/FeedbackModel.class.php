<?php
namespace Home\Model;
use Think\Model;
class FeedbackModel extends Model {

    public function addFeedback($name, $email, $phone, $content) {
        $data['name'] = $name;
        $data['email'] = $email;
        $data['phone'] = $phone;
        $data['content'] = $content;
        return $this->data($data)->add();
    }
}
