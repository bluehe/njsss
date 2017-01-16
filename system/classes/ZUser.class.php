<?php

class ZUser {

    const SECRET_KEY = '@4!@#$%@';

    static public function GenPassword($p) {
        return md5($p . self::SECRET_KEY);
    }

    static public function Create($user_row) {

        $user_row['password'] = self::GenPassword($user_row['password']);
        $user_row['reg_ip'] = Utility::GetRemoteIp();

        $user_row['recode'] = '';
        $user_row['user_id'] = DB::Insert('user', $user_row);
        return $user_row['user_id'];
    }

    static public function CreateUser($table, $user_row) {
        $user_row['user_id'] = DB::Insert($table, $user_row);
        return $user_row['user_id'];
    }

    static public function GetUser($user_id) {
        if (!$user_id) {
            return array();
        }
        return DB::GetTableRow('user', array('user_id' => $user_id));
    }

    static public function GetLoginCookie($cname = 'zhanplus') {
        $cv = cookieget($cname);
        if ($cv) {
            $zone = base64_decode($cv);
            $p = explode('@', $zone, 2);
            return DB::GetTableRow('user', array(
                        'user_id' => $p[0],
                        'password' => $p[1],
                        'stat' => 1
            ));
        }
        return Array();
    }

    static public function GetNameCookie($cname = 'zhanplus') {
        $cv = cookieget($cname);
        if ($cv) {
            $zone = base64_decode($cv);
        }
        return $zone;
    }

    static public function GetLogin($username, $password, $en = true) {
        $password = self::GenPassword($password);
        $field = strpos($username, '@') ? 'email' : 'username';
        if ($field == 'username') {
            $field = preg_match("/^((0\d{2,3}-\d{7,8}(-\d{1,6})?)|(1[34578]\d{9}))$/", $username) ? 'tel' : 'username';
        }
        if ($en) {
            $condition = array($field => $username, 'password' => $password, 'stat' => 1);
        } else {
            $condition = array($field => $username, 'password' => $password,);
        }
        return DB::GetTableRow('user', $condition);
    }

    static public function GetLoguser($username, $en = true) {
        $field = strpos($username, '@') ? 'email' : 'username';
        if ($field == 'username') {
            $field = preg_match("/^((0\d{2,3}-\d{7,8}(-\d{1,6})?)|(1[34578]\d{9}))$/", $username) ? 'tel' : 'username';
        }
        if ($en) {
            $condition = array($field => $username, 'stat' => 1);
        } else {
            $condition = array($field => $username,);
        }
        return DB::GetTableRow('user', $condition);
    }

}
