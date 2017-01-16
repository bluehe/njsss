<?php

/**
 * @author: shwdai@gmail.com
 */
class ZLogin {

    static public $cookie_name = 'zhanplus';

    /*
      function ZLogin(){
      global $INI;
      self::$cookie_name = isset($INI['pre']['url'])?$INI['pre']['url']:'ds9b';
      }
     */

    static public function GetLoginId() {
        $user_id = abs(intval(Session::Get('user_id')));
        if (!$user_id) {
            $u = ZUser::GetLoginCookie(self::$cookie_name);
            $user_id = abs(intval($u['user_id']));
        }
        if ($user_id)
            self::Login($user_id);
        return $user_id;
    }

    static public function Login($user_id, $content = '') {
        Session::Set('user_id', $user_id);
        Session::Set('begtime', time());
        self::Log($user_id, $content);
        return true;
    }

    static public function Log($user_id, $content = '') {
        $user_log = array(
            'log_time' => time(),
            'user_id' => $user_id, //ZLogin::GetLoginId(),
            'log_info' => $content,
            'log_ip' => $_SERVER["REMOTE_ADDR"],
        );
        DB::Insert('log', $user_log);
    }

    static public function NeedLogin() {
        $user_id = self::GetLoginId();
        return $user_id ? $user_id : False;
    }

    static public function Remember($username) {
        $zone = "{$username}";
        cookieset(self::$cookie_name, base64_encode($zone));
    }

    static public function NoRemember() {
        cookieset(self::$cookie_name, null, -1);
    }

}

?>