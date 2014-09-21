<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index()
    {
        if(D('AdminManager')->isLogged())
        {
            $this->display();
        }
        else
        {
            $this->redirect('Admin/Index/signin');
        }
    }

    public function doLogin()
    {
        if(D('AdminManager')->login($_POST['adminName'], $_POST['password'], $_POST['remember']))
        {
            $this->success('登陆成功', 'index', 3);
        }
        else
        {
            $this->error('登陆失败', 'signin', 3);
        }
    }
}
