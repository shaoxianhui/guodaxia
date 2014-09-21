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
}
