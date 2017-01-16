<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '公告查询', 'nav1' => 'setting', 'nav2' => 'notice');
need_login();
checkperm('notice');
$title = trim($_REQUEST['title']);
$recode = $_REQUEST['recode'];
$start_time = $_REQUEST['start_time'];
$end_time = $_REQUEST['end_time'];
$by = $_REQUEST['by'] ? $_REQUEST['by'] : 'send_time';
$order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
$page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;
$condition = array('type' => 'system', 'send_uid' => $login_user_id);

if ($title) {
    $condition[] = "title LIKE '%{$title}%'";
}
if ($recode) {
    $condition[] = "FIND_IN_SET('$recode',recode)";
}
if ($start_time) {
    $start = strtotime($start_time);
    $condition[] = "send_time > $start";
}
if ($end_time) {
    $end = strtotime($end_time) + 86399;
    $condition[] = "send_time < $end";
}
$nums = count(DB::LimitQuery('message', array('select' => 'id', 'condition' => $condition, 'group' => "group by send_time,recode")));

$messages = DB::LimitQuery('message', array('condition' => $condition,
            'offset' => $INI['system']['page_num'] * $page,
            'size' => $INI['system']['page_num'],
            'group' => "group by send_time,recode",
            'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'send_time' ? '' : ',send_time desc'),
        ));

include template('home/setting_notice_select');
?>