<?php
namespace Common\Library\PhpQrCode;
include "phpqrcode.php";
class TQrCode {
    public static function png($text) {
        QRcode::png($text, false, 'H', 20);
    }
}
