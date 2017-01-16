<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();

$nav = array('title' => '首页');

//获得楼苑信息
$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court', 'stat' => 1), 'order' => "order by displayorder asc"));
foreach ($courts as $key => $court) {
    $forums = DB::LimitQuery('forum', array('condition' => array('type' => 'building', 'stat' => 1), 'order' => "order by displayorder asc"));

    foreach ($forums as $k => $forum) {


        $forums[$k]['total_broom'] = count(DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'stat' => 1), 'group' => "group by broom")));

        $forums[$k]['total_bed'] = DB::Count('bed', array('fid' => $forum['id'], 'stat' => 1));
        $forum_bids = DB::GetDbColumn('bed', 'id', array('fid' => $forum['id'], 'stat' => 1));
        $forums[$k]['check_bed'] = DB::count('order', array('bid' => $forum_bids, 'stat' => 10));
        if ($forum['mold'] == 'sig') {
            //单间
            $floors = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'stat' => 1), 'group' => "group by floor", 'order' => "order by floor asc", 'select' => "floor"));

            foreach ($floors as $index => $floor) {
                //房间
                $brooms = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor], 'stat' => 1), 'select' => "broom,count(bed) as bed_num", 'group' => "group by broom", 'order' => "order by broom asc"));
                foreach ($brooms as $i => $broom) {


                    $brooms[$i]['bed'] = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom], 'stat' => 1), 'select' => "id,bed", 'order' => "order by bed asc"));
                    $bids = DB::GetDbColumn('bed', 'id', array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom], 'stat' => 1));
                    $brooms[$i]['order'] = DB::GetDbColumn('order', array('bid', 'id', 'created_time'), array('bid' => $bids, 'stat' => 10));
                }
                $forums[$k]['f'][$floor[floor]] = $brooms;
            }
//            var_dump($forums);
//            exit;
        } else {
            //套间
        }
        //床位
//        $forums[$k]['bed'] = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['fid'], 'stat' => 1), 'order' => "order by floor asc,broom asc,sroom asc,bed asc"));
    }

    $courts[$key]['forum'] = $forums;
}

//楼层
$floornames = DB::GetDbColumn('parameter', array('k', 'v'), array('name' => 'floorname'), true);

include template('work/index');
?>