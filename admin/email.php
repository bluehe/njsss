<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();

if (is_post()) {
    $email = array();
    $email['mail_sendtype'] = $_POST['mailsend'];
    $email['mail_server'] = $_POST['server'];
    $email['mail_port'] = $_POST['port'];
    $email['mail_auth'] = $_POST['auth'];
    $email['mail_from'] = $_POST['from'];
    $email['mail_username'] = $_POST['username'];
    $email['mail_password'] = $_POST['password'];
    $test_from = $_POST['test_from'];
    $test_to = $_POST['test_to'];
    $ress = 1;
    foreach ($email AS $key => $one) {
        if ($one != $INI['system'][$key]) {
            $ress = $ress && DB::Update('system', $key, array('v' => $one), 'k');
        }
    }
    if ($ress === true) {
        adminmessage('2', '邮件设置成功。', WEB_ROOT . '/admin/email', true, 3);
    }
    if ($test_from != "" && $test_to != "") {
        $smtpemailto = explode(",", $test_to); //发送给谁
        $mailsubject = "测试邮件系统"; //邮件主题
        $mailbody = "<h1> 这是一个测试程序 </h1>"; //邮件内容
        $mailtype = "HTML"; //邮件格式（HTML/TXT）,TXT为文本邮
        if ($email['mail_auth'] == 1) {
            $auth = true;
        } else {
            $auth = false;
        }
        $smtp = new SMTP($email['mail_server'], $email['mail_port'], $auth, $email['mail_username'], $email['mail_password']);
        $count = 0;
        foreach ($smtpemailto AS $emailto) {
            $result = $smtp->sendmail($emailto, $test_from, $INI['system']['sitename'], $mailsubject, $mailbody, $mailtype);
            if ($result) {
                $u['from'] = $test_from;
                $u['to'] = $emailto;
                $u['send_time'] = time();
                $u['subject'] = $mailsubject;
                $u['body'] = $mailbody;
                DB::Insert('emailrecord', $u);
            }
            if ($result)
                $count++;
        }
        if ($count > 0) {
            echo '<script type="text/JavaScript"> window.alert("邮件发送成功：\n ' . $count . '封标题为 ' . $mailsubject . ' 的测试邮件已经发出，请查收。"); </script>';
        }
    }
}

include template('admin/email');
