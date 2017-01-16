<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '服务器查询', 'nav1' => 'setting', 'nav2' => 'service');
need_login();
checkperm('service');
$ip = trim($_REQUEST['ip']);
$stat = $_REQUEST['stat'];
$by = $_REQUEST['by'] ? $_REQUEST['by'] : 'id';
$order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
$page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;
$condition = array();

if ($ip) {
    $condition[] = "ip LIKE '%{$ip}%'";
}

if (!is_null($stat) && $stat !== '') {
    $condition['stat'] = $stat;
}

$nums = DB::Count('service', $condition);
$services = DB::LimitQuery('service', array('condition' => $condition,
            'offset' => $INI['system']['page_num'] * $page,
            'size' => $INI['system']['page_num'],
            'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'id' ? '' : ',id desc'),
        ));

include template('home/setting_service_select');
?>