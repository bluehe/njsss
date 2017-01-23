<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();

$nav = array('title' => '费用统计', 'nav1' => 'work', 'nav2' => 'fee_list');
//获得楼苑信息
$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court', 'stat' => 1), 'order' => "order by displayorder asc,id asc"));
foreach ($courts as $key => $court) {
    $courts[$key]['forum'] = DB::LimitQuery('forum', array('condition' => array('type' => 'building', 'stat' => 1), 'order' => "order by displayorder asc"));
}


$condition = array();
$checkin_uid = DB::GetDbColumn('order', 'checkin_uid', array());
$checkout_uid = DB::GetDbColumn('order', 'checkout_uid', array());
$uid = array_merge($checkin_uid, $checkout_uid);
$users = DB::GetDbColumn('user', array('user_id', 'username'), array('user_id' => $uid));
foreach ($users as $key => $user) {
    $condition1 = $condition;
    $condition1['checkin_uid'] = $user['user_id'];
    $users[$key]['deposit'] = DB::Count('order', $condition1, 'deposit');
    $condition2 = $condition;
    $condition2['checkout_uid'] = $user['user_id'];
    $users[$key]['deposit_out'] = DB::Count('order', $condition2, 'deposit_out');
    $users[$key]['charge'] = DB::Count('order', $condition2, 'charge');
    $users[$key]['income'] = DB::Count('order', $condition2, 'income');
}
$users['all']['username'] = '总计';
$users['all']['deposit'] = DB::Count('order', $condition, 'deposit');
$users['all']['deposit_out'] = DB::Count('order', $condition, 'deposit_out');
$users['all']['charge'] = DB::Count('order', $condition, 'charge');
$users['all']['income'] = DB::Count('order', $condition, 'income');


include template('work/fee_list');
?>