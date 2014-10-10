<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function feedback($name, $email, $phone, $content) {
        D('Feedback')->addFeedback($name, $email, $phone, $content);
        $this->success('感谢您的支持！', U('Home/Index/index'));
    }
}
