<?php include("setup.php"); ?>
<?php
$_SESSION['QR_LOGIN_SECREAT'] = strtoupper(md5(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_-+={}[]'),0,32)));
$text = strtoupper(md5($_SERVER['REMOTE_ADDR']))."-".strtoupper(md5($_SERVER['SERVER_SIGNATURE']))."-".session_id()."-".$_SESSION['QR_LOGIN_SECREAT'];

QRcode::png($text, false, $level = QR_ECLEVEL_H, 5, $margin = 2, false);
?>