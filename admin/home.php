<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

//need_manager();
//MySql版本
$link = DB::GetLinkId();
$ms_vs = mysqli_get_server_info($link);


include template('admin/home');
