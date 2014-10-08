<?php
namespace Wechat\Model;
use Think\Model;
class NewsModel extends Model {
    public function getNews($list) {
        $news = array();
        $count = 0;
        foreach($list as $l) {
            $n = $this->where('id='.$l)->find();
            $des = explode('</br>', $n['description']);
            $news[$count] = array(
                'Title' => $n['title'],
                'Description'=> $des[0],
                'PicUrl'=> getWeChatImageUrl($n['picUrl']),
                'Url'=> $n['url'] == null ? getWeChatDetailUrl($n['id'], 'news') : $n['url']
            );
            $count = $count + 1;
        }
        return $news;
    }
}
