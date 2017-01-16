<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$t = $_REQUEST['t'];
if ($t == 'setting_user_select') {
    $by = $_REQUEST['by'] ? $_REQUEST['by'] : 'user_id';
    $order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';

    $condition = array();
    if (isset($_REQUEST['id'])) {
        $ids = explode(',', $_REQUEST['id']);
        $condition['user_id'] = $ids;
    } else {
        $username = trim($_REQUEST['username']);
        $emial = trim($_REQUEST['emial']);
        $tel = trim($_REQUEST['tel']);
        $stat = $_REQUEST['stat'];
        if ($username) {
            $condition[] = "username LIKE '%{$usernmae}%'";
        }
        if ($emial) {
            $condition[] = "emial LIKE '%{$emial}%'";
        }
        if ($tel) {
            $condition[] = "tel LIKE '%{$tel}%'";
        }
        if (!is_null($stat) && $stat !== '') {
            $condition['stat'] = $stat;
        }
    }
    $nums = DB::Count('user', $condition);
    $data = DB::LimitQuery('user', array('condition' => $condition, 'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'user_id' ? '' : ',user_id desc'),));
    if ($data) {
        $titles = array('head' => '用户列表',
            'th' => array(array('value' => 'username', 'name' => '用户名', 'width' => 15),
                array('value' => 'email', 'name' => '电子邮箱', 'width' => 25),
                array('value' => 'tel', 'name' => '联系电话', 'width' => 18),
                array('value' => 'reg_ip', 'name' => '注册IP', 'width' => 15),
                array('value' => 'reg_time', 'name' => '注册时间', 'width' => 22),
                array('value' => 'last_time', 'name' => '最近登录', 'width' => 22),
                array('value' => 'stats', 'name' => '状态', 'width' => 8),
        ));

        foreach ($data as $key => $one) {
            $data[$key]['reg_time'] = date('Y-m-d H:i:s', $one['reg_time']);
            $data[$key]['last_time'] = date('Y-m-d H:i:s', $one['last_time']);
            $data[$key]['stats'] = $one['stat'] == 1 ? '正常' : '锁定';
        }
        include_once(dirname(__FILE__) . '/excel_list.php');
    } else {
        showmessage('error', '', '不存在相关内容！');
        Utility::Redirect();
    }
} elseif ($t == 'site_website_select') {
    set_time_limit(120);
    $s_time = time();
    $r = $_REQUEST['r'];
    $by = $_REQUEST['by'] ? $_REQUEST['by'] : 'id';
    $order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';

    if (checkperm('manager',false)) {
        $condition = array();
        $users = DB::GetDbColumn('user', array('user_id', 'username'), array());
    } else {
        $condition = array('uid' => $login_user_id);
    }
    if (isset($_REQUEST['id'])) {
        $ids = explode(',', $_REQUEST['id']);
        $condition['id'] = $ids;
    } else {

        $domain = trim($_REQUEST['domain']);
        $name = trim($_REQUEST['name']);
        $uid = $_REQUEST['uid'];
        $style = $_REQUEST['style'];

        if ($domain) {
            $condition[] = "domain LIKE '%{$domain}%'";
        }
        if ($name) {
            $condition[] = "name LIKE '%{$name}%'";
        }
        if ($uid) {
            $condition['uid'] = $uid;
        }
        if ($style) {
            $condition['style'] = $style;
        }
    }

    $data = DB::LimitQuery('website', array('select' => 'id,domain,name,uid,logo,show_type,style,color,stat,click_count,click_num', 'condition' => $condition, 'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'id' ? '' : ',id desc'),));

    if ($data) {

        Session::Set('load_num_' . $r, count($data));
        Session::Set('load_i', 0);
        session_write_close();
        $titles = array('head' => '站点列表',
            'th' => array(array('value' => 'domain', 'name' => '域名', 'width' => 20),
                array('value' => 'name', 'name' => '名称', 'width' => 20),
                array('value' => '', 'name' => 'LOGO', 'width' => 12, 'style' => array('image' => 'logo', 'width' => 84, 'height' => 24)),
                array('value' => 'show_type', 'name' => '加载方式', 'width' => 12),
                array('value' => 'style', 'name' => '类型', 'width' => 8),
//                array('value' => 'click_count', 'name' => '访问次数', 'width' => 8),
//                array('value' => 'click_num', 'name' => '访问人数', 'width' => 8),
                array('value' => 'template', 'name' => '模板', 'width' => 12),
                array('value' => '', 'name' => '颜色', 'width' => 6, 'style' => array('backagecolor' => 'backagecolor')),
                array('value' => 'stats', 'name' => '状态', 'width' => 8),
                array('value' => 'connect', 'name' => '通讯', 'width' => 6, 'style' => array('backagecolor' => 'connectcolor')),
                array('value' => 'version', 'name' => '版本', 'width' => 13),
                array('value' => 'ip', 'name' => '解析', 'width' => 26),
        ));
        if (checkperm('manager',false)) {
            array_unshift($titles['th'], array('value' => 'username', 'name' => '用户', 'width' => 10));
        }
        $webhosts = DB::GetDbColumn('service', array('ip', 'name'), array('stat' => 1), true);
//        $webhosts = array('103.59.146.99' => '华扬建材(103.59.146.99)', '103.59.146.253' => '数字资产(103.59.146.253)', '43.240.30.55' => '爱车服(43.240.30.55)', '45.119.98.143' => '云主机(45.119.98.143)', '45.119.98.144' => '云主机(45.119.98.144)', '45.119.98.145' => '云主机(45.119.98.145)');

        foreach ($data as $key => $one) {
            if (checkperm('manager',false)) {
                $data[$key]['username'] = $users[$one['uid']]['username'];
            }
            $data[$key]['logo'] = dirname(dirname(__FILE__)) . $one['logo'];
            $data[$key]['show_type'] = $one['show_type'] == 'back' ? '后台' : '前台';
            $data[$key]['style'] = $one['style'] == 'product' ? '产品' : ($one['style'] == 'information' ? '资讯' : '行业');
            $data[$key]['backagecolor'] = 'ff' . $one['color'];
            $data[$key]['stats'] = $one['stat'] == 1 ? '启用' : '关闭';
            $status = explode('|', connect($one['domain']));
            $data[$key]['connectcolor'] = $status[0] == 'green' ? 'FF008000' : ($status[0] == 'yellow' ? 'FFFF8C00' : 'FFFF0000');
            $s = json_decode($status[1], true);
            $data[$key]['version'] = preg_replace('/<[^>]*>/', '', $s['version']);
            $data[$key]['template'] = $s['template'];
            $ip = gethostbyname($one['domain']);
            $data[$key]['ip'] = array_key_exists($ip, $webhosts) ? $webhosts[$ip] : $ip;

            session_start();
            Session::Set('load_i', $key + 1);
            session_write_close();
            $e_time = time();
            if ($e_time - $s_time > 100) {
                session_start();
                showmessage('error', '处理时间过长', '请分批下载数据！');
                Utility::Redirect();
            }
        }
        include_once(dirname(__FILE__) . '/excel_list.php' );
    } else {
        showmessage('error', '', '不存在相关内容！');
        Utility::Redirect();
    }
} elseif ($t == 'excel_progress') {
    $num = Session::Get('load_num');
    $i = Session::Get('load_i');
    echo $i . '|' . $num;
} else {
    showmessage('error', '', '不存在相关内容！');
    Utility::Redirect();
}
?>