<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '退出');
if (isset($_SESSION['user_id'])) {
    Session::Logout();
    Session::Get('login_url', TRUE);
    //ZLogin::NoRemember();
    //showmessage('right', '【退出成功】', '', true, 3, WEB_ROOT.'/');
    Utility::Redirect(WEB_ROOT . '/');
}
$seccodestatus = explode(',', $INI['system']['seccode_status']);
if (is_login()) {
    Utility::Redirect(WEB_ROOT . '/');
}
$username = ZUser::GetNameCookie();
include template('account/login');
