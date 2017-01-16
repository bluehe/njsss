<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '权限编辑', 'nav1' => 'auth', 'nav2' => 'permission');
need_login();
checkperm('auth');
$id = $_REQUEST['id'];

$permission = DB::GetTableRow('auth_item', array('name' => $id, 'type' => 2));

include template('home/auth_permission');
?>