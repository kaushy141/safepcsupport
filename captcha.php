<?php
try {
    include("setup.php");
    //ini_set('display_errors', 1);
} catch (Exception $e) {
    echo "" . $e->getMessage() . "";
}
$mode = "DEFAULT";
if (isset($_REQUEST['mode']) && !is_array($_REQUEST['mode']) && trim($_REQUEST['mode']) != "")
    $mode = trim($_REQUEST['mode']);
$captcha_num = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
$captcha_num = substr(str_shuffle($captcha_num), 0, 4);
$_SESSION["CAPTCHA"][$mode] = $captcha_num;
$im = imagecreatetruecolor(65, 34);
$bg = imagecolorallocate($im, 0, 0, 0); //background color blue
$fg = imagecolorallocate($im, 255, 255, 255); //text color white
// imagefill($im, 0, 0, $bg);
// imagestring($im, 5, 15, 10,  $captcha_num, $fg);
// header("Cache-Control: no-cache, must-revalidate");
// header('Content-type: image/png');
// imagepng($im);
// imagedestroy($im);
