<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$t = $_REQUEST['t'];
if ($t == 'setting_notice_select') {
    $title = trim($_REQUEST['title']);
    $recode = $_REQUEST['recode'];
    $start_time = $_REQUEST['start_time'];
    $end_time = $_REQUEST['end_time'];
    $by = $_REQUEST['by'] ? $_REQUEST['by'] : 'send_time';
    $order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
    $page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;
    $more = $_REQUEST['more'];
    $condition = array('type' => 'system', 'send_uid' => $login_user_id);

    if ($title) {
        $condition[] = "title LIKE '%{$title}%'";
    }
    if ($recode) {
        $condition[] = "FIND_IN_SET('$recode',recode)";
    }
    if ($start_time) {
        $start = strtotime($start_time);
        $condition[] = "send_time > $start";
    }
    if ($end_time) {
        $end = strtotime($end_time) + 86399;
        $condition[] = "send_time < $end";
    }
    $nums = count(DB::LimitQuery('message', array('select' => 'id', 'condition' => $condition, 'group' => "group by send_time,recode")));

    $messages = DB::LimitQuery('message', array('condition' => $condition,
                'offset' => $INI['system']['page_num'] * ($page + $more),
                'size' => $INI['system']['page_num'],
                'group' => "group by send_time,recode",
                'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'send_time' ? '' : ',send_time desc'),
    ));

    if ($nums <= $INI['system']['page_num'] * ($page + $more + 1)) {
        $more = 0;
    } else {
        $more++;
    }
    foreach ($messages as $key => $one) {
        $messages[$key]['send_date'] = date('Y-m-d H:i:s', $one['send_time']);
        $messages[$key]['content'] = strip_tags($one[content]);
    }
    echo $more . '^|^' . ($messages ? json_encode($messages) : '');
} elseif ($t == 'setting_user_select') {

    $username = trim($_REQUEST['username']);
    $emial = trim($_REQUEST['emial']);
    $tel = trim($_REQUEST['tel']);
    $stat = $_REQUEST['stat'];
    $by = $_REQUEST['by'] ? $_REQUEST['by'] : 'user_id';
    $order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
    $page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;
    $more = $_REQUEST['more'];
    $condition = array();

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

    $nums = DB::Count('user', $condition);
    $data = DB::LimitQuery('user', array('condition' => $condition,
                'offset' => $INI['system']['page_num'] * ($page + $more),
                'size' => $INI['system']['page_num'],
                'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'user_id' ? '' : ',user_id desc'),
    ));
    if ($nums <= $INI['system']['page_num'] * ($page + $more + 1)) {
        $more = 0;
    } else {
        $more++;
    }

    foreach ($data as $key => $one) {
        $data[$key]['reg_time'] = date('Y-m-d H:i:s', $one['reg_time']);
        $data[$key]['last_time'] = date('Y-m-d H:i:s', $one['last_time']);

        $data[$key]['stats'] = $one['stat'] == 1 ? '正常' : '<b class="red">锁定</b>';
    }
    echo $more . '^|^' . ($data ? json_encode($data) : '');
} elseif ($t == 'site_website_select') {
    $domain = trim($_REQUEST['domain']);
    $name = trim($_REQUEST['name']);
    $uid = $_REQUEST['uid'];
    $style = $_REQUEST['style'];
    $by = $_REQUEST['by'] ? $_REQUEST['by'] : 'id';
    $order = $_REQUEST['order'] ? $_REQUEST['order'] : 'desc';
    $page = $_REQUEST['page'] ? $_REQUEST['page'] : 0;
    $more = $_REQUEST['more'];
    if (checkperm('manager', false)) {
        $condition = array();
        $users = DB::GetDbColumn('user', array('user_id', 'username'), array());
    } else {
        $condition = array('uid' => $login_user_id);
    }

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

    $nums = DB::Count('website', $condition);

    $data = DB::LimitQuery('website', array('select' => 'id,domain,name,uid,logo,show_type,style,color,stat,click_count,click_num',
                'condition' => $condition,
                'offset' => $INI['system']['page_num'] * ($page + $more),
                'size' => $INI['system']['page_num'],
                'order' => 'ORDER BY ' . $by . ' ' . $order . ($by == 'id' ? '' : ',id desc'),
    ));
    if ($nums <= $INI['system']['page_num'] * ($page + $more + 1)) {
        $more = 0;
    } else {
        $more++;
    }

    foreach ($data as $key => $one) {
        if (checkperm('manager', false)) {
            $data[$key]['admin'] = 1;
            $data[$key]['username'] = $users[$one['uid']]['username'];
        } else {
            $data[$key]['admin'] = 0;
        }
        $data[$key]['show_type'] = $one['show_type'] == 'back' ? '后台' : '前台';
        $data[$key]['label'] = $one['stat'] == 1 ? 'label-success' : 'label-default';
        $data[$key]['style'] = $one['style'] == 'product' ? '产品' : ($one['style'] == 'information' ? '资讯' : '行业');
        $data[$key]['stats_b'] = $one['stat'] == 1 ? 0 : 1;
        $data[$key]['stats'] = $one['stat'] == 1 ? '启用' : '关闭';
    }
    echo $more . '^|^' . ($data ? json_encode($data) : '');
}
?>