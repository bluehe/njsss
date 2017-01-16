<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();
checkperm('system');
//大标签修改
$a = Utility::ExtraDecode($INI['system']['current_admin_a']);

$s = array();
foreach ($a as $key => $one) {
    $s[] = '\'' . $key . '\'';
}
$str = implode(",", $s);


$b = Utility::ExtraDecode($INI['system']['current_admin_b']);

include template('admin/admincp');
?>