<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');

need_manager();

$fid = $_GET['fid'];
if (is_post()) {
    $res1 = 1;
    $sql = "insert into forum_tmp select * from forum where id={$fid}";
    DB::Query($sql);
    $res1 = $res1 && DB::DelTableRow('forum', $fid);
    $sql = "insert into bed_tmp select * from bed where fid={$fid}";
    DB::Query($sql);
    $res1 = $res1 && DB::DelTableRow('bed', $fid, 'fid');
    if ($res1 === true) {
        adminmessage('2', '楼苑删除成功', '/admin/forum', true, 3);
    } else {
        adminmessage('3', '楼苑删除过程中出现错误', '/admin/forum', true, 3);
    }
}

$forum = DB::GetTableRow('forum', array('id' => $fid));
if ($forum) {
    $row = DB::GetTableRow('forum', array('fup' => $fid));
    if ($row) {
        adminmessage('3', '下级楼苑不为空，请先返回删除本楼苑的下级楼苑。', '/admin/forum', true, 3);
    }
} else {
    adminmessage('3', '错误操作！', '/admin/forum', true, 3);
}

include template('admin/forumdelete');
?>