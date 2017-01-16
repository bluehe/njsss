<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '用户编辑', 'nav1' => 'setting', 'nav2' => 'user');
need_login();
checkperm('user');
$uid = $_REQUEST['id'];
$user = DB::GetTableRow('user', array('user_id' => $uid));
if (!$user) {
    //不存在
    showmessage('error', '', '此信息已经失效！');
    Utility::Redirect();
}
include template('home/setting_user');
?>