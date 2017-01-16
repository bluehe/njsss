<?php

class AvatarUploader {

    //获取系统图片目录
    public function getDataDir() {
        global $INI;

        if (isset($INI['pre']['data_dir'])) {
            /*
              $pres = explode(".",strrev($_SERVER['HTTP_HOST']));
              $pre = strrev($pres[2]);
             */
            $datadir = $INI['pre']['data_dir'];
        } else {
            $datadir = 'www';
        }
        return $datadir;
    }

    private function api() {
        $api = strtolower((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));
        return $api;
    }

    // 本次页面请求的 url
    private function getThisUrl() {
        //$thisUrl = $_SERVER['SCRIPT_NAME'];
        $thisUrl = "http://{$_SERVER['HTTP_HOST']}";
        return $thisUrl;
    }

    // 本次页面请求的 base-url（尾部有 /）
    private function getBaseUrl() {
        $baseUrl = self::getThisUrl() . '/';
        //$baseUrl = substr( $baseUrl, 0, strrpos( $baseUrl, '/' ) + 1 );
        return $baseUrl;
    }

    // 用于存储的本地文件夹（尾部有一个 DIRECTORY_SEPARATOR）
    public function getBasePath() {
        $basePath = dirname($_SERVER['SCRIPT_FILENAME']);
        $basePath = str_replace('/', DIRECTORY_SEPARATOR, $basePath);
        $basePath = substr($basePath, 0, strrpos($basePath, DIRECTORY_SEPARATOR) + 1);
        return $basePath;
    }

    // 第一步：上传图片文件
    public function uploadAvatar($uid, $user_type = 'member', $key = '_source') {
        // 检查上传文件的有效性
        if (empty($_FILES[$key])) {
            return -3; // No photograph be upload!
        }

        // 本地存储位置
        $pre = self::getDataDir();
        $tmpPath = self::getBasePath() . "data" . DIRECTORY_SEPARATOR . "{$pre}" . DIRECTORY_SEPARATOR . "{$user_type}" . DIRECTORY_SEPARATOR . "{$uid}{$key}.jpg";

        // 如果存储的文件夹不存在，先创建它
        $dir = dirname($tmpPath);
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }

        // 如果同名的文件已经存在，先删除它
        if (file_exists($tmpPath)) {
            @unlink($tmpPath);
        }

        // 把上传的图片文件保存到预定位置
        if (@copy($_FILES[$key]['tmp_name'], $tmpPath) || @move_uploaded_file($_FILES[$key]['tmp_name'], $tmpPath)) {
            @unlink($_FILES[$key]['tmp_name']);
            list($width, $height, $type, $attr) = getimagesize($tmpPath);
            if ($width < 10 || $height < 10 || $width > 3000 || $height > 3000 || $type == 4) {
                @unlink($tmpPath);
                return -2; // Invalid photograph!
            }
        } else {
            @unlink($_FILES[$key]['tmp_name']);
            return -4; // Can not write to the data/tmp folder!
        }

        // 用于访问临时图片文件的 url
        $tmpUrl = self::getBaseUrl() . "data/{$pre}/{$user_type}/{$uid}{$key}.jpg";
        return $tmpUrl;
    }

    // 从客户端访问头像图片的 url
    public function getAvatarUrl($uid, $type = 'member', $size = 'middle') {
        $pre = self::getDataDir();
        return self::getBaseUrl() . "data/{$pre}/{$type}/{$uid}_{$size}.jpg";
    }

}

?>