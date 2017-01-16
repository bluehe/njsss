<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');

$nav = array('title' => '修改密码', 'nav2' => 'password');
need_login();
if (is_post()) {
    $old = $_REQUEST['old'];
    $password = $_REQUEST['new'];
    $password1 = $_REQUEST['new1'];
    if ($old != '' || $password != '' || $password1 != '') {
        $old = ZUser::GenPassword($old);
        $login_user = DB::GetTableRow('user', array('user_id' => $_SESSION['user_id']));
        if ($old != $login_user['password']) {
            showmessage('error', '', '原密码错误！');
        } elseif ($password == '' || $password1 == '') {
            showmessage('error', '', '请输入新密码！');
        } elseif (!preg_match("/^[0-9A-Za-z]{4,16}$/", $password)) {
            showmessage('error', '', '密码为4-16个数字或英文字符！');
        } elseif ($password != $password1) {
            showmessage('error', '', '两次密码不一致！');
        } else {
//密码重置操作
            $p = ZUser::GenPassword($password);
            $res = DB::Update('user', $login_user['user_id'], array('password' => $p), 'user_id');
            if ($res) {
                showmessage('success', '', '密码修改成功！');
            } else {
                showmessage('error', '', '密码修改失败！');
            }
        }
        Utility::Redirect(WEB_ROOT . '/account/password');
    }
}
include template('account/password');
?>