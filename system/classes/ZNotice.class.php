<?php

/**
 * @author: blue
 */
class ZNotice {

    static private $send_uid = 0;

    static public function SendNotice($send_uid = 0, $receive_uid = array(), $type, $notice) {
        settype($receive_uid, 'array');
        $m = DB::GetDbRowById('mailer', 'message_' . $type);
        $message['title'] = self::MailReplace(stripslashes($m['title']), $notice); //标题
        $message['content'] = self::MailReplace(stripslashes($m['value']), $notice); //标题
        $message['url'] = $notice['url'];
        $message['recode'] = $notice['recode'];
        $message['send_uid'] = $send_uid;
        $message['type'] = $notice['message_type'] ? $notice['message_type'] : 'business';
        foreach ($receive_uid as $r_uid) {
            $message['receive_uid'] = $r_uid;
            self::SendMessage($message);
        }
        self::SendEmail($send_uid, $receive_uid, $type, $notice);
    }

    static public function SendMessage($message = array()) {
        //站内信
        $message['send_time'] = time();
        $message['send_uid'] = isset($message['send_uid']) ? $message['send_uid'] : self::$send_uid;
        $res = DB::Insert('message', $message);
        return $res;
    }

    static public function SendEmail($send_uid = 0, $receive_uid = array(), $type, $email) {
        global $INI;
        $n = DB::GetDbRowById('mailer', 'email_' . $type);
        $email = array_merge($INI['system'], $email);
        $email['servername'] = $_SERVER['SERVER_NAME'];
        $mailsubject = self::MailReplace(stripslashes($n['title']), $email); //邮件主题
        $mailbody = self::MailReplace(stripslashes($n['value']), $email);
        $mailtype = "HTML"; //邮件格式（HTML/TXT）,TXT为文本邮
        if ($INI['system']['mail_auth'] == 1) {
            $auth = true;
        } else {
            $auth = false;
        }
        $smtp = new SMTP($INI['system']['mail_server'], $INI['system']['mail_port'], $auth, $INI['system']['mail_username'], $INI['system']['mail_password']);
        $receive_eamils = DB::GetDbColumn('user', 'email', array('user_id' => $receive_uid));
        foreach ($receive_eamils as $r_email) {
            $res = $smtp->sendmail($r_email, $INI['system']['mail_from'], $INI['system']['sitename'], $mailsubject, $mailbody, $mailtype);
            if ($res) {
                $u['user_id'] = $send_uid ? $send_uid : 0;
                $u['from'] = $INI['system']['mail_from'];
                $u['to'] = $r_email;
                $u['send_time'] = time();
                $u['subject'] = $mailsubject;
                $u['body'] = $mailbody;
                DB::Insert('emailrecord', $u);
            }
        }
        return $res;
    }

    static public function SendMail($type, $user) {
        global $INI;
        // $n = Table::Fetch('mailer', $type);
        $n = DB::GetTableRow('mailer', array('id' => $type));
        $user = array_merge($INI['system'], $user);
        $user['servername'] = $_SERVER['SERVER_NAME'];
        $mailsubject = self::MailReplace(stripslashes($n['title']), $user); //邮件主题
        $mailbody = self::MailReplace(stripslashes($n['value']), $user);
        $mailtype = "HTML"; //邮件格式（HTML/TXT）,TXT为文本邮
        if ($INI['system']['mail_auth'] == 1) {
            $auth = true;
        } else {
            $auth = false;
        }
        $smtp = new SMTP($INI['system']['mail_server'], $INI['system']['mail_port'], $auth, $INI['system']['mail_username'], $INI['system']['mail_password']);
        $res = $smtp->sendmail($user['email'], $INI['system']['mail_from'], $INI['system']['sitename'], $mailsubject, $mailbody, $mailtype);
        if ($res) {
            $u['user_id'] = Session::Get('user_id') ? Session::Get('user_id') : 0;
            $u['from'] = $INI['system']['mail_from'];
            $u['to'] = $user['email'];
            $u['send_time'] = time();
            $u['subject'] = $mailsubject;
            $u['body'] = $mailbody;
            DB::Insert('emailrecord', $u);
        }
        return $res;
    }

    static private function MailReplace($text, $user) {
        while (preg_match('/{\$([a-z0-9_]+)}/i', $text, $regs)) {
            $found = $regs[1];
            $text = preg_replace('/\{\$' . $found . '\}/i', strval($user[$found]), $text);
        }

        return $text;
    }

}

?>