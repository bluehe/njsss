<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();

$type = $_GET['type'];
if (is_post()) {
    $parameters = $_POST['parameter'];
    $res1 = 1;
    $res2 = 1;
    $res3 = 1;
    foreach ($parameters as $key => $parameter) {
        $c = DB::GetTableRow('parameter', array('name' => $type, 'v' => $parameter['v']));
        if (!$c) {
            $res1 = $res1 && DB::Update('parameter', $key, array('v' => $parameter['v']));
        } elseif ($c['id'] != $key) {
            adminmessage('3', '参数名称已经存在', WEB_ROOT . '/admin/parameter?type=' . $type);
        }
    }

    if ($_POST['delete']) {
        $res2 = $res2 && DB::DelTableRow('parameter', $_POST['delete']);
    }

    if ($_POST['newcode'] != "") {
        $newcodes = $_POST['newcode'];
        $newnames = $_POST['newname'];
        $new = array();
        foreach ($newnames as $key => $one) {
            $new[$key]['k'] = $newcodes[$key];
            $new[$key]['v'] = $newnames[$key];
            $new[$key]['name'] = $type;
        }
        foreach ($new as $one) {
            $d = DB::GetTableRow('parameter', array('name' => $type, 'OR' => array('k' => $one['k'], 'v' => $one['v'])));
            if ($d) {
                adminmessage('3', '参数编号或名称已经存在', WEB_ROOT . '/admin/parameter?type=' . $type);
            } else {
                $res3 = $res3 && (DB::Insert('parameter', $one) ? true : false);
            }
        }
    }
    if ($res1 === true || $res2 === true || $res3 === true) {
        adminmessage('2', '参数编辑成功。', WEB_ROOT . '/admin/parameter?type=' . $type, true, 3);
    }
}

$parameters = DB::LimitQuery('parameter', array('condition' => array('name' => $type),
            'order' => 'ORDER BY id ASC',
        ));


include template('admin/parameter');
?>