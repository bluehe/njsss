<?php

class ZSystem {

    static public function GetINI() {
        global $INI;
        $INI = Config::Instance('php');
        $NOW = time();
//$pre = Utility::GetHost('pre');
        $url = $_SERVER['HTTP_HOST'];
        $db = new DB();
        $results = DB::LimitQuery('system', array('condition' => array(),));
        if (!empty($results)) {
            foreach ($results AS $one) {
                $SYS['system'][$one['k']] = $one['v'];
            }
            $INI = Config::MergeINI($INI, $SYS);
        }

//$BUL = Table::Fetch('system', 2);
//$BUL = Utility::ExtraDecode($BUL['value']);
//$INI = Config::MergeINI($INI, $BUL);
        $INI = ZSystem::WebRoot();
        return self::BuildINI($INI);
    }

    static public function GetUnsetINI($ini) {
        unset($ini['db']);
        unset($ini['webroot']);
        unset($ini['memcache']);
        return $ini;
    }

    static public function GetSaveINI($ini) {
        return array(
            'db' => $ini['db'],
            'memcache' => $ini['memcache'],
            'webroot' => $ini['webroot'],
        );
    }

    static private function WebRoot() {
        global $INI;
        if (defined('WEB_ROOT'))
            return $INI;

        /* validator */
        $script_name = $_SERVER['SCRIPT_NAME'];
        if (preg_match('/^(.*)/loader.php$/', $script_name, $m)) {
            $INI['webroot'] = $m[1];
            save_config('php');
        }

        if (isset($INI['webroot'])) {

            define('WEB_ROOT', $INI['webroot']);
        } else {
            $document_root = $_SERVER['DOCUMENT_ROOT'];
            $docroot = rtrim(str_replace('\\', '/', $document_root), '/');
            if (!$docroot) {
                $script_filename = $_SERVER['SCRIPT_FILENAME'];
                $script_filename = str_replace('\\', '/', $script_filename);
                $script_name = $_SERVER['SCRIPT_NAME'];
                $script_name = str_replace('\\', '/', $script_name);
                $lengthf = strlen($script_filename);
                $lengthn = strlen($script_name);
                $length = $lengthf - $lengthn;
                $docroot = rtrim(substr($script_filename, 0, $length), '/');
            }

            $webroot = trim(substr(WWW_ROOT, strlen($docroot)), '\\/');
            define('WEB_ROOT', $webroot ? "/{$webroot}" : '');
        }
        return $INI;
    }

    static private function BuildINI($ini) {
        $host = $_SERVER['HTTP_HOST'];
        $ini['system']['wwwprefix'] = "http://{$host}" . WEB_ROOT;
        $ini['system']['imgprefix'] = "http://{$host}" . WEB_ROOT;
        if (!$ini['system']['sitename']) {
            $ini['system']['sitename'] = ' ';
        }
        if (!$ini['system']['sitetitle']) {
            $ini['system']['sitetitle'] = ' ';
        }
        return $ini;
    }

    static public function GetThemeList() {
        $root = WWW_ROOT . '/themes';
        $dirs = scandir($root);
        $themelist = array('default' => 'default',);
        foreach ($dirs AS $one) {
            if (strpos($one, '.') === 0)
                continue;
            $onedir = $root . '/' . $one;
            if (is_dir($onedir))
                $themelist[$one] = $one;
        }
        return $themelist;
    }

}
