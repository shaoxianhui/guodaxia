<?php
namespace Wechat\Model;
use Think\Model;
class MenuModel extends Model {
    public function getMenus() {
        $menus_array = array();
        $top_menus = $this->where('upperId is null')->order('sort')->select();
        $c1 = 0;
        foreach($top_menus as $tm) {
            if($tm['key'] == null) {
                $sub_menus_array = array();
                $sub_menus = $this->where('upperId='.$tm['id'])->order('sort')->select();
                $c2 = 0;
                foreach($sub_menus as $sm) {
                    if($sm['type'] == 'click') {
                        $sub_menus_array[$c2] = array('type' => $sm['type'], 'name' => $sm['name'], 'key' => $sm['key']);
                    } elseif ($sm['type'] == 'view') {
                        $sub_menus_array[$c2] = array('type' => $sm['type'], 'name' => $sm['name'], 'url' => $sm['url']);
                    }
                    $c2 = $c2 + 1;
                }
                $menus_array[$c1] = array('name' => $tm['name'], 'sub_button' => $sub_menus_array);
            }
            else {
                if($tm['type'] == 'click') {
                    $menus_array[$c1] = array('type' => $tm['type'], 'name' => $tm['name'], 'key' => $tm['key']);
                } elseif ($tm['type'] == 'view') {
                    $menus_array[$c1] = array('type' => $tm['type'], 'name' => $tm['name'], 'url' => $tm['url']);
                }
            }
            $c1 = $c1 + 1;
        }
        $menus = array('button' => $menus_array);
        return $menus;
    }
}
