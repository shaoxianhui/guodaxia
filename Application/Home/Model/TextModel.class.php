<?php
namespace Home\Model;
use Think\Model;
class TextModel extends Model {
    public function getText($id) {
        $text = $this->where('id='.$id)->find();
        if($text == true) {
            return $text['message'];
        }
    }
}
