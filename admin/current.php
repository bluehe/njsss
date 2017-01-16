<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();
$f = $_REQUEST['f'] ? $_REQUEST['f'] : 'back';
if (is_post()) {
    $res1 = 1;
    $res2 = 1;
    if ($f == 'front') {
        $mm = $_POST['m'];
        $m_n = array();
        foreach ($mm as $key => $one) {
            foreach ($one as $two) {

                if (trim($two[0]) != "" && trim($two[1]) != "") {
                    $m_n[$key][$two[0]]['value'] = $two[1];
                    if (array_key_exists('side', $two)) {
                        foreach ($two['side'] as $three) {
                            if (trim($three[0]) != "" && trim($three[1]) != "") {
                                $m_n[$key][$two[0]]['side'][$three[0]] = $three[1];
                            }
                        }
                    }
                }
            }
        }
        $m_en = Utility::ExtraEncode($m_n);
        //$lm = Table::Fetch('system', 'current_menu', 'k');
        $lm = DB::GetTableRow('system', array('k' => 'current_menu'));
        if ($lm['v'] != $m_en)
            $res1 = $res1 && DB::Update('system', 'current_menu', array('v' => $m_en), 'k');
    } else {
        $aa = $_POST['a'];
        $bb = $_POST['b'];
        $a_n = array();
        $b_n = array();
        foreach ($aa as $key => $one) {
            if (trim($one[0]) != "" && trim($one[1]) != "") {
                if ($one[2]) {
                    $a_n[$one[0]][] = $one[2];
                } else {
                    $a_n[$one[0]][] = '';
                }
                $a_n[$one[0]][] = $one[1];
                foreach ($bb[$key] as $two) {
                    if (trim($two[0]) != '' && trim($two[1]) != '') {
                        $b_n[$one[0]][$two[0]] = $two[1];
                    }
                }
            }
        }

        $a_en = Utility::ExtraEncode($a_n);
        $b_en = Utility::ExtraEncode($b_n);
        //$la = Table::Fetch('system', 'current_admin_a', 'k');
        // $lb = Table::Fetch('system', 'current_admin_b', 'k');
        $la = DB::GetTableRow('system', array('k' => 'current_admin_a'));
        $lb = DB::GetTableRow('system', array('k' => 'current_admin_b'));
        if ($la['v'] != $a_en)
            $res1 = $res1 && DB::Update('system', 'current_admin_a', array('v' => $a_en), 'k');
        if ($lb['v'] != $b_en)
            $res2 = $res2 && DB::Update('system', 'current_admin_b', array('v' => $b_en), 'k');
    }
    if ($res1 === true || $res2 === true) {
        adminmessage('2', '标签更改成功。', WEB_ROOT . '/admin/current?f=' . $f, true, 3);
    }
    $res3 = 1;
    $res4 = 1;
    if ($_POST['newcat']) {
        $newcatorder = $_POST['newcatorder'];
        $newcat = $_POST['newcat'];
        $sa = Utility::ExtraDecode($la['v']);

        foreach ($newcatorder as $key => $one) {
            if ($one == "" || $one == '页面名称' || $newcat[$key] == "" || $newcat[$key] == '显示名称' || array_key_exists($one, $sa)) {
                adminmessage('3', '您没有正确填写页面名称或显示名称，请返回修改。', WEB_ROOT . '/admin/current?f=' . $f);
            } else {
                $sa[$one][] = '';
                $sa[$one][] = $newcat[$key];
            }
        }
        $a_e = Utility::ExtraEncode($sa);
        $res3 = $res3 && DB::Update('system', 'current_admin_a', array('v' => $a_e), 'k');
    }

    if ($_POST['newforum']) {
        $newforumorder = $_POST['newforumorder'];
        $newforum = $_POST['newforum'];
        $sb = Utility::ExtraDecode($lb['v']);
        foreach ($newforumorder as $key => $one) {
            foreach ($one as $index => $two) {
                if ($two == "" || $two == '新分页名称' || $newforum[$key][$index] == "" || $newforum[$key][$index] == '新显示名称' || array_key_exists($two, $sb[$key])) {
                    adminmessage('3', '您没有正确填写页面名称或显示名称，请返回修改。', WEB_ROOT . '/admin/current?f=' . $f);
                } else {
                    $sb[$key][$two] = $newforum[$key][$index];
                }
            }
        }
        $b_e = Utility::ExtraEncode($sb);
        $res4 = $res4 && DB::Update('system', 'current_admin_b', array('v' => $b_e), 'k');
    }
    if ($_POST['newmenu'] || $_POST['newmenuside']) {
        $newmenuorder = $_POST['newmenuorder'];
        $newmenu = $_POST['newmenu'];
        $newmenusideorder = $_POST['newmenusideorder'];
        $newmenuside = $_POST['newmenuside'];
        $sm = Utility::ExtraDecode($lm['v']);

        foreach ($newmenuorder as $key => $one) {
            foreach ($one as $index => $two) {
                if ($two == "" || $two == '页面名称' || $newmenu[$key][$index] == "" || $newmenu[$key][$index] == '显示名称' || array_key_exists($two, $sm[$key])) {
                    adminmessage('3', '您没有正确填写页面名称或显示名称，请返回修改。', WEB_ROOT . '/admin/current?f=' . $f);
                } else {
                    $sm[$key][$two]['value'] = $newmenu[$key][$index];
                }
            }
        }
        foreach ($newmenusideorder as $key => $one) {
            foreach ($one as $index => $two) {
                foreach ($two as $k => $three) {
                    if ($three == "" || $three == '新分页名称' || $newmenuside[$key][$index][$k] == "" || $newmenu[$key][$index][$k] == '新显示名称' || array_key_exists($three, $sm[$key][$index])) {
                        adminmessage('3', '您没有正确填写页面名称或显示名称，请返回修改。', WEB_ROOT . '/admin/current?f=' . $f);
                    } else {
                        $sm[$key][$index]['side'][$three] = $newmenuside[$key][$index][$k];
                    }
                }
            }
        }
        $m_e = Utility::ExtraEncode($sm);
        $res3 = $res3 && DB::Update('system', 'current_menu', array('v' => $m_e), 'k');
    }
    if ($res3 === true || $res4 === true) {
        adminmessage('2', '标签更改成功。', WEB_ROOT . '/admin/current?f=' . $f, true, 3);
    }
}

//$a = Table::Fetch('system', 'current_admin_a', 'k');
//$b = Table::Fetch('system', 'current_admin_b', 'k');
//$m = Table::Fetch('system', 'current_menu', 'k');
$a = DB::GetTableRow('system', array('k' => 'current_admin_a'));
$b = DB::GetTableRow('system', array('k' => 'current_admin_b'));
$m = DB::GetTableRow('system', array('k' => 'current_menu'));

$as = Utility::ExtraDecode($a['v']);
$bs = Utility::ExtraDecode($b['v']);
$ms = Utility::ExtraDecode($m['v']);

include template('admin/current');
?>