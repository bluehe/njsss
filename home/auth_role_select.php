<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '角色查询', 'nav1' => 'auth', 'nav2' => 'role');
need_login();
checkperm('auth');
$name = trim($_REQUEST['name']);
$description = trim($_REQUEST['description']);
$permission = $_REQUEST['permission'];
$by = $_REQUEST['by'] ? $_REQUEST['by'] : 'created_at';
$order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
$page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;

$condition = array('type' => 1);
if ($name) {
    $condition[] = "name LIKE '%{$name}%'";
}
if ($description) {
    $condition[] = "description LIKE '%{$description}%'";
}
if (!is_null($permission) && $permission !== '') {
    $condition['name'] = DB::GetDbColumn('auth_item_child', 'parent', array('child' => $permission));
}

$nums = DB::Count('auth_item', $condition);
$roles = DB::LimitQuery('auth_item', array('condition' => $condition, 'offset' => $INI['system']['page_num'] * $page, 'size' => $INI['system']['page_num'], 'order' => 'ORDER BY ' . $by . ' ' . $order,));

foreach ($roles as $key => $role) {
    $roles[$key]['permission'] = DB::GetDbColumn('auth_item_child', 'child', array('parent' => $role['name']));
}
$permissions = DB::GetDbColumn('auth_item', 'name', array('type' => 2), true);
include template('home/auth_role_select');
?>