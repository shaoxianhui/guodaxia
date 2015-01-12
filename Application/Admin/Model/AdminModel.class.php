<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model {
    /**
     * 根据标示符(adminname或uid)和未加密的密码获取本地用户 (密码为null时不参与验证)
     * 
     * @param string         $identifier 标示符内容 (为数字时:标示符类型为uid, 其他:标示符类型为adminname)
     * @param string|boolean $password   未加密的密码
     * @return array|boolean 成功获取用户数据时返回用户信息数组, 否则返回false
     */
    function getAdmin($identifier, $password = null) {
        if (empty($identifier))
            return false;
        
        $identifier_type = is_numeric($identifier) ? 'id' : 'name';
        if ($identifier_type == 'id')
            $map[$identifier_type] = intval($identifier);
        else
            $map[$identifier_type] = $identifier;
        $admin = $this->where($map)->find();
        if (!$admin)
            return false;
        else if ($password && md5($password) != $admin['password'])
            return false;
        else
            return $admin;
    }

    /**
     * 登陆
     * 
     * @param string $adminname
     * @param string $password
     * @param bool $remember
     * @return admin or boolean
     */
    function login($adminname, $password = null, $remember = false){
        $admin = $this->getAdmin($adminname, $password);
        if($admin)
        {
            //注册session
            $_SESSION['aid'] = $admin['id'];
            $_SESSION['adminname'] = $admin['adminname'];

            if (!$this->getCookieAid()) {
                $expire = $remember ? (3600*24*365) : (3600*1);
                cookie('LOGGED_ADMIN', base64_encode("gdx.".$admin['id']), $expire);
            }
            return $admin;
        }
        return false;
    }

    /**
     * 使用cookie登陆
     * 
     * @param string $cookieId
     * @return boolean
     */
     function cookieLogin($cookieId){
        $cookieId = explode( '.', base64_decode($cookieId));
        if ($cookieId[0] !== 'gdx') {
            return false;
        }else {
            return $this->login(intval($cookieId[1]));
        }
    }

    /**
     * 验证用户是否已登录
     * @return boolean
     */
     function isLogged(){
        $_cookie_admin		=	cookie('LOGGED_ADMIN');
        $_session_admin_id	=	intval($_SESSION['aid']);
        
        // 验证本地系统登录
        if($_session_admin_id){
            return true;
        }elseif($_cookie_admin){
            return $this->cookieLogin($_cookie_admin);
        }else{
            return false;
        }
    }

	/**
	 * 获取cookie中记录的用户ID
	 */
	function getCookieAid()
	{
		static $cookie_aid = null;
		if (isset($cookie_aid))
			return $cookie_aid;
			
		$cookie = cookie('LOGGED_ADMIN');
		$cookie = explode('.', base64_decode($cookie));
		$cookie_aid = ($cookie[0] !== 'beijing') ? false : $cookie[1];
		return $cookie_aid;
	}

    /**
     * 注销本地登录
     * @return void
     */
     function logout() {
        //注销session
        unset($_SESSION['aid']);

        //注销cookie
        cookie('LOGGED_ADMIN',NULL);
    }
}
