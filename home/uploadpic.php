<?php

/*
  Uploadify
  Copyright (c) 2012 Reactive Apps, Ronnie Garcia
  Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
 */
// Define a destination
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$dir = $_POST['dir'] ? $_POST['dir'] : 'images';
$targetFolder = WEB_ROOT . '/data/' . $dir; // Relative to the root
if (!empty($_FILES)) {
    $tempFile = $_FILES['file']['tmp_name'];
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
    $basePath = rtrim($targetPath, '/') . '/';
    if (!file_exists($basePath)) {
        @mkdir($basePath, 0777, true);
    }
    //$targetFile = str_replace('/', DIRECTORY_SEPARATOR, $basePath) . $_FILES['file']['name'];
    // Validate the file type
    $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
    $fileParts = pathinfo($_FILES['file']['name']);
    $fileParts['extension'] = strtolower($fileParts['extension']);
    $filename = time() . mt_rand(10000, 99999) . '.' . $fileParts['extension'];
    $targetFile = str_replace('/', DIRECTORY_SEPARATOR, $basePath) . $filename;

    if (file_exists($filename)) {
        @unlink($filename);
    }

    if (in_array($fileParts['extension'], $fileTypes)) {
        if (@move_uploaded_file($tempFile, $targetFile) || @copy($tempFile, $targetFile)) {
            echo $targetFolder . '/' . $filename;
        } else {
            echo 'Invalid file type.';
        }
    } else {
        echo 'Invalid file type.';
    }
}
?>