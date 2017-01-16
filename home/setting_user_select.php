<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '用户查询', 'nav1' => 'setting', 'nav2' => 'user');
need_login();
checkperm('user');
$username = trim($_REQUEST['username']);
$email = trim($_REQUEST['email']);
$tel = trim($_REQUEST['tel']);
$stat = $_REQUEST['stat'];
$by = $_REQUEST['by'] ? $_REQUEST['by'] : 'user_id';
$order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
$page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;
$condition = array();

if ($username) {
    $condition[] = "username LIKE '%{$username}%'";
}
if ($email) {
    $condition[] = "email LIKE '%{$email}%'";
}
if ($tel) {
    $condition[] = "tel LIKE '%{$tel}%'";
}
if (!is_null($stat) && $stat !== '') {
    $condition['stat'] = $stat;
}

$nums = DB::Count('user', $condition);
$users = DB::LimitQuery('user', array('condition' => $condition,
            'offset' => $INI['system']['page_num'] * $page,
            'size' => $INI['system']['page_num'],
            'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'user_id' ? '' : ',user_id desc'),
        ));

include template('home/setting_user_select');
?>