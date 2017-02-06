<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();
checkperm('fee_list');
$nav = array('title' => '部门费用', 'nav1' => 'work', 'nav2' => 'depart_fee');
//获得楼苑信息
$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court', 'stat' => 1), 'order' => "order by displayorder asc,id asc"));
foreach ($courts as $key => $court) {
    $courts[$key]['forum'] = DB::LimitQuery('forum', array('condition' => array('type' => 'building', 'stat' => 1), 'order' => "order by displayorder asc"));
}

$check = $_REQUEST['check'];
$de = $_REQUEST['de'];

$condition = array();
if ($de) {
    $condition[] = "depart LIKE '%{$de}%'";
}
$condition1 = $condition2 = $condition;
if ($check) {
    $in = explode(' ', $check);
    $condition1[] = "check_in>= '$in[0]' and check_in<= '$in[2]'";
    $condition2[] = "check_out>= '$in[0]' and check_out<= '$in[2]'";
}

$checkin_depart = DB::GetDbColumn('order', 'depart', $condition1);
$checkout_depart = DB::GetDbColumn('order', 'depart', $condition2);
$departs = array_unique(array_merge($checkin_depart, $checkout_depart));
$users = array();
foreach ($departs as $key => $depart) {
    $users[$key]['depart'] = $depart;
    $condition1['depart'] = $depart;
    $users[$key]['deposit'] = DB::Count('order', $condition1, 'deposit');

    $condition2['depart'] = $depart;
    $users[$key]['deposit_out'] = DB::Count('order', $condition2, 'deposit_out');
    $users[$key]['charge'] = DB::Count('order', $condition2, 'charge');
    $users[$key]['income'] = DB::Count('order', $condition2, 'income');
}
unset($condition1['depart']);
unset($condition2['depart']);
$users['all']['depart'] = '总计';
$users['all']['deposit'] = DB::Count('order', $condition1, 'deposit');
$users['all']['deposit_out'] = DB::Count('order', $condition2, 'deposit_out');
$users['all']['charge'] = DB::Count('order', $condition2, 'charge');
$users['all']['income'] = DB::Count('order', $condition2, 'income');


include template('work/depart_fee');
?>