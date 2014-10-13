<?php
function getWeChatImageUrl($image)
{
    if($image === null || $image == '')
        return null;
    if(preg_match('/^http/i', $image))
    {
        return $image; 
    }
    return 'http://www.meirixianguo.com/Public/Wechat/images/'.$image;
}


function getWeChatDetailUrl($id, $type)
{
    return 'http://www.meirixianguo.com/index.php/Wechat/Index/detail/id/'.$id.'/type/'.$type;
}
