<?php
namespace Org\Util;
class Cart {
	private $product;
	public function __construct($openId) {
        $this->product = D('Product');
        if(!session('?openId')) {
            session('openId', $openId);
			session('cart', array('products_list'=>array(), 'total_num'=>0, 'total_price'=>0.00));
        }
	}

	public function add_product($product_id) {
        $p = $this->product->find($product_id);
        if($p !== null) {
            $cart = session('cart');
            if(!$cart['products_list'][$p['id']]) {
                $p['count'] = 1;
            } else {
                $p['count'] = $cart['products_list'][$p['id']]['count'] + 1;
            }
            $cart['products_list'][$p['id']] = $p;
            $cart['total_price'] = $cart['total_price'] + $p['price'];
            $cart['total_num'] = $cart['total_num'] + 1;
            session('cart', $cart);
            return array('state_code' => 0,'state_message' => '加入购物车成功!', 'total_price' => $cart['total_price']);
        } else {
            return array('state_code' => 1,'state_message' => '商品已下架!');
        }
    }

	public function dec_product($product_id) {
        $p = $this->product->find($product_id);
        if($p !== null) {
            $cart = session('cart');
            if($cart['products_list'][$p['id']] && $cart['products_list'][$p['id']]['count'] > 1) {
                $p['count'] = $cart['products_list'][$p['id']]['count'] - 1;
                $cart['products_list'][$p['id']] = $p;
                $cart['total_price'] = $cart['total_price'] - $p['price'];
                $cart['total_num'] = $cart['total_num'] - 1;
                session('cart', $cart);
                return array('state_code' => 0,'state_message' => '减少商品数量成功!');
            } else {
                return array('state_code' => 0,'state_message' => '在购物车中没有找到该商品或商品数量已经为1!');
            }
        } else {
            return array('state_code' => 1,'state_message' => '商品已下架!');
        }
    }

	public function delete_product($product_id) {
        $p = $this->product->find($product_id);
        if($p !== null) {
            $cart = session('cart');
            if($cart['products_list'][$p['id']]) {
				$cart['total_price'] = $cart['total_price'] - $cart['products_list'][$p['id']]['count'] * $p['price'];
				$cart['total_num'] = $cart['total_num'] - $cart['products_list'][$p['id']]['count'];
				unset($cart['products_list'][$product_id]);
                session('cart', $cart);
				return array('state_code' => 0,'state_message' => '商品删除成功!');
			} else {
				return array('state_code' => 1,'state_message' => '在购物车中没有找到该商品!');
			}
		} else {
			return array('state_code' => 1, 'state_message' => '商品已下架!');
		}
	}

	public function empty_cart() {
		session('cart', null);
		session('openId', null);
	}
	
	public function get_cart_info() {
		return session('cart');
	}

    public function isAtCart($product_id) {
        $cart = session('cart');
        return isset($cart['products_list'][$product_id]);
    }
}
