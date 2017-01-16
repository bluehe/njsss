<?php

/**
 * @copyright
 * @author blue
 */
require_once(dirname(__FILE__) . '/system/confsystem.php');

/* process currefer */
//$currefer = uencode(strval($_SERVER['REQUEST_URI']));
/* session,cache,configure,webroot register */
Session::Init();

$INI = ZSystem::GetINI();


$AJAX = ('XMLHttpRequest' == @$_SERVER['HTTP_X_REQUESTED_WITH']);
if (false == $AJAX) {
    header('Content-Type: text/html; charset=UTF-8;');
} else {
    header("Cache-Control: no-store, no-cache, must-revalidate");
}
/* 终端类型 */
if ($_GET['agent']) {
    $agent = $_GET['agent'] == 'm' ? 'm' : 'pc';
    Session::Set('agent', $agent);
} elseif (!isset($_SESSION['agent'])) {
    $agent = isMobile() ? 'm' : 'pc';
    Session::Set('agent', $agent);
}

/* session */
if (isset($_SESSION['begtime']) && $INI['system']['logout_time']) {
    if ($_SESSION['begtime'] > (time() - 60 * $INI['system']['logout_time'])) {
        Session::Set('begtime', time());
    } else {
        Session::Logout();
    }
}
/* end */

/* biz logic */
$login_user_id = Session::Get('user_id');

//$login_user = Table::Fetch('user', $login_user_id, 'user_id');
$login_user = DB::GetTableRow('user', array('user_id' => $login_user_id));
if ($login_user_id) {
    $login_user['message_num'] = DB::Count('message', array('receive_uid' => $login_user_id, 'stat' => 1));
}
if (isset($INI['system']['site_stat'])) {
    if ($INI ['system'] ['site_stat'] == 0 && $_SERVER['PHP_SELF'] != WEB_ROOT . '/account/login.php' && $_SERVER['PHP_SELF'] != WEB_ROOT . '/account/dologin.php' && $_SERVER['PHP_SELF'] != WEB_ROOT . '/account/create_seccode.php' && $_SERVER['PHP_SELF'] != WEB_ROOT . '/account/dosec.php' && $login_user_id != 1) {
        if (isset($_SESSION['user_id'])) {
            Session::Logout();
            //ZLogin::NoRemember();
        }
        showmessage('info', '告示', '【<b>系统关闭</b>】' . $INI['system']['site_reason']);
        Utility::Redirect(WEB_ROOT . '/account/login');
    }
} else {
    showmessage('info', '告示', '【系统公告】系统数据库异常，请联系系统管理员');
}


if ($_SERVER ['SCRIPT_FILENAME'] == __FILE__) {
    Utility::Redirect(WEB_ROOT . '/');
}
/* end */
?>