<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();

$mailers = array(
//    'usercheck' => '用户审核',
//    'userdelete' => '用户删除通知',
    'passwordreset' => '密码重置'
);

$id = $_GET['id'] ? strval($_GET['id']) : 'passwordreset';
if ($id && !in_array($id, array_keys($mailers))) {
    Utility::Redirect(WEB_ROOT . '/admin/mailer');
}
//$n = Table::Fetch('mailer', $id);
$n = DB::GetTableRow('mailer', array('id' => $id));
if (is_post()) {
    $table = new Table('mailer', $_POST);
    $table->SetStrip('title', 'value');
    if ($n) {
        $table->SetPk('id', $id);
        $table->update(array('id', 'title', 'value'));
    } else {
        $table->insert(array('id', 'title', 'value'));
    }

    adminmessage('2', '页面设置成功。', WEB_ROOT . '/admin/mailer?id=' . $id, true, 3);
}

include template('admin/mailer');
