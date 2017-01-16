<?php

/**
 * @author blue
 */
require_once(dirname(__FILE__) . '/loader.php');
$nav = array('title' => '首页', 'nav1' => 'index');

//if (!file_exists('../backup/zhanplus_' . date('Y-m-d', time()) . '.sql')) {
//    $dir = dirname(dirname(__FILE__)) . '/backup';
//    if (!file_exists($dir)) {
//        @mkdir($dir, 0777, true);
//    }
//    $t = new DatabaseTool(array('target' => '../backup/zhanplus_' . date('Y-m-d', time())));
//    $t->backup();
//}

need_login();
Utility::Redirect(WEB_ROOT . '/work');
?>