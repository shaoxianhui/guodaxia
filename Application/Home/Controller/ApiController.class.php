<?php
namespace Home\Controller;
use Think\Controller\RpcController;
class ApiController extends RpcController {
    public function login($username, $password) {
        return D('Admin/Admin')->getAdmin($username, $password);
    }
}
