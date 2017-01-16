<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '版本查询', 'nav1' => 'setting', 'nav2' => 'version');
need_login();
checkperm('version');
$old_version = trim($_REQUEST['old_version']);
$new_version = trim($_REQUEST['new_version']);
$auto = $_REQUEST['auto'];
$stat = $_REQUEST['stat'];
$by = $_REQUEST['by'] ? $_REQUEST['by'] : 'id';
$order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
$page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;
$condition = array();

if ($old_version) {
    $condition[] = "old_version LIKE '%{$old_version}%'";
}
if ($new_version) {
    $condition[] = "new_version LIKE '%{$new_version}%'";
}
if (!is_null($auto) && $auto !== '') {
    $condition['auto'] = $auto;
}

if (!is_null($stat) && $stat !== '') {
    $condition['stat'] = $stat;
}

$nums = DB::Count('version', $condition);
$versions = DB::LimitQuery('version', array('condition' => $condition,
            'offset' => $INI['system']['page_num'] * $page,
            'size' => $INI['system']['page_num'],
            'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'id' ? '' : ',id desc'),
        ));

include template('home/setting_version_select');
?>