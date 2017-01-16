<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$form_type = $_REQUEST['form_type'];
if ($form_type == 'get_connect') {
    session_write_close();
    //通讯
    $domain = $_REQUEST['domain'];
    $status = connect($domain);
    $webhosts = DB::GetDbColumn('service', array('ip', 'name'), array('stat' => 1), true);
//    $webhosts = array('103.59.146.99' => '华扬建材(103.59.146.99)', '103.59.146.253' => '数字资产(103.59.146.253)', '43.240.30.55' => '爱车服(43.240.30.55)', '45.119.98.143' => '云主机(45.119.98.143)', '45.119.98.144' => '云主机(45.119.98.144)', '45.119.98.145' => '云主机(45.119.98.145)');
    $ip = gethostbyname($domain);
    $ip = array_key_exists($ip, $webhosts) ? $webhosts[$ip] : $ip;
    echo $ip . '|' . $status;
} elseif ($form_type == 'website_style') {
    //站点设置加载类型
    $uid = $_REQUEST['uid'];
    $id = $_REQUEST['id'];
    $u = DB::GetTableRow('website', array('id' => $id));
    $style = array();
    if (in_array($uid, checkuid('product'))) {
        if ($u['style'] == 'product') {
            $style[] = array('id' => 'product', 'name' => '产品', 'check' => 'checked');
        } else {
            $style[] = array('id' => 'product', 'name' => '产品', 'check' => '');
        }
    }
    if (in_array($uid, checkuid('information'))) {
        if ($u['style'] == 'information') {
            $style[] = array('id' => 'information', 'name' => '资讯', 'check' => 'checked');
        } else {
            $style[] = array('id' => 'information', 'name' => '资讯', 'check' => '');
        }
    }
    if (in_array($uid, checkuid('industry'))) {

        if ($u['style'] == 'industr') {
            $style[] = array('id' => 'industry', 'name' => '行业', 'check' => 'checked');
        } else {
            $style[] = array('id' => 'industry', 'name' => '行业', 'check' => '');
        }
    }
    if (in_array($uid, checkuid('battery'))) {

        if ($u['style'] == 'battery') {
            $style[] = array('id' => 'battery', 'name' => '电瓶', 'check' => 'checked');
        } else {
            $style[] = array('id' => 'battery', 'name' => '电瓶', 'check' => '');
        }
    }

    echo 'success^$^' . json_encode($style);
} elseif ($form_type == 'website_link') {
    //站点设置加载链接
    $uid = $_REQUEST['uid'];
    $id = $_REQUEST['id'];
    $u = DB::GetTableRow('website', array('id' => $id));
    $u_link = explode(',', $u['link']);
    $links = DB::LimitQuery('link', array('condition' => array('uid' => $uid, 'stat' => 1), 'order' => 'ORDER BY displayorder asc,id desc'));

    foreach ($links as $key => $link) {
        if (in_array($link['id'], $u_link)) {
            $links[$key]['check'] = 'checked';
        } else {
            $links[$key]['check'] = '';
        }
    }
    echo 'success^$^' . json_encode($links);
} elseif ($form_type == 'website_nav') {
    //站点设置加载链接
    $uid = $_REQUEST['uid'];
    $id = $_REQUEST['id'];
    $u = DB::GetTableRow('website', array('id' => $id));
    $u_nav = explode(',', $u['nav']);
    $navs = DB::LimitQuery('nav', array('select' => 'id,name', 'condition' => array('uid' => $uid, 'stat' => 1), 'order' => 'ORDER BY displayorder asc,id desc'));

    foreach ($navs as $key => $nav) {
        if (in_array($nav['id'], $u_nav)) {
            $navs[$key]['check'] = 'checked';
        } else {
            $navs[$key]['check'] = '';
        }
    }
    echo 'success^$^' . json_encode($navs);
} elseif ($form_type == 'website_banner') {
    //站点设置加载链接
    $uid = $_REQUEST['uid'];
    $id = $_REQUEST['id'];
    $u = DB::GetTableRow('website', array('id' => $id));
    $u_banner = explode(',', $u['banner']);
    $banners = DB::LimitQuery('banner', array('select' => 'id,logo,name', 'condition' => array('uid' => $uid, 'stat' => 1), 'order' => 'ORDER BY displayorder asc,id desc'));

    foreach ($banners as $key => $banner) {
        if (in_array($banner['id'], $u_banner)) {
            $banners[$key]['check'] = 'checked';
        } else {
            $banners[$key]['check'] = '';
        }
    }
    echo 'success^$^' . json_encode($banners);
} elseif ($form_type == 'website_sidebar') {
    //站点设置加载链接
    $uid = $_REQUEST['uid'];
    $id = $_REQUEST['id'];
    $u = DB::GetTableRow('website', array('id' => $id));
    $u_sidebar = explode(',', $u['sidebar']);
    $sidebars = DB::LimitQuery('sidebar', array('select' => 'id,name', 'condition' => array('uid' => $uid, 'stat' => 1), 'order' => 'ORDER BY displayorder asc,id desc'));

    foreach ($sidebars as $key => $sidebar) {
        if (in_array($sidebar['id'], $u_sidebar)) {
            $sidebars[$key]['check'] = 'checked';
        } else {
            $sidebars[$key]['check'] = '';
        }
    }
    echo 'success^$^' . json_encode($sidebars);
} elseif ($form_type == 'website_company') {
    //站点设置加载链接
    $uid = $_REQUEST['uid'];
    $id = $_REQUEST['id'];
    $u = DB::GetTableRow('website', array('id' => $id));

    $companys = DB::LimitQuery('company', array('select' => 'id,name', 'condition' => array('uid' => $uid, 'stat' => 1), 'order' => 'ORDER BY id desc'));

    foreach ($companys as $key => $company) {
        if ($company['id'] == $u['company']) {
            $companys[$key]['check'] = 'checked';
        } else {
            $companys[$key]['check'] = '';
        }
    }
    echo 'success^$^' . json_encode($companys);
} elseif ($form_type == 'site_website') {
//站点信息
    $id = $_REQUEST['id'];
    $website = $_REQUEST['website'];
    $website['domain'] = Utility::GetHost('all', $website['domain']);
    $website['show_company'] = $website['show_company'] ? 1 : 0;
    $website['show_contact'] = $website ['show_contact'] ? 1 : 0;
    $website['show'] = implode(',', $website ['show']);
    $website['link'] = implode(',', $website['link']);
    $website ['nav'] = implode(',', $website['nav']);
    $website['banner'] = implode(',', $website['banner']);
    $website['sidebar'] = implode(',', $website['sidebar']);
    $website['caser'] = implode(',', $website['caser']);
    $website['statcode'] = stripslashes($website['statcode']);
    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('website', array('id' => $id));
    } else {
        $u = DB::GetTableRow('website', array('id' => $id, 'uid' => $login_user_id));
        $website['uid'] = $login_user_id;
    }
    if ($website['stat'] == '') {
        echo 'error|请选择状态！';
        exit;
    }
    if ($website['uid'] == '') {
        echo 'error|请选择用户！';
        exit;
    }
    if ($website ['show_type'] == '') {
        echo 'error|请选择加载方式！';
        exit;
    }
    if ($website['style'] == '') {
        echo 'error|请选择类型！';
        exit;
    }
    if ($website['color'] == '' || !preg_match("/^[0-9A-Fa-f]{6}$/", $website['color'])) {
        echo 'error|请输入颜色色值！';
        exit;
    }
    if ($website['template'] == '') {
        echo 'error|请选择模板！';
        exit;
    } else if ($website['template'] != 'default') {
        $templates = json_decode(file_get_contents('http://' . $website['domain'] . '/config.json'), true);
        if (!in_array($website['template'], array_keys($templates['template'][$website['style']]))) {
            echo 'error|请选择模板！';
            exit;
        }
    }
    if ($website['company'] == '') {
        echo 'error|请选择公司！';
        exit;
    }
    if ($website['style'] == 'battery' && $website['area'] == '') {
        echo 'error|请填写区域！';
        exit;
    }
    if ($website['domain'] == '') {
        echo 'error|请填写域名！';
        exit;
    }
    if ($u) {
        $w = DB::GetTableRow('website', array('domain' => $website['domain'], "id!=" . $id));
    } else {
        $w = DB::GetTableRow('website', array('domain' => $website['domain']));
    }
    if ($w) {
        echo 'error|域名已经存在！';
        exit;
    }
    if ($website['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($website, $u);
        if (!empty($change)) {
            $res = DB::Update('website', $id, $change, 'id');
            if (array_key_exists('uid', $change) && $u['style'] == 'battery') {
                //电瓶类用户更改检查电瓶用户匹配情况
                check_battery();
            }
            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $id = DB::Insert('website', $website);
        if ($id) {
            $link = array('uid' => $website['uid'], 'displayorder' => 0, 'stat' => 1, 'name' => $website['name'], 'url' => $website['domain'], 'logo' => $website['logo'], 'description' => $website['title']);
            DB::Insert('link', $link);
            showmessage('success', '', '站点添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/site_website?id=' . $id;
        } else {
            echo 'error|站点添加失败！';
        }
    }
} elseif ($form_type == 'website_goods') {
    //产品管理
    $id = $_REQUEST['id'];
    $goods = $_REQUEST['goods'];
    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('goods', array('id' => $id));
    } else {
        $site_id = DB::GetDbColumn('website', 'id', array('uid' => $login_user_id));
        $site_id[] = "-$login_user_id";
        $u = DB::GetTableRow('goods', array('id' => $id, 'site_id' => $site_id));
    }
    if ($goods['site_id'] == '') {
        echo 'error|请选择站点！';
        exit;
    }

    if ($goods['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
    if ($goods['displayorder'] == '') {
        echo 'error|请填写排序！';
        exit;
    }
    if ($goods['pic'] == '') {
        echo 'error|请上传图片！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($goods, $u);
        if (!empty($change)) {
            $res = DB::Update('goods', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $goods['add_uid'] = $login_user_id;
        $goods['add_time'] = time();
        $id = DB::Insert('goods', $goods);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/website_goods?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'website_battery') {
    //产品管理
    $id = $_REQUEST['id'];
    $battery = $_REQUEST['battery'];
    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('battery', array('id' => $id));
    } else {
        $site_id = DB::GetDbColumn('website', 'id', array('uid' => $login_user_id));
        $site_id[] = "-$login_user_id";
        $u = DB::GetTableRow('battery', array('id' => $id, 'site_id' => $site_id));
    }
    if ($battery['site_id'] == '') {
        echo 'error|请选择站点！';
        exit;
    }

    if ($battery['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
    if ($battery['category'] == '') {
        echo 'error|请选择类别！';
        exit;
    }
    if ($battery['displayorder'] == '') {
        echo 'error|请填写排序！';
        exit;
    }
    if ($battery['pic'] == '') {
        echo 'error|请上传图片！';
        exit;
    }
    if ($battery['content'] == '') {
        echo 'error|请填写内容！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($battery, $u);
        if (!empty($change)) {
            $res = DB::Update('battery', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $battery['add_uid'] = $login_user_id;
        $battery['add_time'] = time();
        $id = DB::Insert('battery', $battery);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/website_battery?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'website_industry') {
    //行业管理
    $id = $_REQUEST['id'];
    $industry = $_REQUEST['industry'];
    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('industry', array('id' => $id));
    } else {
        $site_id = DB::GetDbColumn('website', 'id', array('uid' => $login_user_id));
        $site_id[] = "-$login_user_id";
        $u = DB::GetTableRow('industry', array('id' => $id, 'site_id' => $site_id));
    }
    if ($industry['site_id'] == '') {
        echo 'error|请选择站点！';
        exit;
    }

    if ($industry['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
    if ($industry['description'] == '') {
        echo 'error|请填写描述！';
        exit;
    }
    if ($industry['displayorder'] == '') {
        echo 'error|请填写排序！';
        exit;
    }


    if ($u) {
        $change = array_diff_assoc($industry, $u);
        if (!empty($change)) {
            $res = DB::Update('industry', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $industry['add_uid'] = $login_user_id;
        $industry['add_time'] = time();
        $id = DB::Insert('industry', $industry);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/website_industry?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'website_article') {
    //文章管理
    $id = $_REQUEST['id'];
    $article = $_REQUEST['article'];
    $article['send_time'] = strtotime($article['send_time']);
    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('article', array('id' => $id));
    } else {
        $site_id = DB::GetDbColumn('website', 'id', array('uid' => $login_user_id));
        $site_id[] = "-$login_user_id";
        $u = DB::GetTableRow('article', array('id' => $id, 'site_id' => $site_id));
    }
    if ($article['site_id'] == '') {
        echo 'error|请选择站点！';
        exit;
    }

    if ($article['title'] == '') {
        echo 'error|请填写标题！';
        exit;
    }
    if ($article['description'] == '') {
        echo 'error|请填写描述！';
        exit;
    }
    if ($article['send_time'] <= 0) {
        echo 'error|请填写发布时间！';
        exit;
    }
//    if ($article['cover'] == '') {
//        echo 'error|请上传缩略图！';
//        exit;
//    }
    if ($article['content'] == '') {
        echo 'error|请填写内容！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($article, $u);
        if (!empty($change)) {
            $res = DB::Update('article', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $article['add_uid'] = $login_user_id;
        $article['add_time'] = time();
        $id = DB::Insert('article', $article);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/website_article?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'website_case') {
    //文章管理
    $id = $_REQUEST['id'];
    $case = $_REQUEST['case'];
    $case['send_time'] = strtotime($case['send_time']);
    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('case', array('id' => $id));
    } else {
        $site_id = DB::GetDbColumn('website', 'id', array('uid' => $login_user_id));
        $site_id[] = "-$login_user_id";
        $u = DB::GetTableRow('case', array('id' => $id, 'site_id' => $site_id));
    }
    if ($case['site_id'] == '') {
        echo 'error|请选择站点！';
        exit;
    }

    if ($case['title'] == '') {
        echo 'error|请填写标题！';
        exit;
    }
//    if ($case['description'] == '') {
//        echo 'error|请填写描述！';
//        exit;
//    }
    if ($case['send_time'] <= 0) {
        echo 'error|请填写发布时间！';
        exit;
    }
    if ($case['content'] == '') {
        echo 'error|请填写内容！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($case, $u);
        if (!empty($change)) {
            $res = DB::Update('case', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $case['add_uid'] = $login_user_id;
        $case['add_time'] = time();
        $id = DB::Insert('case', $case);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/website_case?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'setting_notice') {
    $id = $_REQUEST['id'];
    $message = $_REQUEST['message'];
    $recode = $_REQUEST ['recode'];

    if (!$recode) {
        echo 'error|请选择发送范围！';
        exit;
    }
    if ($message['title'] == '' || iconv_strlen($message['title'], "UTF-8") > 64) {
        echo 'error|请正确输入标题！';
        exit;
    }
    if ($message['content'] == '') {
        echo 'error|请输入公告内容！';
        exit;
    }
    $message['recode'] = implode(',', $recode);
    $message ['type'] = 'system';
    $message['send_uid'] = $login_user_id;
    $message['send_time'] = time();

    if (in_array('系统公告', $recode)) {
        $message['receive_uid'] = 0;
        $res = DB::Insert('message', $message);
    }
    if (in_array('所有用户', $recode)) {

        $users = DB::GetDbColumn('user', 'user_id', array('stat' => 1));
        foreach ($users as $uid) {
            $message['receive_uid'] = $uid;
            $res = DB::Insert('message', $message);
        }
    }

    if ($res) {
        echo 'redirect|' . WEB_ROOT . '/home/setting_notice?id=' . $res;
    } else {
        echo 'error|公告发布失败！';
    }
} elseif ($form_type == 'setting_user') {
    $uid = $_REQUEST['uid'];
    $username = trim(strtolower($_REQUEST['username']));
    $email = trim(strtolower($_REQUEST['email']));
    $tel = trim(strtolower($_REQUEST ['tel']));
    $password = trim($_REQUEST['password']);
    $stat = $_REQUEST['stat'];
    $u = DB::GetTableRow('user', array('user_id' => $uid));
    if ($u) {
        if ($username != $u['username'] && (!preg_match("/^[0-9A-Za-z]{4,16}$/", $username) || preg_match("/^[0-9]*$/", $username))) {
            echo 'error|用户名为4-16个数字或英文字符，不区分大小写，不能全为数字！';
            exit;
        }
        $user_u = DB::GetTableRow('user', array('username' => $username, "user_id!=" . $uid));
        if ($user_u) {
            echo 'error|用户名已经存在！';
            exit;
        }
        $mgc = DB::GetDbColumn('parameter', 'v', array('name' => 'username'));
        for ($i = 0; $i < count($mgc); $i++) {
            $content = substr_count($username, $mgc [$i]);
            if ($content > 0) {
                echo 'error|用户名包含敏感词汇！';
                exit;
            }
        }
        if ($email != $u['email'] && !preg_match("/^([0-9A-Za-z\\-_\\.]+)@(([0-9a-z\\-]+\\.)?[0-9a-z\\-]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $email)) {
            echo 'error|电子邮箱格式错误！';
            exit;
        }
        $user_e = DB::GetTableRow('user', array('email' => $email, "user_id!=" . $uid));
        if ($user_e) {
            echo 'error|电子邮箱已经存在！';
            exit;
        }
        if ($tel != $u['tel'] && !preg_match("/^((0\d{2,3}-\d{7,8}(-\d{1,6})?)|(1[34578]\d{9}))$/", $tel)) {
            echo 'error|联系电话格式错误！';
            exit;
        }
        $user_t = DB::GetTableRow('user', array('tel' => $tel, "user_id!=" . $uid));
        if ($user_t) {
            echo 'error|联系电话已经存在！';
            exit;
        }
        if ($password != '' && !preg_match("/^[0-9A-Za-z]{4,16}$/", $password)) {
            echo 'error|密码为4-16个数字或英文字符！';
            exit;
        }
        $user = array('username' => $username, 'email' => $email, 'tel' => $tel, 'stat' => $stat);
        if ($password != '') {
            $user['password'] = ZUser::GenPassword($password);
        }
        $change = array_diff_assoc($user, $u);
        if (!empty($change)) {
            $res = DB::Update('user', $uid, $change, 'user_id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    }
} elseif ($form_type == 'setting_version') {
    //版本管理
    $id = $_REQUEST['id'];
    $version = $_REQUEST['version'];
    $version['file'] = implode(',', $version['file']);
    $version['size'] = implode(',', $version['size']);


    $u = DB::GetTableRow('version', array('id' => $id));

    if ($version['stat'] == '') {
        echo 'error|请选择状态！';
        exit;
    }
    if ($version['auto'] == '') {
        echo 'error|请选择更新方式！';
        exit;
    }
    if ($version['old_version'] == '') {
        echo 'error|请填写旧版本！';
        exit;
    }
    if ($version['new_version'] == '') {
        echo 'error|请填写新版本！';
        exit;
    }
    if ($version['file'] == '') {
        echo 'error|请上传文件！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($version, $u);
        if (!empty($change)) {
            $res = DB::Update('version', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $version['update_time'] = time();
        $id = DB::Insert('version', $version);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/setting_version?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'setting_service') {
    //版本管理
    $id = $_REQUEST['id'];
    $service = $_REQUEST['service'];



    $u = DB::GetTableRow('service', array('id' => $id));

    if ($service['stat'] == '') {
        echo 'error|请选择状态！';
        exit;
    }

    if ($service['ip'] == '') {
        echo 'error|请填写IP地址！';
        exit;
    }
    if ($service['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }


    if ($u) {
        $change = array_diff_assoc($service, $u);
        if (!empty($change)) {
            $res = DB::Update('service', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {

        $id = DB::Insert('service', $service);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/setting_service?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'site_link') {
    //友情链接
    $id = $_REQUEST['id'];
    $link = $_REQUEST['link'];
    $link['url'] = Utility::GetHost('all', $link['url']);

    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('link', array('id' => $id));
    } else {
        $u = DB::GetTableRow('link', array('id' => $id, 'uid' => $login_user_id));
        $link['uid'] = $login_user_id;
    }
    if ($link['uid'] == '') {
        echo 'error|请选择用户！';
        exit;
    }
    if ($link['displayorder'] == '') {
        echo 'error|请填写排序！';
        exit;
    }
    if ($link['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
    if ($link['url'] == '') {
        echo 'error|请填写域名！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($link, $u);
        if (!empty($change)) {
            $res = DB::Update('link', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $id = DB::Insert('link', $link);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/site_link?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'site_banner') {
    //banner
    $id = $_REQUEST['id'];
    $banner = $_REQUEST['banner'];
    $banner['url'] = Utility::GetHost('all', $banner['url']);

    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('banner', array('id' => $id));
    } else {
        $u = DB::GetTableRow('banner', array('id' => $id, 'uid' => $login_user_id));
        $banner['uid'] = $login_user_id;
    }
    if ($banner['uid'] == '') {
        echo 'error|请选择用户！';
        exit;
    }
    if ($banner['displayorder'] == '') {
        echo 'error|请填写排序！';
        exit;
    }
    if ($banner['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
    if ($banner['logo'] == '') {
        echo 'error|请上传图片！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($banner, $u);
        if (!empty($change)) {
            $res = DB::Update('banner', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $id = DB::Insert('banner', $banner);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/site_banner?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'site_category') {
    //分类管理
    $id = $_REQUEST['id'];
    $category = $_REQUEST['category'];


    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('category', array('id' => $id));
    } else {
        $u = DB::GetTableRow('category', array('id' => $id, 'uid' => $login_user_id));
        $category['uid'] = $login_user_id;
    }
    if ($category['uid'] == '') {
        echo 'error|请选择用户！';
        exit;
    }
    if ($category['type'] == '') {
        echo 'error|请选择类型！';
        exit;
    }
    if ($category['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
    if ($category['sort_order'] == '') {
        echo 'error|请填写排序！';
        exit;
    }



    if ($u) {
        $change = array_diff_assoc($category, $u);
        if (!empty($change)) {
            $res = DB::Update('category', $id, $change, 'id');
            if (array_key_exists('uid', $change)) {
                $res = $res && DB::Update('category', array('parent_id' => $id), array('uid' => $change[uid]));
                check_battery();
            }

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $id = DB::Insert('category', $category);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/site_category?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'site_nav') {
    //友情链接
    $id = $_REQUEST['id'];
    $nav = $_REQUEST['navs'];


    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('nav', array('id' => $id));
    } else {
        $u = DB::GetTableRow('nav', array('id' => $id, 'uid' => $login_user_id));
        $nav['uid'] = $login_user_id;
    }
    if ($nav['uid'] == '') {
        echo 'error|请选择用户！';
        exit;
    }
    if ($nav['displayorder'] == '') {
        echo 'error|请填写排序！';
        exit;
    }
    if ($nav['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
    if ($nav['url'] == '') {
        echo 'error|请填写链接！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($nav, $u);
        if (!empty($change)) {
            $res = DB::Update('nav', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $id = DB::Insert('nav', $nav);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/site_nav?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'site_sidebar') {
    //友情链接
    $id = $_REQUEST['id'];
    $sidebar = $_REQUEST['sidebar'];
    $sidebar['content'] = stripslashes($sidebar['content']);

    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('sidebar', array('id' => $id));
    } else {
        $u = DB::GetTableRow('sidebar', array('id' => $id, 'uid' => $login_user_id));
        $sidebar['uid'] = $login_user_id;
    }
    if ($sidebar['uid'] == '') {
        echo 'error|请选择用户！';
        exit;
    }
    if ($sidebar['displayorder'] == '') {
        echo 'error|请填写排序！';
        exit;
    }
    if ($sidebar['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
    if ($sidebar['content'] == '') {
        echo 'error|请填写内容！';
        exit;
    }

    if ($u) {
        $change = array_diff_assoc($sidebar, $u);
        if (!empty($change)) {
            $res = DB::Update('sidebar', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $id = DB::Insert('sidebar', $sidebar);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/site_sidebar?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'site_company') {
    //公司管理
    $id = $_REQUEST['id'];
    $company = $_REQUEST['company'];

    if (checkperm('manager', false)) {
        $u = DB::GetTableRow('company', array('id' => $id));
    } else {
        $u = DB::GetTableRow('company', array('id' => $id, 'uid' => $login_user_id));
        $company['uid'] = $login_user_id;
    }
    if ($company['uid'] == '') {
        echo 'error|请选择用户！';
        exit;
    }

    if ($company['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
    if ($company['contacts'] == '') {
        echo 'error|请填写联系人！';
        exit;
    }
    if ($company['tel'] == '') {
        echo 'error|请填写电话！';
        exit;
    } elseif (!preg_match("/^((0\d{2,3}-\d{7,8}(-\d{1,6})?)|(1[34578]\d{9})|(400[-|\d]{7,9}))$/", $company['tel'])) {
        echo 'error|电话格式错误！';
        exit;
    }
    if ($company['email'] && !preg_match("/^([0-9A-Za-z\\-_\\.]+)@(([0-9a-z\\-]+\\.)?[0-9a-z\\-]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $company['email'])) {
        echo 'error|电子邮箱格式错误！';
        exit;
    }
    if ($company['phone'] && !preg_match("/^(1[34578]\d{9})$/", $company['phone'])) {
        echo 'error|手机格式错误！';
        exit;
    }
    if ($u) {
        $change = array_diff_assoc($company, $u);
        if (!empty($change)) {
            $res = DB::Update('company', $id, $change, 'id');

            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        $id = DB::Insert('company', $company);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/site_company?id=' . $id;
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'user_register') {
    $email = trim(strtolower($_REQUEST['email']));
    $tel = trim(strtolower($_REQUEST['tel']));
    if ($email != $login_user['email'] || $tel != $login_user['tel']) {
        if ($email != $login_user['email'] && !preg_match("/^([0-9A-Za-z\\-_\\.]+)@(([0-9a-z\\-]+\\.)?[0-9a-z\\-]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $email)) {
            echo 'error|电子邮箱格式错误！';
        } elseif ($tel != $login_user['tel'] && !preg_match("/^((0\d{2,3}-\d{7,8}(-\d{1,6})?)|(1[34578]\d{9}))$/", $tel)) {
            echo 'error|联系电话格式错误！';
        } else {
            $user_e = DB::GetTableRow('user', array('email' => $email));
            $user_t = DB::GetTableRow('user', array('tel' => $tel));
            if ($user_e && $user_e['user_id'] != $login_user['user_id']) {
                echo 'error|电子邮箱已经存在！';
            } elseif ($user_t && $user_t['user_id'] != $login_user['user_id']) {
                echo 'error|联系电话已经存在！';
            } else {
                $res = DB::Update('user', $login_user['user_id'], array('email' => $email, 'tel' => $tel), 'user_id');
                echo $res ? 'success|信息修改成功！' : 'error|信息修改失败！';
            }
        }
    }
} elseif ($form_type == 'user_password') {
    $old = $_REQUEST['old'];
    $password = $_REQUEST['new'];
    $password1 = $_REQUEST['new1'];
    if ($old != '' || $password != '' || $password1 != '') {
        $old = ZUser::GenPassword($old);
        if ($old != $login_user['password']) {
            echo 'error|原密码错误！';
        } elseif ($password == '' || $password1 == '') {
            echo 'error|请输入新密码！';
        } elseif (!preg_match("/^[0-9A-Za-z]{4,16}$/", $password)) {
            echo 'error|密码为4-16个数字或英文字符！';
        } elseif ($password != $password1) {
            echo 'error|两次密码不一致！';
        } else {
//密码重置操作
            $p = ZUser::GenPassword($password);
            $res = DB::Update('user', $login_user['user_id'], array('password' => $p), 'user_id');
            if ($res) {
                echo 'success|密码修改成功！';
            } else {
                echo 'error|密码修改失败！';
            }
        }
    }
} elseif ($form_type == 'user_invoice') {
    $invoice = $_REQUEST['invoice'];
    $invoice['uid'] = $login_user_id;
    $u = DB::GetTableRow('user_invoice', array('uid' => $login_user_id));

    if ($invoice['type'] == 'company') {
        if ($invoice['name'] == "") {
            echo 'error|发票抬头不能为空！';
            exit;
        }
    } else {
        $invoice['mold'] = 'common';
    }
    if ($invoice['tax_no'] == "" || $invoice['bank_no'] == "" || $invoice['bank_name'] == "" || $invoice['reg_address'] == "" || $invoice['reg_tel'] == "") {
        echo 'error|请完整填写相关信息！';
        exit;
    }
    if ($u) {
        $res = DB::Update('user_invoice', $u['id'], $invoice);
    } else {
        $res = DB::Insert('user_invoice', $invoice);
    }
    if ($res) {
        echo 'success|信息修改成功！';
    } else {
        echo 'error|信息修改失败！';
    }
} elseif ($form_type == 'auth_assignment') {
    //授权管理
    $id = $_REQUEST['id'];
    $u = DB::GetTableRow('user', array('user_id' => $id));

    if ($u) {
        $assignment = $_REQUEST['assignment'] ? $_REQUEST['assignment'] : array();  //新设置角色
        $assignment_u = DB::GetDbColumn('auth_assignment', 'item_name', array('user_id' => $id)); //已经获得角色

        $add = array_diff($assignment, $assignment_u);
        $del = array_diff($assignment_u, $assignment);
        $res = 1;

        if (!empty($add)) {
            $NOW = time();
            foreach ($add as $one) {
                $res = $res && DB::Insert('auth_assignment', array('item_name' => $one, 'user_id' => $id, 'created_at' => $NOW));
            }
        }

        if (!empty($del)) {
            $res = $res && DB::Delete('auth_assignment', array('item_name' => array_values($del), 'user_id' => $id));
        }

        if ($res) {
            echo 'success|信息修改成功！';
        } else {
            echo 'error|信息修改失败！';
        }
    } else {
        echo 'error|信息修改失败！';
    }
} elseif ($form_type == 'auth_role') {
    //角色管理
    $id = $_REQUEST['id'];
    $role = $_REQUEST['role'];


    if ($role['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }

    $u = DB::GetTableRow('auth_item', array('name' => $id, 'type' => 1));
    $user_u = DB::GetTableRow('auth_item', array('name' => $role['name']));

    if ($u) {
        if ($user_u['created_at'] != $u['created_at']) {
            echo 'error|名称已经存在！';
            exit;
        }
        $res = 1;
        //处理权限
        $permissions = $_REQUEST['permission'] ? $_REQUEST['permission'] : array();
        $role_per = DB::GetDbColumn('auth_item_child', 'child', array('parent' => $u['name']));
        $add = array_diff($permissions, $role_per);
        $del = array_diff($role_per, $permissions);

        if (!empty($add)) {
            foreach ($add as $one) {
                $res = $res && DB::Insert('auth_item_child', array('parent' => $u['name'], 'child' => $one));
            }
        }
        if (!empty($del)) {
            $res = $res && DB::Delete('auth_item_child', array('parent' => $u['name'], 'child' => array_values($del)));
        }
        $change = array_diff_assoc($role, $u);
        if (!empty($change)) {
            $res = $res && DB::Update('auth_item', array('name' => $id, 'type' => 1), $change);
            if (array_key_exists('name', $change)) {
                //更改关联表
                $res = $res && DB::Update('auth_item_child', array('parent' => $u['name']), array('parent' => $role['name']));
            }
        }
        if ($res) {
            echo 'success|信息修改成功！';
        } else {
            echo 'error|信息修改失败！';
        }
    } else {
        if ($user_u) {
            echo 'error|名称已经存在！';
            exit;
        }
        $role['created_at'] = time();
        $role['type'] = 1;
        $id = DB::Insert('auth_item', $role);
        if ($id) {
            //处理权限
            $permissions = $_REQUEST['permission'];

            if (!empty($permissions)) {
                foreach ($permissions as $one) {
                    DB::Insert('auth_item_child', array('parent' => $role['name'], 'child' => $one));
                }
            }
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/auth_role?id=' . $role['name'];
        } else {
            echo 'error|添加失败！';
        }
    }
} elseif ($form_type == 'auth_permission') {
    //权限管理
    $id = $_REQUEST['id'];
    $permission = $_REQUEST['permission'];
    $permission['updated_at'] = time();

    if ($permission['name'] == '') {
        echo 'error|请填写名称！';
        exit;
    }
//    if ($permission['description'] == '') {
//        echo 'error|请填写描述！';
//        exit;
//    }
    $u = DB::GetTableRow('auth_item', array('name' => $id, 'type' => 2));
    $user_u = DB::GetTableRow('auth_item', array('name' => $permission['name']));
    if ($u) {
        if ($user_u['created_at'] != $u['created_at']) {
            echo 'error|名称已经存在！';
            exit;
        }
        $change = array_diff_assoc($permission, $u);
        if (!empty($change)) {
            $res = DB::Update('auth_item', array('name' => $id, 'type' => 2), $change);
            if (array_key_exists('name', $change)) {
                //更改关联表
                DB::Update('auth_item_child', array('child' => $u['name']), array('child' => $permission['name']));
            }
            if ($res) {
                echo 'success|信息修改成功！';
            } else {
                echo 'error|信息修改失败！';
            }
        }
    } else {
        if ($user_u) {
            echo 'error|名称已经存在！';
            exit;
        }
        $permission['created_at'] = time();
        $permission['type'] = 2;
        $id = DB::Insert('auth_item', $permission);
        if ($id) {
            showmessage('success', '', '添加成功！');
            echo 'redirect|' . WEB_ROOT . '/home/auth_permission?id=' . $permission['name'];
        } else {
            echo 'error|添加失败！';
        }
    }
} else {
    echo 'error|错误操作！';
}
?>