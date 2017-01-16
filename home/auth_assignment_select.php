<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '授权查询', 'nav1' => 'auth', 'nav2' => 'assignment');
need_login();
checkperm('auth');
$username = trim($_REQUEST['username']);
$role = $_REQUEST['role'];
$by = $_REQUEST['by'] ? $_REQUEST['by'] : 'user_id';
$order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
$page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;
$condition = array();

if ($username) {
    $condition[] = "username LIKE '%{$username}%'";
}
if (!is_null($role) && $role !== '') {
    $condition['user_id'] = DB::GetDbColumn('auth_assignment', 'user_id', array('item_name' => $role));
}

$nums = DB::Count('user', $condition);
$users = DB::LimitQuery('user', array('condition' => $condition,
            'offset' => $INI['system']['page_num'] * $page,
            'size' => $INI['system']['page_num'],
            'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'user_id' ? '' : ',user_id desc'),
        ));

foreach ($users as $key => $user) {
    $users[$key]['role'] = DB::GetDbColumn('auth_assignment', 'item_name', array('user_id' => $user['user_id']));
}

$roles = DB::GetDbColumn('auth_item', 'name', array('type' => 1), true);

include template('home/auth_assignment_select');
?>