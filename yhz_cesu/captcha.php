<?php
/**
 * Created by PhpStorm.
 * User: a12345678
 * Date: 2018/7/25
 * Time: 上午10:16
 */
session_start();

$session_captcha = '';

$image = imagecreatetruecolor( 100, 30 );

$bgColor = imageColorallocate($image, 255, 255, 255);

imagefill( $image, 0, 0, $bgColor);

for ($i=0; $i < 4; $i++) {
    $fontSize = 6;
    $fontColor = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));
    $data = "qwertyuiopasdfghjklzxcvbnm1234567890";

    $x = (100 * $i / 4) + rand(5, 10) ;
    $y = rand(5,10);
    $fontContent = substr($data, rand(0, strlen($data) - 1), 1);
    $session_captcha .= $fontContent;

    imagestring($image, $fontSize, $x, $y, $fontContent, $fontColor);
}

$_SESSION['captcha'] = $session_captcha;

for ($i = 0; $i < 200; $i++) {
    $color = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
    imagesetpixel($image, rand(1, 99), rand(1, 29), $color);
}

for ($i = 0; $i < 3; $i++) {
    $color = imagecolorallocate($image, rand(80, 220),rand(80, 220),rand(80, 220));
    imageline($image, rand(0, 100), rand(5, 25), rand(0, 100), rand(5, 25), $color);
}


header('content-type: image/png');

imagepng( $image );

imagedestroy( $image );

?>