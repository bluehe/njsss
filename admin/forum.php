<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();

if ($_GET['stat'] != "") {
    $fid = $_GET['fid'];
    $stat = $_GET['stat'];
    $res5 = 1;
    $forum = DB::GetDbRowById('forum', $fid);
    if ($forum) {
        $res5 = $res5 && DB::Update('forum', $fid, array('stat' => $stat));
    } else {
        adminmessage('3', '错误操作！', '/admin/forum', true, 3);
    }
    if ($res5 === true) {
        adminmessage('2', '楼苑状态更改成功。', '/admin/forum', true, 3);
    }
}
if (is_post()) {
    $cs = $_POST['court'];
    $bs = $_POST['build'];
    $res1 = 1;
    $res2 = 1;
    foreach ($cs as $key => $one) {
        $l = DB::GetDbRowById('forum', $key);
        $change = array_diff_assoc($one, $l);
        if (!empty($change))
            $res1 = $res1 && DB::Update('forum', $key, $change);
    }
    foreach ($bs as $key => $one) {
        $l = DB::GetDbRowById('forum', $key);
        $change = array_diff_assoc($one, $l);
        if (!empty($change))
            $res2 = $res2 && DB::Update('forum', $key, $change);
    }

    if ($res1 === true || $res2 === true) {
        adminmessage('2', '楼苑更改成功。', '/admin/forum', true, 3);
    }
    $res3 = 1;
    $res4 = 1;
    if ($_POST['newcat']) {

        $newcatorder = $_POST['newcatorder'];
        $newcat = $_POST['newcat'];
        $c_new = array();
        foreach ($newcat as $key => $one) {
            if ($one == "" || $one == '新苑名称') {
                adminmessage('3', '您没有正确填写楼苑名称，请返回修改。', '/admin/forum');
            } else {
                $c_new['name'] = $one;
            }

            $c_new['displayorder'] = $newcatorder[$key];
            $c_new['fup'] = 0;
            $c_new['type'] = 'court';
            $c_new['stat'] = 1;
            $res3 = $res3 && (DB::Insert('forum', $c_new) ? true : false);
        }
    }

    if ($_POST['newforum']) {
        $neworder = $_POST['neworder'];
        $newforum = $_POST['newforum'];
        $newfloor = $_POST['newfloor'];
        $newmold = $_POST['newmold'];
        $newroomtype = $_POST['newroomtype'];
        $newcostroom = $_POST['newcostroom'];
        $newcostwater = $_POST['newcostwater'];
        $newcostelectric = $_POST['newcostelectric'];
        $b_new = array();
        foreach ($newforum as $key => $one) {
            foreach ($one as $index => $two) {
                if ($two == "" || $two == '新楼名称') {
                    adminmessage('3', '您没有正确填写楼苑名称，请返回修改。', '/admin/forum');
                } else {
                    $b_new['name'] = $two;
                }
                $b_new['displayorder'] = $neworder[$key][$index];
                $b_new['floor'] = $newfloor[$key][$index];
                $b_new['mold'] = $newmold[$key][$index];
                $b_new['roomtype'] = $newroomtype[$key][$index];
                $b_new['cost_room'] = $newcostroom[$key][$index];
                $b_new['cost_water'] = $newcostwater[$key][$index];
                $b_new['cost_electric'] = $newcostelectric[$key][$index];
                $b_new['fup'] = $key;
                $b_new['type'] = 'building';
                $b_new['stat'] = 1;
                $res4 = $res4 && (DB::Insert('forum', $b_new) ? true : false);
            }
        }
    }

    if ($res3 === true || $res4 === true) {
        adminmessage('2', '楼苑添加成功。', '/admin/forum', true, 3);
    }
}


$courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court'),
            'order' => 'ORDER BY displayorder,id ASC',));
$builds = DB::LimitQuery('forum', array('condition' => array('type' => 'building'),
            'order' => 'ORDER BY fup,displayorder,id ASC',));


//楼层
$floornames = DB::LimitQuery('parameter', array('condition' => array('name' => 'floorname'),
            'order' => 'ORDER BY CAST(k as SIGNED) ASC',
        ));
include template('admin/forum');
?>