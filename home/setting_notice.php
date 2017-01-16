<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '公告发布', 'nav1' => 'setting', 'nav2' => 'notice');
need_login();
checkperm('notice');
$id = $_REQUEST['id'];
$message = DB::GetTableRow('message', array('id' => $id));
if ($message) {
    $recode = explode(',', $message['recode']);
    if ($_SERVER['HTTP_REFERER'] == 'http://' . $_SERVER['HTTP_HOST'] . '/home/setting_notice' || $_SERVER['HTTP_REFERER'] == 'https://' . $_SERVER['HTTP_HOST'] . '/home/setting_notice') {
        $back_url = WEB_ROOT . '/home/setting_notice_select';
        // $back_url = 'javascript:history.go(-2);';
    } else {
        //$back_url = $_SERVER['HTTP_REFERER'];
        $back_url = 'javascript:history.go(-1);';
    }
}
include template('home/setting_notice');
?>