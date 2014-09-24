<?php
namespace Home\Model;
use Think\Model;
class ProductUserModel extends Model {
    public function addRecord($openId, $productId) {
        $user = D('User')->getUser($openId);
        if($user !== null) {
            $data['userId'] = $user['id'];
            $data['productId'] = $productId;
            $data['ctime'] = time();
            $this->data($data)->add();
        }
    }

    public function getProductOfUser($openId) {
        $user = D('User')->getUser($openId);
        if($user !== null) {
            $where['userId'] = $user['id'];
            $product = $this->where($where)->order('ctime desc')->find();
            if($product !== null) {
                return D('Product')->getProduct($product['productId']);
            }
        }
        return null;
    }
}
