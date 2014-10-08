<?php
namespace Wechat\Model;
use Think\Model\RelationModel;
class GroupModel extends RelationModel {

    protected $_link = array(
        'Product' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'Product',
            'foreign_key' => 'groupId',
            'relation_foreign_key' => 'productId',
            'mapping_name' => 'products',
            'relation_table' => 'gdx_product_group'
        ),
        'Prize' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'Prize',
            'foreign_key' => 'groupId',
            'relation_foreign_key' => 'prizeId',
            'mapping_name' => 'prizes',
            'relation_table' => 'gdx_prize_group'
        )
    );

}
