<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();

$nav = array('title' => '首页');

//获得楼苑信息
$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court', 'stat' => 1), 'order' => "order by displayorder asc,id asc"));
foreach ($courts as $key => $court) {
    $forums = DB::LimitQuery('forum', array('condition' => array('type' => 'building', 'stat' => 1), 'order' => "order by displayorder asc"));

    foreach ($forums as $k => $forum) {


        $forums[$k]['total_broom'] = count(DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'stat' => 1), 'group' => "group by floor,broom")));
        $forums[$k]['total_sroom'] = count(DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'stat' => 1), 'group' => "group by floor,broom,sroom")));

        $forums[$k]['total_bed'] = DB::Count('bed', array('fid' => $forum['id'], 'stat' => 1));
        $forum_bids = DB::GetDbColumn('bed', 'id', array('fid' => $forum['id'], 'stat' => 1));
        $forums[$k]['check_bed'] = DB::count('order', array('bid' => $forum_bids, 'stat' => 10));

        $floors = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'stat' => 1), 'group' => "group by floor", 'order' => "order by floor asc", 'select' => "floor"));
        foreach ($floors as $index => $floor) {
            //房间
            $brooms = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor]), 'select' => "broom,count(bed) as bed_num", 'group' => "group by broom", 'order' => "order by broom asc"));
            foreach ($brooms as $i => $broom) {
                //大室总记录
                $bids = DB::GetDbColumn('bed', 'id', array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom], 'stat' => 1));
                $brooms[$i]['order'] = DB::GetDbColumn('order', array('bid', 'id', 'created_time'), array('bid' => $bids, 'stat' => 10));
                $brooms[$i]['bed'] = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom]), 'select' => "id,bed,stat,note", 'order' => "order by sroom asc,bed asc"));
                if ($forum['mold'] == 'mul') {

                    //套间
                    //小室
                    $brooms[$i]['sroom'] = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom]), 'select' => "sroom,count(bed) as bed_num", 'group' => "group by sroom", 'order' => "order by sroom asc"));
                    foreach ($brooms[$i]['sroom'] as $j => $sroom) {
                        $brooms[$i]['sroom'][$j]['bed'] = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom], 'sroom' => $sroom[sroom]), 'select' => "id,bed,stat,note", 'order' => "order by sroom asc,bed asc"));
                        $bids_r = DB::GetDbColumn('bed', 'id', array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom], 'sroom' => $sroom[sroom], 'stat' => 1));
                        $brooms[$i]['sroom'][$j]['order'] = DB::GetDbColumn('order', array('bid', 'id', 'created_time'), array('bid' => $bids_r, 'stat' => 10));
                    }
                }
//                var_dump($brooms);
//                exit;
            }
            $forums[$k]['f'][$floor[floor]] = $brooms;
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