<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();
//Session::Set('iframe_page', 'system');
if (is_post()) {
    $system = $_POST['system'];
    $system['forum_statcode'] = stripslashes($system['forum_statcode']);

    foreach ($system AS $key => $one) {
        if ($one != $INI['system'][$key]) {
            if (isset($INI['system'][$key])) {
                $res = DB::Update('system', $key, array('v' => $one), 'k');
            } else {
                $res = DB::Insert('system', array('k' => $key, 'v' => $one));
            }
        }
    }
    if ($res) {
        adminmessage('2', '系统设置成功。', WEB_ROOT . '/admin/system', true, 3);
    }
}


include template('admin/system');
