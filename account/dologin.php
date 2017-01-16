<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$seccodestatus = explode(',', $INI['system']['seccode_status']);
$sec = md5(strtoupper($_REQUEST['seccode']));
$seccode = $_SESSION['seccode'];
$form_type = $_REQUEST['form_type'];
if ($form_type == 'login') {
    $username = trim(strtolower($_REQUEST['username']));
    $password = trim($_REQUEST['password']);
    if ($username == '' || $password == '') {
        echo 'error|请输入账号和密码！';
        exit;
    }
    if (in_array("2", $seccodestatus) && $sec != $seccode) {
        echo 'error|验证码错误！';
    } else {
        $user = ZUser::GetLogin($username, $password);
        if (!$user) {
            echo 'error|用户名或密码错误！';
        } else {
            //登录
            ZLogin::Login($user['user_id']);

            //记住用户名
            if (isset($_REQUEST['rememberme'])) {
                ZLogin::Remember($username);
            } else {
                ZLogin::NoRemember();
            }

            //成功
            DB::Update('user', $user['user_id'], array('last_time' => time()), 'user_id');
            $url = isset($_SESSION['login_url']) ? Session::Get('login_url', TRUE) : WEB_ROOT . '/';
            echo 'redirect|' . $url;
        }
    }
} elseif ($form_type == 'password') {
    if (in_array("3", $seccodestatus) && $sec != $seccode) {
        echo 'error|验证码错误！';
    } else {
        $username = trim(strval(strtolower($_REQUEST['username'])));
        if ($username == '') {
            echo 'error|请输入账号！';
        } else {
            $user = ZUser::GetLoguser($username);
            if ($user) {
                echo 'success|' . WEB_ROOT . '/account/passwordreset?u=' . base64_encode($username);
            } else {
                echo 'error|用户不存在！';
            }
        }
    }
} elseif ($form_type == 'passwordreset') {
    $password = $_REQUEST['password'];
    $password1 = $_REQUEST['password1'];
    if ($password == '' || $password1 == '') {
        echo 'error|请输入新密码！';
    } elseif (!preg_match("/^[0-9A-Za-z]{4,16}$/", $password)) {
        echo 'error|密码为4-16个数字或英文字符！';
    } elseif ($password != $password1) {
        echo 'error|两次密码不一致！';
    } else {
        //密码格式正确
        $c = $_REQUEST['c'];
        if ($c != '') {
            $user = DB::GetTableRow('user', array('recode' => $c));
            if ($user) {
                //密码重置操作
                $p = ZUser::GenPassword($password);
                $res = DB::Update('user', $user['user_id'], array('password' => $p, 'recode' => ''), 'user_id');
                if ($res) {
                    echo 'success|' . WEB_ROOT . '/account/passwordreset?c=success';
                } else {
                    echo 'error|操作失败，请重新进行密码重置！';
                }
            } else {
                showmessage('error', '', '链接已经失效，请重新进行密码重置！');
                echo 'success|' . WEB_ROOT . '/account/passwordreset';
            }
        } else {
            echo 'error|操作错误，请重新打开链接！';
        }
    }
} elseif ($form_type == 'register') {
    if (in_array("1", $seccodestatus) && $sec != $seccode) {
        echo 'error|验证码错误！';
    } else {

        $user['username'] = trim(strval(strtolower($_REQUEST['username'])));
        $user['password'] = $_REQUEST['password'];
        $password1 = $_REQUEST['password1'];
        $user['email'] = trim(strval(strtolower($_REQUEST['email'])));
        $user['tel'] = trim(strval(strtolower($_REQUEST['tel'])));

        $user['reg_time'] = time();
        $user['stat'] = 1;
        if ($user['username'] == '') {
            echo 'error|请输入用户名！';
            exit;
        } elseif (!preg_match("/^[0-9A-Za-z]{4,16}$/", $user['username']) || preg_match("/^[0-9]*$/", $user['username'])) {
            echo 'error|用户名为4-16个数字或英文字符，不区分大小写，不能全为数字！';
            exit;
        }
        $user1 = DB::GetTableRow('user', array('username' => $user['username']));
        if ($user1) {
            echo 'error|用户名已经存在！';
            exit;
        }

        $mgc = DB::GetDbColumn('parameter', 'v', array('name' => 'username'));
        for ($i = 0; $i < count($mgc); $i++) {
            $content = substr_count($user['username'], $mgc[$i]);
            if ($content > 0) {
                echo 'error|用户名包含敏感词汇！';
                exit;
            }
        }
        if ($user['password'] == '') {
            echo 'error|请输入密码！';
            exit;
        } elseif (!preg_match("/^[0-9A-Za-z]{4,16}$/", $user['password'])) {
            echo 'error|密码为4-16个数字或英文字符！';
            exit;
        } elseif ($user['password'] != $password1) {
            echo 'error|两次密码不一致！';
            exit;
        }
        if ($user['email'] == '') {
            echo 'error|请输入电子邮箱！';
            exit;
        } elseif (!preg_match("/^([0-9A-Za-z\\-_\\.]+)@(([0-9a-z\\-]+\\.)?[0-9a-z\\-]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $user['email'])) {
            echo 'error|电子邮箱格式错误！';
            exit;
        }
        $user2 = DB::GetTableRow('user', array('email' => $user['email']));
        if ($user2) {
            echo 'error|电子邮箱已经存在！';
            exit;
        }
        if ($user['tel'] == '') {
            echo 'error|请输入联系电话！';
            exit;
        } elseif (!preg_match("/^((0\d{2,3}-\d{7,8}(-\d{1,6})?)|(1[34578]\d{9}))$/", $user['tel'])) {
            echo 'error|联系电话格式错误！';
            exit;
        }
        $user3 = DB::GetTableRow('user', array('tel' => $user['tel']));
        if ($user3) {
            echo 'error|联系电话已经存在！';
            exit;
        }
        $uid = ZUser::Create($user);
        if ($uid) {
            ZLogin::Login($uid);
            showmessage('info', '', '注册成功！');
            echo 'redirect|' . WEB_ROOT . '/index';
        } else {
            echo 'error|注册失败！';
        }
    }
}
?>