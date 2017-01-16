<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');

$filename = iconv("UTF-8", "GB2312", $_POST['filename']);

$targetPath = $_SERVER['DOCUMENT_ROOT'];
$basePath = rtrim($targetPath, '/');
$targetFile = str_replace('/', DIRECTORY_SEPARATOR, $basePath) . $filename;
if (file_exists($targetFile)) {
    @unlink($targetFile);
    echo "success|成功删除文件！";
} else {
    echo "info|文件不存在！";
}
?>