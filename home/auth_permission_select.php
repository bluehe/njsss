<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '权限查询', 'nav1' => 'auth', 'nav2' => 'permission');
need_login();
checkperm('auth');
$name = trim($_REQUEST['name']);
$description = trim($_REQUEST['description']);
$by = $_REQUEST['by'] ? $_REQUEST['by'] : 'created_at';
$order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
$page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;

$condition = array('type' => 2);
if ($name) {
    $condition[] = "name LIKE '%{$name}%'";
}
if ($description) {
    $condition[] = "description LIKE '%{$description}%'";
}
$nums = DB::Count('auth_item', $condition);
$permissions = DB::LimitQuery('auth_item', array('condition' => $condition, 'offset' => $INI['system']['page_num'] * $page, 'size' => $INI['system']['page_num'], 'order' => 'ORDER BY ' . $by . ' ' . $order,));

include template('home/auth_permission_select');
?>