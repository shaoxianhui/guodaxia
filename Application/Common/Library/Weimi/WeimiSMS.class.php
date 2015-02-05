<?php
namespace Common\Library\Weimi;

class WeimiSMS{

    public static $uid = '98FxFFr5hG1null';
    public static $uidpass = '4cbjqbw4';
    /*
    短信接口一，自写短信内容。该接口提交的短信均由人工审核，下发后请联系在线客服。适合：节假日祝福、会员营销群发等。
    1、设定微米账号的接口UID和接口密码
    2、传入目标手机号码，多个以“,”分隔，一次性调用最多100个号码，示例：139********,138********
    3、传入短信内容。必须设置好短信签名，签名规范：
        1）短信内容一定要带签名，签名放在短信内容的最前面；
        2）签名格式：【***】，签名内容为三个汉字以上（包括三个）；
        3）短信内容不允许双签名，即短信内容里只有一个“【】”
    */
    public static function sendSMS($phones, $content) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api.weimi.cc/2/sms/send.html");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'uid='.self::$uid.'&pas='.self::$uidpass."&mob=$phones&con=".urlencode($content).'&type=json');
        $res = curl_exec( $ch );
        curl_close( $ch );
        echo($res);
    }

    /*
    短信接口二，触发类模板短信接口，可以设置动态参数变量。适合：验证码、订单短信等。
    1、设定微米账号的接口UID和接口密码
    2、传入目标手机号码，多个以“,”分隔，一次性调用最多100个号码，示例：139********,138********
    3、短信模板cid，通过微米后台创建，由在线客服审核。必须设置好短信签名，签名规范：
        1）、模板内容一定要带签名，签名放在模板内容的最前面；
        2）、签名格式：【***】，签名内容为三个汉字以上（包括三个）；
        3）、短信内容不允许双签名，即短信内容里只有一个“【】”。
    4、传入模板参数。短信模板内容示例：
        【微米】您的验证码是：%P%，%P%分钟内有效。如非您本人操作，可忽略本消息。
        传入两个参数：
        p1：610912
        p2：3
        最终发送内容：
        【微米】您的验证码是：610912，3分钟内有效。如非您本人操作，可忽略本消息。
    */
    public static function sendTemplateSMS($phone, $cid, $ps) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api.weimi.cc/2/sms/send.html");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        $ps_str = '';
        foreach($ps as $key => $value) {
            $ps_str .= "&$key=$value"; 

        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'uid='.self::$uid.'&pas='.self::$uidpass."&mob=$phones&cid=$cid$ps_str&type=json");
        $res = curl_exec( $ch );
        curl_close( $ch );
        echo($res);
    }

    public static function balance() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api.weimi.cc/2/account/balance.html");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'uid='.self::$uid.'&pas='.self::$uidpass.'&type=json');
        $res = curl_exec( $ch );
        curl_close( $ch );
        echo($res);
    }

}
