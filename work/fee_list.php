<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();
checkperm('fee_list');
$nav = array('title' => '费用统计', 'nav1' => 'work', 'nav2' => 'fee_list');
//获得楼苑信息
$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court', 'stat' => 1), 'order' => "order by displayorder asc,id asc"));
foreach ($courts as $key => $court) {
    $courts[$key]['forum'] = DB::LimitQuery('forum', array('condition' => array('type' => 'building', 'stat' => 1), 'order' => "order by displayorder asc"));
}

$check = $_REQUEST['check'];
$condition1 = $condition2 = $condition = array();
if ($check) {
    $in = explode(' ', $check);
    $condition1[] = "check_in>= '$in[0]' and check_in<= '$in[2]'";
    $condition2[] = "check_out>= '$in[0]' and check_out<= '$in[2]'";
}

$checkin_uid = DB::GetDbColumn('order', 'checkin_uid', $condition1);
$checkout_uid = DB::GetDbColumn('order', 'checkout_uid', $condition2);
$uid = array_merge($checkin_uid, $checkout_uid);
$users = DB::GetDbColumn('user', array('user_id', 'username'), array('user_id' => $uid));
foreach ($users as $key => $user) {

    $condition1['checkin_uid'] = $user['user_id'];
    $users[$key]['deposit'] = DB::Count('order', $condition1, 'deposit');

    $condition2['checkout_uid'] = $user['user_id'];
    $users[$key]['deposit_out'] = DB::Count('order', $condition2, 'deposit_out');
    $users[$key]['charge'] = DB::Count('order', $condition2, 'charge');
    $users[$key]['income'] = DB::Count('order', $condition2, 'income');
}
unset($condition1['checkin_uid']);
unset($condition2['checkout_uid']);
$users['all']['username'] = '总计';
$users['all']['deposit'] = DB::Count('order', $condition1, 'deposit');
$users['all']['deposit_out'] = DB::Count('order', $condition2, 'deposit_out');
$users['all']['charge'] = DB::Count('order', $condition2, 'charge');
$users['all']['income'] = DB::Count('order', $condition2, 'income');


include template('work/fee_list');
?>