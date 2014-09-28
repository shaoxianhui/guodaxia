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

    public function getProductNewsOfUser($openId) {
        $products = $this->getProductOfUser($openId);
        if($products !== null) {
            $product_news = array();
            $count = 0;
            foreach($products as $p) {
                $product_news[$count] = array(
                    'Title' => $p['id'].':'.$p['name'],
                    'Description'=> $p['description'],
                    'PicUrl'=> getWeChatImageUrl($p['picUrl']),
                    'Url'=> $p['url'] == null ? getWeChatDetailUrl($p['id'], 'product') : $p['url']
                );
                $count = $count + 1;
            }
            return $product_news;
        }
        return null;
    }
}
