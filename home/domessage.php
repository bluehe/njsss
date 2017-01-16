<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$form_type = $_REQUEST['form_type'];
if ($form_type == 'load') {
    $id = $_REQUEST['id'];
    $s = $_REQUEST['s'];
    $condition = array('receive_uid' => $login_user['user_id'], 'stat' => $s, "id NOT IN (" . $id . "0)");
    $num = DB::Count('message', $condition);
    $messages = DB::LimitQuery('message', array('condition' => $condition,
                'offset' => 0,
                'size' => $INI['system']['page_num'],
                'order' => 'ORDER BY id DESC',
    ));
//    foreach ($messages as $key => $message) {
//        $messages[$key]['content'] = strip_tags($message['content']);
//    }
    echo $num > $INI['system']['page_num'] ? 'yes|' : 'no|';
    echo $messages ? json_encode($messages) : '';
} elseif ($form_type == 'read') {
    if ($_REQUEST['id'] == 'all') {
        $res = DB::Update('message', array('receive_uid' => $login_user['user_id'], 'stat' => 1), array('stat' => 0, 'read_time' => time()));
        $num = 0;
    } else {
        $id = explode(',', $_REQUEST['id']);
        $res = DB::Update('message', array('id' => $id), array('stat' => 0, 'read_time' => time()));
        $num = DB::Count('message', array('receive_uid' => $login_user['user_id'], 'stat' => 1));
    }
    echo ($res ? 1 : 0 ) . "|" . $num;
}
?>