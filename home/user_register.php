<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '注册信息', 'nav1' => 'user', 'nav2' => 'register');
need_login();
include template('home/user_register');
?>