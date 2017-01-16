<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_login();

$nav = array('title' => '公告消息', 'nav1' => 'user', 'nav2' => 'message');

include template('home/user_message');
