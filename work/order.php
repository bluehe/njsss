<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();

$nav = array('title' => '业务操作');
//获得楼苑信息
$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court', 'stat' => 1), 'order' => "order by displayorder asc,id asc"));
foreach ($courts as $key => $court) {
    $courts[$key]['forum'] = DB::LimitQuery('forum', array('condition' => array('type' => 'building', 'stat' => 1), 'order' => "order by displayorder asc"));
}

$id = $_GET['id'];
$bed = DB::GetTableRow('bed', array('id' => $id, 'stat' => 1));
$forum = DB::GetTableRow('forum', array('id' => $bed['fid'], 'stat' => 1));


$court = DB::GetTableRow('forum', array('id' => $forum['fup'], 'stat' => 1));
if ($court) {
    //楼层
    $floornames = DB::GetDbColumn('parameter', array('k', 'v'), array('name' => 'floorname'), true);

    $beds = DB::LimitQuery('bed', array('condition' => array('fid' => $bed['fid'], 'floor' => $bed['floor'], 'broom' => $bed['broom'], 'stat' => 1), 'select' => "id,broom,sroom,bed,order_id", 'order' => "order by sroom asc,bed asc"));
    $order = DB::GetTableRow('order', array('id' => $bed['order_id']));
    if ($order) {
        $persons = json_decode($order['person'], true);
    } else {
        $persons = array(array());
    }

    include template('work/order');
} else {
    showmessage('error', '', '不存在有效床位！');
    Utility::Redirect();
}
?>