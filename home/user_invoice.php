<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '开票信息', 'nav1' => 'user', 'nav2' => 'invoice');
need_login();

$invoice = DB::GetTableRow('user_invoice', array('uid' => $login_user_id));

include template('home/user_invoice');
?>