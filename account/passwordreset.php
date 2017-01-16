<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$nav = array('title' => '密码重置');
if (is_login()) {
    Utility::Redirect(WEB_ROOT . '/');
}
$seccodestatus = explode(',', $INI['system']['seccode_status']);

$c = $_REQUEST['c'];
if ($c != '') {
    if ($c == 'success') {
        include template('account/passwordresetfinish');
    } else {
        $user = DB::GetTableRow('user', array('recode' => $c));
        if ($user) {
            include template('account/passwordresetinfo');
        } else {
            showmessage('error', '', '链接已经失效，请重新进行密码重置！');
            Utility::Redirect(WEB_ROOT . '/account/passwordreset');
        }
    }
} else {
    $username = $_REQUEST['u'] ? base64_decode($_REQUEST['u']) : $_REQUEST['username'];
    $user = ZUser::GetLoguser($username);
    if ($user) {
        $code_rand = mt_rand(0, 1000000);

        $way = $_REQUEST['way'];
        if ($way == 'email') {
            $r = Session::Get('code_rand');
            if ($_REQUEST['code_rand'] == $r) {
                Session::Set('code_rand', $code_rand);
                $code = $user['recode'];
                if ($code == '') {
                    $code = md5(json_encode($user) . time());
                    DB::Update('user', $user['user_id'], array('recode' => $code), 'user_id');
                }
                $user['code'] = $code;
                $res = ZNotice::SendMail('passwordreset', $user);
                if ($res) {
                    include template('account/passwordresetemail');
                } else {
                    showmessage('error', '', '邮件发送失败，请确认邮箱地址正确，或者选择其他验证方式');
                    Utility::Redirect(WEB_ROOT . '/account/passwordreset');
                }
            } else {
                include template('account/passwordresetemail');
            }
        } else {
            Session::Set('code_rand', $code_rand);
            include template('account/passwordresetfirst');
        }
    } else {
        include template('account/passwordreset');
    }
}