<?php
namespace Common\Library\PhpQrCode;
include "phpqrcode.php";
class TQrCode {
    public static function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint=false) {
        QRcode::png($text, $outfile, $level, $size, $margin, $saveandprint);
    }
}
