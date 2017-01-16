<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();

$fid = $_GET['fid'];
$forum = DB::GetDbRowById('forum', $fid);
if ($forum) {
    //操作表
    if (is_post()) {

        if ($_POST['statue'] == 'ok') {
            $res3 = 1;
            $forumbeds = $_POST['forumbeds'];
            $sql = "insert into bed_tmp select * from bed where id in ({$forumbeds})";
            DB::Query($sql);
            $res3 = DB::DelTableRow('bed', explode(",", $forumbeds));
            if ($res3 === true) {
                adminmessage('2', '床位删除成功', '/admin/forumsetting?fid=' . $fid, true, 3);
            } else {
                adminmessage('3', '床位删除过程中出现错误', '/forumsetting?fid=' . $fid, true, 3);
            }
        }
        
         if ($_POST['bidarray'] != "") {
            adminmessage('3', '<form method="post" action="/admin/forumsetting?fid=' . $fid . '">
						 <input type="hidden"  name="forumbeds" value="' . implode(",", $_POST['bidarray']) . '"  />
						 <input type="hidden"  name="statue" value="ok"  /><br />
						 <h4 class="marginbot normal"><strong>本操作不可恢复，您确定要删除这些床位吗？</strong><br /></h4><br />
						 <p class="margintop">
						 <input type="submit" class="btn" name="confirmed" value="确定">&nbsp;&nbsp;
						 <input type="button" class="btn" value="取消" onclick="history.go(-1);"></p></form>', false);
        }

        $f = array();
        $res1 = 1;
        $res2 = 2;

        $f['name'] = $_POST['namenew'];
        $f['stat'] = $_POST['statnew'];
        $f['fup'] = $_POST['fupnew'];
        //$f['floor'] = $_POST['floornew'];
        //$f['mold'] = $_POST['moldnew'];
        $f['roomtype'] = $_POST['roomtype'];
        $f['cost_room'] = $_POST['cost_room'];
        $f['cost_water'] = $_POST['cost_water'];
        $f['cost_electric'] = $_POST['cost_electric'];

        $f['description'] = $_POST['descriptionnew'];
        $one = DB::GetDbRowById('forum', $fid);
        $change = array_diff_assoc($f, $one);
        if (!empty($change))
            $res1 = $res1 && DB::Update('forum', $fid, $change);

        if ($_POST['newfloor'] != "") {
            $newfloors = $_POST['newfloor'];
            $newbrooms = $_POST['newbroom'];

            $newbeds = $_POST['newbed'];
            $newnotes = $_POST['newnote'];
            $newstats = $_POST['newstat'];

            foreach ($newfloors as $key => $newfloor) {
                if ($newbrooms[$key] == '' || $newbeds[$key] == '') {
                    adminmessage('3', '大室或床位不能为空', '/admin/forumsetting?fid=' . $fid, true, 3);
                }
                $new = array();
                $new['floor'] = $newfloor;
                $brooms = explode(",", $newbrooms[$key]);
                $beds = explode(",", $newbeds[$key]);
                $new['stat'] = $newstats[$key];
                $new['note'] = $newnotes[$key];
                $new['fid'] = $fid;

                foreach ($brooms as $broom) {
                    $br = explode("~", $broom);
                    if (count($br) > 1) {
                        for ($a = $br[0]; $a <= $br[1]; $a++) {
                            $new['broom'] = $a;
                            foreach ($beds as $bed) {
                                $new['bed'] = $bed;
                                if ($forum['mold'] == 'mul') {
                                    $newsrooms = $_POST['newsroom'];
                                    $srooms = explode(",", $newsrooms[$key]);
                                    foreach ($srooms as $sroom) {
                                        $new['sroom'] = $sroom;
                                        $res2 = $res2 && (DB::Insert('bed', $new) ? true : false);
                                    }
                                } else {
                                    $res2 = $res2 && (DB::Insert('bed', $new) ? true : false);
                                }
                            }
                        }
                    } else {
                        $new['broom'] = $broom;
                        foreach ($beds as $bed) {
                            $new['bed'] = $bed;
                            if ($forum['mold'] == 'mul') {
                                $newsrooms = $_POST['newsroom'];
                                $srooms = explode(",", $newsrooms[$key]);
                                foreach ($srooms as $sroom) {
                                    $new['sroom'] = $sroom;
                                    $res2 = $res2 && (DB::Insert('bed', $new) ? true : false);
                                }
                            } else {
                                $res2 = $res2 && (DB::Insert('bed', $new) ? true : false);
                            }
                        }
                    }
                }
            }
        }
        if ($res1 === true || $res2 === true) {
            adminmessage('2', '楼苑更改成功。', '/admin/forumsetting?fid=' . $fid, true, 3);
        }

       
    }


    $courts = DB::LimitQuery('forum', array('condition' => array('type' => 'court'),
                'order' => 'ORDER BY displayorder ASC',));
    $builds = DB::LimitQuery('forum', array('condition' => array('type' => 'building'),
                'order' => 'ORDER BY fup,displayorder,name ASC',));

    $nums = DB::Count('bed', array('fid' => $fid));
    list($pagesize, $offset, $pagestring) = pagestring($nums, $INI['system']['page_num']);
    $beds = DB::LimitQuery('bed', array('condition' => array('fid' => $fid),
                'offset' => $offset,
                'size' => $pagesize,
                'order' => 'ORDER BY floor,broom,sroom,bed ASC',));
    $bed_stats = DB::LimitQuery('parameter', array('condition' => array('name' => 'bed_stat'),
                'order' => 'ORDER BY id ASC',));
    $stat = array();
    foreach ($bed_stats as $one) {
        $stat[$one[k]] = $one['v'];
    }


    //楼层
    $floornames = DB::GetDbColumn('parameter', array('k', 'v'), array('name' => 'floorname', "k <=$forum[floor]"), true);
} else {
    adminmessage('3', '错误操作！', '/admin/forum', true, 3);
}


include template('admin/forumsetting');
?>