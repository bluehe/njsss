<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '修改密码', 'nav1' => 'user', 'nav2' => 'password');
need_login();
include template('home/user_password');
?>