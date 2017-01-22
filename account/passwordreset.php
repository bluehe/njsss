<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '密码重置');
if (is_login()) {
    Utility::Redirect(WEB_ROOT . '/');
}
$seccodestatus = explode(',', $INI['system']['seccode_status']);

$c = $_REQUEST['c'];
if ($c != '') {

    $user = DB::GetTableRow('user', array('recode' => $c));
    if ($user) {
        include template('account/passwordresetinfo');
    } else {
        showmessage('error', '', '链接已经失效，请重新进行密码重置！');
        Utility::Redirect(WEB_ROOT . '/account/passwordreset');
    }
} else {
    include template('account/passwordreset');
}