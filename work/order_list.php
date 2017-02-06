<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();
checkperm('order_list');
$nav = array('title' => '订单记录', 'nav1' => 'work', 'nav2' => 'order_list');
//获得楼苑信息
$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court', 'stat' => 1), 'order' => "order by displayorder asc,id asc"));
foreach ($courts as $key => $court) {
    $courts[$key]['forum'] = DB::LimitQuery('forum', array('condition' => array('type' => 'building', 'stat' => 1), 'order' => "order by displayorder asc"));
}


$by = $_REQUEST['by'] ? $_REQUEST['by'] : 'id';
$sort = $_REQUEST['sort'] ? $_REQUEST['sort'] : 'desc';
$page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;

$checkin = $_REQUEST['checkin'];
$checkout = $_REQUEST['checkout'];
$bid = $_REQUEST['bid'];
$pe = $_REQUEST['pe'];
$depart = $_REQUEST['depart'];
$condition = array();
if ($checkin) {
    $in = explode(' ', $checkin);
    $condition[] = "check_in>= '$in[0]' and check_in<= '$in[2]'";
}
if ($checkout) {
    $out = explode(' ', $checkout);
    $condition[] = "check_out>= '$out[0]' and check_out<= '$out[2]'";
}
if ($bid) {
    $condition[] = "FIND_IN_SET('$bid',bid)";
}
if ($pe) {
    $condition[] = "person LIKE '%{$pe}%'";
}
if ($depart) {
    $condition[] = "depart LIKE '%{$depart}%'";
}
$nums = DB::Count('order', $condition);
$orders = DB::LimitQuery('order', array('condition' => $condition, 'offset' => $INI['system']['page_num'] * $page, 'size' => $INI['system']['page_num'], 'order' => 'ORDER BY ' . $by . ' ' . $sort . ($by == 'id' ? '' : ',id desc'),));

foreach ($orders as $key => $order) {
    $orders[$key]['bed'] = DB::LimitQuery('bed', array('condition' => array('id' => explode(',', $order['bid'])), 'order' => "order by sroom asc,bed asc"));
    $orders[$key]['person'] = json_decode($order['person'], true);
}

//楼层
$floornames = DB::GetDbColumn('parameter', array('k', 'v'), array('name' => 'floorname'), true);
//楼栋
$forums = DB::GetDbColumn('forum', array('id', 'fid', 'mold', 'name'), array());

//床位
$beds = DB::LimitQuery('bed', array('condition' => array(), 'select' => "id,fid,floor,broom,sroom,bed", 'order' => "order by fid asc,floor asc,broom asc,sroom asc,bed asc"));
include template('work/order_list');
?>