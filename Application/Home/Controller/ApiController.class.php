<?php
namespace Home\Controller;
use Think\Controller\RpcController;
class ApiController extends RpcController {
    public function login($username, $password) {
        $admin = D('Admin/Admin')->getAdmin($username, $password);
        if($admin !== false) {
            return true;
        } else {
            return false;
        }
    }
}
