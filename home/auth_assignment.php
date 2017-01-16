<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '授权管理', 'nav1' => 'auth', 'nav2' => 'assignment');
need_login();
checkperm('auth');
$id = $_REQUEST['id'];

$user = DB::GetTableRow('user', array('user_id' => $id));
if ($user) {
    //已经授权角色
    $assignment = DB::GetDbColumn('auth_assignment', 'item_name', array('user_id' => $id));
    //角色列表
    $roles = DB::GetDbColumn('auth_item', array('name', 'description'), array('type' => 1), true);
} else {
    //不存在
    showmessage('error', '', '此信息已经失效！');
    Utility::Redirect();
}

include template('home/auth_assignment');
?>