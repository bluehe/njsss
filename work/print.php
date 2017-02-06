<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();
checkperm('order');
$type = $_REQUEST['type'];
if ($type == 'deposit') {
    $id = $_REQUEST['id'];
    $order = DB::GetTableRow('order', array('id' => $id));
    $bids = explode(',', $order['bid']);
    $beds = DB::LimitQuery('bed', array('condition' => array('id' => $bids), 'select' => "id,broom,sroom,bed,fid,floor", 'order' => "order by sroom asc,bed asc"));
    $forum = DB::GetTableRow('forum', array('id' => $beds[0]['fid'], 'stat' => 1));
    $court = DB::GetTableRow('forum', array('id' => $forum['fup'], 'stat' => 1));

    //楼层
    $floornames = DB::GetDbColumn('parameter', array('k', 'v'), array('name' => 'floorname'), true);

    include template('work/print_deposit');
}
?>