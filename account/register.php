<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '注册');
if (is_login()) {
    Utility::Redirect(WEB_ROOT . '/');
}
if (isset($INI['system']['register']) && $INI['system']['register'] != 1) {
    showmessage('error', '提示', '目前系统禁止用户注册，您可以联系系统管理员进行注册!');
    echo '<script language="javascript">history.go(-1);</script>';
    exit;
}
$seccodestatus = explode(',', $INI['system']['seccode_status']);

include template('account/register');
