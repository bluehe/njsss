<?php

/* import other */
import('current');

//import('function.inc');

function check_battery() {
    $batterys = DB::LimitQuery('battery', array('condition' => array('stat' => 1)));
    $cids = DB::GetDbColumn('battery', 'category', array('stat' => 1));  //分类id
    $sids = DB::GetDbColumn('battery', 'site_id', array('stat' => 1));  //站点ID

    $cuid = DB::GetDbColumn('category', array('id', 'uid'), array(), true);
    $suid = DB::GetDbColumn('category', array('id', 'uid'), array(), true);

    foreach ($batterys as $b) {
        if ($cuid[$b['category']] != $suid[$b['site_id']] && $cuid[$b['category']] != -$b['site_id']) {
            DB::Update('battery', $b['id'], array('stat' => 0));
        }
    }
}

function format_bytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++)
        $size /= 1024;
    return round($size, 2) . $units[$i];
}

function get_domain($url) {
    preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $matches);
    return $matches[2];
}

function connect($url) {
    $website = DB::GetTableRow('website', array('domain' => $url));
    $found = 'red';
    if ($website) {
        $timeout = array(
            'http' => array(
                'timeout' => 5 //设置一个超时时间，单位为秒
            )
        );
        $ctx = stream_context_create($timeout);
        $config = file_get_contents('http://' . $url . '/config.json', 0, $ctx);
        $s = json_decode($config, TRUE);
        if ($s['system'] == 'zhanplus') {
            $version = ZUpdate::GetUpdate($s['version']);
            if ($version) {
                $s['version'].=' <a data-code="' . md5($website[domain] . $website[update_time]) . '">[升级至' . $version['new_version'] . ']</a>';
            }
            $found = 'green|' . json_encode(array('version' => $s['version'], 'template' => $s['template'][$website[style]][$website[template]][title]), 256);
        } else {
            $curl = curl_init($url . '?auto=1');
            //不取回数据
            curl_setopt($curl, CURLOPT_NOBODY, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout['http']['timeout']);
            //发送请求
            $result = curl_exec($curl);

            if ($result !== false) {
                $found = 'yellow';
            }
        }
    }
    return $found;
}

function connects($url) {
    $website = DB::GetTableRow('website', array('domain' => $url));
    $found = 'red';
    if ($website) {

        $curl = curl_init($url . '?c = 1');
        //不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        //发送请求
        $result = curl_exec($curl);

        if ($result !== false) {
            //检查http响应码是否为200
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                $found = 'yellow';
                $res = ZUpdate::CheckRemoteFile('http://' . $url . '/config.json');
                if ($res) {
                    $config = file_get_contents('http://' . $url . '/config.json');
                    $s = json_decode($config, TRUE);
                    if ($s['system'] == 'zhanplus') {
                        $version = ZUpdate::GetUpdate($s['version']);
                        if ($version) {
                            $s['version'].=' <a data-code="' . md5($website[domain] . $website[update_time]) . '">[升级至' . $version['new_version'] . ']</a>';
                        }
                        $found = 'green|' . json_encode(array('version' => $s['version'], 'template' => $s['template'][$website[style]][$website[template]][title]), 256);
                    }
                }
            }
        }
        curl_close($curl);
    }
    return $found;
}

//友情链接
function links() {
    global $INI;
    $results = DB::LimitQuery('link', array('condition' => array('stat' => 1,),
                'order' => 'ORDER BY displayorder ASC',));
    return $results;
}

//判断 手机-----------------------------------------------------------------
function isMobile() {
// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;

//此条摘自TPM智能切换模板引擎，适合TPM开发
    if (isset($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
        return true;
//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA']))
//找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
//判断手机发送的客户端标志,兼容性有待提高
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );
//从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
//协议法，因为有可能不准确，放到最后判断
    if (isset($_SERVER['HTTP_ACCEPT'])) {
// 如果只支持wml并且不支持html那一定是移动设备
// 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

function company_display($str) {
    global $INI;
    if ($INI['system']['company_display'] == 'Y') {
        return $str;
    } else {
        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, -4, 4, 'utf-8');
            return '****' . $newstr;
        } else {
            return '****' . GBsubstr($str, -12, 12);
        }
    }
}

function GBsubstr($string, $start, $length) {

    if (strlen($string) > $length) {
        $str = null;
        $len = $start + $length;
        for ($i = $start; $i < $len; $i++) {
            if (ord(substr($string, $i, 1)) > 0xa0) {
                $str.=substr($string, $i, 2);
                $i++;
            } else {
                $str.=substr($string, $i, 1);
            }
        }
        return $str;
    } else {
        return $string;
    }
}

function downFileFromServer($showFileName, $downFilePath) {
    if (file_exists($downFilePath)) {
        if (is_readable($downFilePath)) {
//            $file = fopen($downFilePath, "r"); // 打开文件
//// 输入文件标签
//            Header("Content-type: application/octet-stream");
//            Header("Accept-Ranges: bytes");
//            Header("Accept-Length: " . filesize($downFilePath));
//            Header("Content-Disposition: attachment; filename=" . $showFileName);
//// 输出文件内容
//            echo fread($file, filesize($downFilePath));
//            fclose($file);
            if (trim($showFileName) == '') {
                $showFileName = 'undefined';
            }
            ob_start();
            ob_clean();
            $file_size = filesize($downFilePath);
            header('Content-Encoding:none');
            header('Cache-Control:private');
            header('Content-Length:' . $file_size);
            if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
                $iefilename = preg_replace('/\./', '%2e', $showFileName, substr_count($showFileName, '.') - 1);
                header("content-disposition:attatchment;
filename = " . $iefilename);
            } else {
                header("content-disposition:attatchment;
filename = " . $showFileName);
            }
            header('Content-Disposition:attachment; filename=' . $showFileName);
            header('Content-Type:application/octet-stream');
            @readfile($downFilePath);
            ob_flush();
        }
    }
}

function getCity($ip) {

    $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
    $ip = json_decode(file_get_contents($url));
    if ((string) $ip->code == '1') {
        return false;
    }
    $data = (array) $ip->data;
    return $data;
}

function mail_domain($email) {
    $email = strtolower($email);
    $email_domain = array(
        'qq.com' => 'http://mail.qq.com',
        '163.com' => 'http://mail.163.com',
        '126.com' => 'http://mail.126.com');
    $domain = substr($email, strrpos($email, '@') + 1);
    if (array_key_exists($domain, $email_domain)) {
        return $email_domain[$domain];
    } else {
        return 'http://mail.' . $domain;
    }
}

function checkauth() {
    $varArray = func_get_args();     //获取参数，返回参数数组
    $user_id = abs(intval(Session::Get('user_id')));
    $company = DB::GetTableRow('v_usercompany', array('id' => $user_id));
    $note = array('company' => '企业认证', 'tel' => '电话', 'email' => '邮箱', 'sdt' => '实地通');
    foreach ($varArray as $value) {
        if ($company['auth_' . $value] != 'Y') {
            showmessage('error', '失败', '您还没有进行 <b class=red>' . $note[$value] . '</b> 认证，请先完成相应认证！');
            Utility::Redirect(WEB_ROOT . '/public');
        }
    }
    return true;
}

function checkperm($action = null, $jump = true, $logic = 'OR') {
    settype($action, 'array');
    $user_id = abs(intval(Session::Get('user_id')));
    if ($logic == 'AND') {
        $r = true;
        foreach ($action as $a) {
            $role = DB::GetDbColumn('auth_item_child', 'parent', array('child' => $a));
            $r = $r && DB::Exist("auth_assignment", array('user_id' => $user_id, 'item_name' => $role));
        }
    } else {
        $role_per = DB::GetDbColumn('auth_item_child', 'parent', array('child' => $action));
        $r = DB::Exist("auth_assignment", array('user_id' => $user_id, 'item_name' => $role_per));
    }

    if ($r) {
        return true;
    } else {
        if ($jump) {
            showmessage('error', '失败', '您没有相应权限！');
            echo "<script>history.go(-1);</script>";
            exit;
        }
        return false;
    }
}

function checkuid($action = null, $logic = 'OR') {
    settype($action, 'array');
    $uid = array();
    if ($logic == 'AND') {
        foreach ($action as $k => $a) {
            if ($k == 0) {
                $uid = checkuid($a);
            } else {
                $uid = array_intersect($uid, checkuid($a));
            }
            if (empty($uid)) {
                break;
            }
        }
    } else {
        $role_per = DB::GetDbColumn('auth_item_child', 'parent', array('child' => $action));
        $uid = DB::GetDbColumn('auth_assignment', 'user_id', array('item_name' => $role_per));
    }
    return array_unique($uid);
}

function listdir($dir = "", $files = array(), $pre = '') {
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ((is_dir($dir . "/" . $file)) && $file != "." && $file != "..") {
                    $files = listdir($dir . "/" . $file, $files, $pre ? $pre . '/' . $file : $file);
                } else {
                    if ($file != "." && $file != "..") {
                        $files[] = $pre ? $pre . '/' . $file : $file;
                    }
                }
            }
            closedir($dh);
            return $files;
        }
    }
}

function deldir($dir = "") {
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
    closedir($dh);
    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}

function showmessage($mtype = 'error', $title = '', $message = '') {

    Session::Set('notice', true);
    Session::Set('message_type', $mtype);
    Session::Set('title', $title);
    Session::Set('message', $message);
}

function adminmessage($mtype = '2', $message = '', $url = "", $jump = false, $second = 3) {
    if ($jump) {
        if ($url == "") {
            $url = $_SERVER['HTTP_REFERER'];
        }
        $message_url = "<p class=\"marginbot\"><a href=\"$url\" class=\"lightlink\">如果您的浏览器没有自动跳转，请点击这里</a></p><script type=\"text/JavaScript\">setTimeout(\"window.location.href ='$url';\", " . ($second * 1000) . ");</script>";
    } elseif ($url != "") {
        $message_url = "<p class=\"marginbot\"><a href=\"$url\" class=\"lightlink\">请点击这里返回上一页</a></p>";
    }
    Session::Set('message_type', $mtype);
    Session::Set('message', $message);
    Session::Set('message_url', $message_url);
    Utility::Redirect(WEB_ROOT . '/admin/message');
}

function template($tFile) {
    global $INI;

//    if (0 === strpos($tFile, 'manage')) {
//        return __template($tFile);
//    }
    if ($INI['system']['skin']) {
        $templatedir = $INI['system']['skin'];
    } else {
        $templatedir = 'default';
    }
    return __template($templatedir . '/html/' . $tFile);
}

function json($data, $type = 'eval') {
    $type = strtolower($type);
    $allow = array('eval', 'alert', 'updater', 'dialog', 'mix', 'refresh');
    if (false == in_array($type, $allow))
        return false;
    Output::Json(array('data' => $data, 'type' => $type,));
}

function redirect($url = null, $second = 1, $jump = false, $values = '') {
    if ($jump) {
        if ($url) {
            $message = "<a href=\"$url\">你已经安全退出了$values</a><script>setTimeout(\"window.location.href ='$url';\", " . ($second * 1000) . ");</script>";
        }
        include template('showmessage');
    } else {
        header("Location: {$url}");
        exit;
    }
}

function write_php_file($array, $filename = null) {
    $v = "<?php \r\n\$INI = ";
    $v .= var_export($array, true);
    $v .=";
\r\n
?>";
    return file_put_contents($filename, $v);
}

function write_ini_file($array, $filename = null) {
    $ok = null;
    if ($filename) {
        $s = ";;;;;;;;;;;;;;;;;;\r\n";
        $s .= ";; SYS_INIFILE\r\n";
        $s .= ";;;;;;;;;;;;;;;;;;\r\n";
    }
    foreach ($array as $k => $v) {
        if (is_array($v)) {
            if ($k != $ok) {
                $s .= "\r\n[{$k}]\r\n";
                $ok = $k;
            }
            $s .= write_ini_file($v);
        } else {
            if (trim($v) != $v || strstr($v, "["))
                $v = "\"{$v}\"";
            $s .= "$k = \"{$v}\"\r\n";
        }
    }

    if (!$filename)
        return $s;
    return file_put_contents($filename, $s);
}

function save_config($type = 'ini') {
    global $INI;
    $q = ZSystem::GetSaveINI($INI);
    if (strtoupper($type) == 'INI') {
        if (!is_writeable(SYS_INIFILE))
            return false;
        return write_ini_file($q, SYS_INIFILE);
    }
    if (strtoupper($type) == 'PHP') {
        if (!is_writeable(SYS_PHPFILE))
            return false;
        return write_php_file($q, SYS_PHPFILE);
    }
    return false;
}

// user relative
function need_login() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
    Session::Set('login_url', $_SERVER['REQUEST_URI']);
    return redirect(WEB_ROOT . '/account/login');
}

function need_user($user = 'member', $force = false) {
    if (isset($_SESSION['user_id'])) {
        $m = DB::GetTableRow('user', array('user_id' => $_SESSION['user_id']));
        if ($m['usertype'] == $user) {
            return $_SESSION['user_id'];
        } else {
            showmessage('error', '错误', '您没有访问权限！');
            return Utility::Redirect();
        }
    }
    return redirect(WEB_ROOT . '/account/login');
}

function need_manager() {
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1) {
        return $_SESSION['user_id'];
    }
    $url = $_SERVER['REQUEST_URI'];
    Session::Set('login_url', $url);
    return redirect(WEB_ROOT . '/account/login');
}

function need_auth($b = true, $a = true) {
    if ($b)
        return true;
    if ($a)
        json('无权操作', 'alert');
    Session::Set('error', '无权操作');
    redirect(WEB_ROOT . '/account/login');
}

function is_manager() {
    global $login_user;
    return ($login_user['user_id'] == 1);
}

function is_get() {
    return !is_post();
}

function is_post() {
    return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
}

function is_login() {
    return isset($_SESSION['user_id']);
}

function cookieset($k, $v, $expire = 0) {
    $pre = substr(md5($_SERVER['HTTP_HOST']), 0, 4);
    $k = "{$pre}_{$k}";
    if ($expire == 0) {
        $expire = time() + 365 * 86400;
    } else {
        $expire += time();
    }
    setCookie($k, $v, $expire, '/');
}

function cookieget($k) {
    $pre = substr(md5($_SERVER['HTTP_HOST']), 0, 4);
    $k = "{$pre}_{$k}";
    return strval($_COOKIE[$k]);
}

/*
  function moneyit($k) {
  return rtrim(rtrim(sprintf('%.2f',$k), '0'), '.');
  } */

function debug($v, $e = false) {
    global $login_user_id;
    if ($login_user_id == 1) {
        echo "<pre>";
        var_dump($v);
        if ($e)
            exit;
    }
}

function getparam($index = 0, $default = 0) {
    if (is_numeric($default)) {
        $v = abs(intval($_GET['param'][$index]));
    } else
        $v = strval($_GET['param'][$index]);
    return $v ? $v : $default;
}

function getpage() {
    $c = abs(intval($_GET['page']));
    return $c ? $c : 1;
}

function pagestring($count, $pagesize) {
    $p = new Pager($count, $pagesize, 'page');
    return array($pagesize, $p->offset, $p->genBasic());
}

function uencode($u) {
    return base64_encode(urlEncode($u));
}

function udecode($u) {
    return urlDecode(base64_decode($u));
}

?>