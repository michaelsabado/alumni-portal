<?php
session_start();

$text = "";
$possible = "23456789bcdfghjkmnpqrstvwxyz";
$i = 0;

while ($i < 5) {
  $text .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
  $i++;
}

$_SESSION['captcha_text'] = $text;

$width = 120;
$height = 40;

$image = imagecreate($width, $height);

$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);

imagestring($image, 5, 30, 10, $text, $black);

header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
