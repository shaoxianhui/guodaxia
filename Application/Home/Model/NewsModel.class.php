<?php
namespace Home\Model;
use Think\Model;
class NewsModel extends Model {
    public function getNews($list) {
        $news = array();
        $count = 0;
        foreach($list as $l) {
            $n = $this->where('id='.$l)->find();
            $news[$count] = array(
                'Title' => $n['title'],
                'Description'=> $n['description'],
                'PicUrl'=> getWeChatImageUrl($n['picUrl']),
                'Url'=> $n['url'] == null ? getWeChatDetailUrl($n['id'], 'news') : $n['url']
            );
            $count = $count + 1;
        }
        return $news;
    }
}
