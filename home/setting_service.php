<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '服务器编辑', 'nav1' => 'setting', 'nav2' => 'service');
need_login();
checkperm('service');
$id = $_REQUEST['id'];

$service = DB::GetTableRow('service', array('id' => $id));


include template('home/setting_service');
?>