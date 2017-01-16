<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '登录');
$seccodestatus = explode(',', $INI['system']['seccode_status']);
if (is_login()) {
    Utility::Redirect(WEB_ROOT . '/');
}
$username = ZUser::GetNameCookie();
include template('account/login');
