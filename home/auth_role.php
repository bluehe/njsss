<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '角色编辑', 'nav1' => 'auth', 'nav2' => 'role');
need_login();
checkperm('auth');
$id = $_REQUEST['id'];

//角色信息
$role = DB::GetTableRow('auth_item', array('name' => $id, 'type' => 1));
//权限列表
$permissions = DB::GetDbColumn('auth_item', array('name', 'description'), array('type' => 2), true);

//已经获得权限
$role_per = DB::GetDbColumn('auth_item_child', 'child', array('parent' => $role['name']));

include template('home/auth_role');
?>