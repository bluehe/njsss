<?php

function current_menu($type = 'member') {

    // $SYS = Table::Fetch('system', 'current_menu', 'k');
    $SYS = DB::GetTableRow('system', array('k' => 'current_menu'));
    $s = Utility::ExtraDecode($SYS['v']);
    foreach ($s[$type] as $key => $one) {
        $a['/' . $key] = $one['value'];
    }
    $r = $_SERVER['REQUEST_URI'];
    if (preg_match('#/([a-z]+/[a-z]+)#', $r, $m)) {
        $l = "{$m[1]}";
    } else {
        $l = $type . '/index';
    }
    return current_link($l, $a);
}

function current_menu_side($menu = 'member', $side = 'employee') {
    //$SYS = Table::Fetch('system', 'current_menu', 'k');
    $SYS = DB::GetTableRow('system', array('k' => 'current_menu'));
    $s = Utility::ExtraDecode($SYS['v']);
    foreach ($s[$menu][$side]['side'] as $key => $one) {
        $a['/' . $key] = $one;
    }

    $r = $_SERVER['REQUEST_URI'];
    if (preg_match('#/(' . $side . '\w+)#', $r, $m)) {
        $l = "{$m[1]}";
    } else
        $l = $side;
    return current_link($l, $a);
}

function current_side() {
    $a = array(
        '/account/account' => '注册信息',
        '/account/useravatar' => '头像',
        '/account/usercp' => '用户资料',
        '/account/pwchange' => '密码和邮箱修改',
    );
    $r = $_SERVER['REQUEST_URI'];
    if (preg_match('#/(account/\w+)#', $r, $m)) {
        $l = "{$m[1]}";
    } else
        $l = 'account/usercp';
    return current_link($l, $a);
}

function current_teacher_student() {
    $a = array(
        '/teacher/student' => '学生管理',
        '/teacher/student_edit' => '添加学生',
        '/teacher/student_check' => '学生信息审核',
        '/teacher/student_upload' => '学生导入',
    );
    $r = $_SERVER['REQUEST_URI'];
    if (preg_match('#/(teacher/student\w+)#', $r, $m)) {
        $l = "{$m[1]}";
    } else
        $l = 'teacher/student';
    return current_link($l, $a);
}

function current_link($link, $links, $span = false) {
    $html = '';
    $span = $span ? '<span></span>' : '';
    foreach ($links as $l => $n) {
        if (trim($l, '/') == trim($link, '/')) {
            $html .= "<li class='current'><a href=\"{$l}\">{$n}</a>{$span}</li>";
        } else
            $html .= "<li><a href=\"{$l}\">{$n}</a>{$span}</li>";
    }
    return $html;
}

?>