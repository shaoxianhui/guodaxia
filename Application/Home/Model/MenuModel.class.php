<?php
namespace Home\Model;
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
                    $sub_menus_array[$c2] = array('type' => $sm['type'], 'name' => $sm['name'], 'key' => $sm['key']);
                    $c2 = $c2 + 1;
                }
                $menus_array[$c1] = array('name' => $tm['name'], 'sub_button' => $sub_menus_array);
            }
            else {
                $menus_array[$c1] = array('type' => $tm['type'], 'name' => $tm['name'], 'key' => $tm['key']);
            }
            $c1 = $c1 + 1;
        }
        $menus = array('button' => $menus_array);
        return $menus;
    }
}
