<?php
namespace Wechat\Model;
use Think\Model\RelationModel;
class UserModel extends RelationModel {

    protected $_link = array(
        'Product' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'Product',
            'foreign_key' => 'userId',
            'relation_foreign_key' => 'ProductId',
            'mapping_name' => 'products',
            'relation_table' => 'gdx_product_user'
        ),
        'Prize' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'Prize',
            'foreign_key' => 'userId',
            'relation_foreign_key' => 'PrizeId',
            'mapping_name' => 'prizes',
            'relation_table' => 'gdx_prize_user'
        )
    );

    public function getUser($openId) {
        $where['openId'] = $openId;
        return $this->where($where)->find();
    }

    public function getUsers($start = 0, $length = 10, $order = 'id asc') {
        $page = $start / $length + 1;
        return $this->order($order)->page($page.','.$length)->select();
    }

    public function getCount() {
        return $this->count();
    }

    public function addUser($openId, $nickname, $qrScene) {
        $add_or_update = true;
        $user_data['openId'] = $openId;
        $user = $this->where($user_data)->find();
        if($user !== null) {
            $user['subscribe'] = 1;
            $user['nickname'] = $nickname;
            if($qrScene !== false && is_numeric($qrScene)) {
                $user['qrScene'] = $qrScene;
            } else {
                $user['qrScene'] = 0;
            }
            $this->save($user);
            $add_or_update = false;
        } else {
            $user_data['subscribe'] = 1;
            $user_data['nickname'] = $nickname;
            if($qrScene !== false && is_numeric($qrScene)) {
                $user_data['qrScene'] = $qrScene;
            } else {
                $user['qrScene'] = 0;
            }
            $this->data($user_data)->add();
        }
        return $add_or_update;
    }

    public function changeSceneOfUser($openId, $scene)
    {
        if($scene >= 100)
        {
            $user = $this->getUser($openId);
            if($user['qrScene'] !== $scene)
            {
                $user['qrScene'] = $scene;
                $this->save($user);
            }
        }
    }
}
