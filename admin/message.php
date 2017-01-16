<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

//need_manager();

$mtype = Session::Get('message_type');
$message = Session::Get('message');
$message_url = Session::Get('message_url');

include template('admin/message');
