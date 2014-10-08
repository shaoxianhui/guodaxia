<?php
namespace Admin\Controller;
use Think\Controller\HproseController;
class Api2Controller extends HproseController {
    public function test1($text) {
        $admin = D('Admin/Admin')->getAdmin($username, $password);
        if($admin !== false) {
            return true;
        } else {
            return false;
        }
    }
}
