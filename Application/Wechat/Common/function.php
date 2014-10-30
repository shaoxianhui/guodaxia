<?php
function getWeChatImageUrl($image) {
    if($image === null || $image == '') return null;
    if(preg_match('/^http/i', $image)) {
        return $image; 
    }
    return 'http://www.meirixianguo.com/Public/Wechat/images/'.$image;
}


function getWeChatDetailUrl($id, $type) {
    return 'http://www.meirixianguo.com/index.php/Wechat/Index/detail/id/'.$id.'/type/'.$type;
}

function getDistance($latA, $lonA, $latB = 45.740271, $LonB = 126.624488) {
    $R = 6371.001;
    $t = sin($LatA) * sin($LatB) + cos($LatA) * cos($LatB) * cos($LonA - $LonB);
    $distance = $R * acos($t) * M_PI / 180;
    return $distance * 1000;
}
