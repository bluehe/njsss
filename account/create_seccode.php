<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');

$length = $INI['system']['seccode_length'];
$vcode = new Vcode();
$vcode->setLength($length);
$vcode->setBgColor('#3c8dbc');
$vcode->setFontColor('#ffffff');
$vcode->setDotNoise(0);
$vcode->setLineNoise(0);
$_SESSION['seccode'] = $vcode->paint(); // To be encrypted by MD5
?>