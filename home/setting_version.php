<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '版本编辑', 'nav1' => 'setting', 'nav2' => 'version');
need_login();
checkperm('version');
$id = $_REQUEST['id'];

$version = DB::GetTableRow('version', array('id' => $id));
if ($version) {
    $attachs = explode(',', $version['file']);
    $sizes = explode(',', $version['size']);
}

include template('home/setting_version');
?>