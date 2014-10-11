<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index()
    {
        if(D('AdminManager')->isLogged())
        {
            $this->redirect('Admin/Index/dashboard');
        }
        else
        {
            $this->redirect('Admin/Index/login');
        }
    }

    public function doLogin($adminName, $password, $remember = false)
    {
        if(D('AdminManager')->login($adminName, $password, $remember))
        {
            $this->success('登陆成功', 'dashboard');
        }
        else
        {
            $this->error('登陆失败', 'login', 3);
        }
    }
}
