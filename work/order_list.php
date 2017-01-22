<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();

$nav = array('title' => '订单记录', 'nav1' => 'work', 'nav2' => 'order_list');
//获得楼苑信息
$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court', 'stat' => 1), 'order' => "order by displayorder asc,id asc"));
foreach ($courts as $key => $court) {
    $courts[$key]['forum'] = DB::LimitQuery('forum', array('condition' => array('type' => 'building', 'stat' => 1), 'order' => "order by displayorder asc"));
}
$by = $_REQUEST['by'] ? $_REQUEST['by'] : 'id';
$order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
$condition = array();
$nums = DB::Count('order', $condition);
$orders = DB::LimitQuery('order', array('condition' => $condition, 'offset' => $INI['system']['page_num'] * $page, 'size' => $INI['system']['page_num'], 'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'id' ? '' : ',id desc'),));

foreach ($orders as $key => $order) {
    $orders[$key]['bed'] = DB::LimitQuery('bed', array('condition' => array('id' => explode(',', $order['bid']), 'order' => "order by sroom asc,bed asc")));
}
//楼层
$floornames = DB::GetDbColumn('parameter', array('k', 'v'), array('name' => 'floorname'), true);
//楼栋
$forums = DB::GetDbColumn('forum', array('id', 'fid', 'name'), array());
include template('work/order_list');
?>