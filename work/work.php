<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();
checkperm('work');
$nav = array('title' => '首页');

//预订退房
$leave_orders = DB::GetDbColumn('order', array('id', 'check_leave'), array('check_leave>0', 'checkout_time is null'), true);

//获得楼苑信息
$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court', 'stat' => 1), 'order' => "order by displayorder asc,id asc"));
foreach ($courts as $key => $court) {
    $forums = DB::LimitQuery('forum', array('condition' => array('type' => 'building', 'stat' => 1), 'order' => "order by displayorder asc"));

    foreach ($forums as $k => $forum) {


        $forums[$k]['total_broom'] = count(DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'stat' => 1), 'group' => "group by floor,broom")));
        $forums[$k]['total_sroom'] = count(DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'stat' => 1), 'group' => "group by floor,broom,sroom")));

        //总床位
        $forums[$k]['total_bed'] = DB::Count('bed', array('fid' => $forum['id'], 'stat' => 1));
        //已入住床位
        $forums[$k]['check_bed'] = DB::count('bed', array('fid' => $forum['id'], 'stat' => 1, "order_id>0"));

        //楼层
        $floors = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'stat' => 1), 'group' => "group by floor", 'order' => "order by floor asc", 'select' => "floor"));
        foreach ($floors as $index => $floor) {
            //房间
            $brooms = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor]), 'select' => "broom,count(bed) as bed_num", 'group' => "group by broom", 'order' => "order by broom asc"));
            foreach ($brooms as $i => $broom) {
                //大室入住数
                $brooms[$i]['check_bed'] = DB::count('bed', array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom], 'stat' => 1, "order_id>0"));



                $brooms[$i]['bed'] = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom]), 'select' => "id,bed,stat,note,order_id", 'order' => "order by sroom asc,bed asc"));
                $brooms[$i]['stat'] = 0;
                foreach ($brooms[$i]['bed'] as $b) {
                    if ($b['stat'] == 1) {
                        $brooms[$i]['stat'] = 1;
                        break;
                    }
                }
                foreach ($brooms[$i]['bed'] as $b) {
                    if (array_key_exists($b['order_id'], $leave_orders)) {
                        if (isset($brooms[$i]['leave'])) {
                            $a = ceil(($leave_orders[$b['order_id']] - time()) / 3600 / 24);
                            $brooms[$i]['leave'] = $brooms[$i]['leave'] > $a ? $a : $brooms[$i]['leave'];
                        } else {
                            $brooms[$i]['leave'] = ceil(($leave_orders[$b['order_id']] - time()) / 3600 / 24);
                        }
                    }
                }
                if ($forum['mold'] == 'mul') {

                    //套间
                    //小室
                    $brooms[$i]['sroom'] = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom]), 'select' => "sroom,count(bed) as bed_num", 'group' => "group by sroom", 'order' => "order by sroom asc"));
                    foreach ($brooms[$i]['sroom'] as $j => $sroom) {
                        //小室入住数
                        $brooms[$i]['sroom'][$j]['check_bed'] = DB::count('bed', array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom], 'sroom' => $sroom[sroom], 'stat' => 1, "order_id>0"));

                        $brooms[$i]['sroom'][$j]['bed'] = DB::LimitQuery('bed', array('condition' => array('fid' => $forum['id'], 'floor' => $floor[floor], 'broom' => $broom[broom], 'sroom' => $sroom[sroom]), 'select' => "id,bed,stat,note,order_id", 'order' => "order by sroom asc,bed asc"));
                        $brooms[$i]['sroom'][$j]['stat'] = 0;
                        foreach ($brooms[$i]['sroom'][$j]['bed'] as $s) {
                            if ($s['stat'] == 1) {
                                $brooms[$i]['sroom'][$j]['stat'] = 1;
                                break;
                            }
                        }
                        foreach ($brooms[$i]['sroom'][$j]['bed'] as $s) {
                            if (array_key_exists($s['order_id'], $leave_orders)) {
                                if (isset($brooms[$i]['sroom'][$j]['leave'])) {
                                    $a = ceil(($leave_orders[$s['order_id']] - time()) / 3600 / 24);
                                    $brooms[$i]['sroom'][$j]['leave'] = $brooms[$i]['sroom'][$j]['leave'] > $a ? $a : $brooms[$i]['sroom'][$j]['leave'];
                                } else {
                                    $brooms[$i]['sroom'][$j]['leave'] = ceil(($leave_orders[$s['order_id']] - time()) / 3600 / 24);
                                }
                            }
                        }
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

include template('work/work');
?>