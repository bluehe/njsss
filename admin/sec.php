<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();

$sec = explode(",", $INI['system']['seccode_status']);
$seccodestatus = explode(',', $INI['system']['seccode_status']);

if (is_post()) {
    $seccode = array();
    $seccode['seccode_length'] = $_POST['seccode_length'];
    $seccode['seccode_status'] = implode(",", $_POST['seccodestatus']);

    $res = 1;
    foreach ($seccode AS $key => $one) {
        if ($one != $INI['system'][$key]) {
            $res = $res && DB::Update('system', $key, array('v' => $one), 'k');
        }
    }
    if ($res === true) {
        adminmessage('2', '安全设置成功。', WEB_ROOT . '/admin/sec', true, 3);
    }
}

include template('admin/sec');
