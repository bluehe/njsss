<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$table = $_REQUEST['table'];
$id = $_REQUEST['id'];
$label = $_REQUEST['label'];
$value = $_REQUEST['value'];
$pkname = $_REQUEST['pkname'];
$type = $_REQUEST['type'];
if ($table == 'battery' && $type == 'update' && $label == 'stat') {
    //电瓶类更改需要检查用户匹配情况
    $battery = DB::GetTableRow('battery', array($pkname => $id));
    $category = DB::GetTableRow('category', array('id' => $battery['category']));
    if ($category['uid'] != -$battery['site_id']) {
        $site = DB::GetTableRow('website', array('id' => $battery['site_id']));
        if ($category['uid'] != $site['uid']) {
            echo 'fail';
            exit;
        }
    }
}
if ($type == 'update') {
    $res = DB::Update($table, $id, array($label => $value), $pkname);
} elseif ($type == 'delete') {
    if ($table == 'role') {
        $res = DB::Delete('auth_item', array('type' => 1, 'name' => $id));
        $res = $res && DB::Delete('auth_item_child', array('parent' => $id));
        $res = $res && DB::Delete('auth_assignment', array('item_name' => $id));
    } else if ($table == 'permission') {
        $res = DB::Delete('auth_item', array('type' => 2, 'name' => $id));
        $res = $res && DB::Delete('auth_item_child', array('child' => $id));
    } else {
        $res = DB::DelTableRow($table, $id, $pkname);
    }
}
echo $res ? 'success' : 'fail';
?>