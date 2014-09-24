<?php
namespace Home\Model;
use Think\Model;
class ProductModel extends Model {
    public function getProduct($id) {
        return $this->where('id='.$id)->find();
    }

    public function getProductOfUser($openId) {
        $productGroup = D('ProductGroup')->getProductGroupOfUser($openId);
        if($productGroup !== null) {
            $products = array();
            $count = 0;
            foreach($productGroup as $p) {
                $products[$count] = $this->where('id='.$p['productId'])->find();
                $count = $count + 1;
            }
            return $products;
        }
        return null;
    }
}
