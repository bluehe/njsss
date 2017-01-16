<?php

class ZUpdate {

    static public function GetUpdate($version = '', $auto = '') {
        $condition = array('stat' => 1, 'old_version' => $version);
        if ($auto !== '') {
            $condition['auto'] = $auto;
        }
        $v = DB::LimitQuery('version', array('condition' => $condition, 'order' => "order by id desc", 'one' => TRUE));

        if ($v) {
            return $v;
        } else {
            return false;
        }
    }

    static public function DoUpdate($auto = '') {
        $localdir = dirname(dirname(dirname(__FILE__)));
        $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($localdir . "/config.json")), true);
        $version = self::GetUpdate($config['version'], $auto);
        if ($version) {
            //需要更新
            $INI = ZSystem::GetINI();
            $files = explode(',', $version['file']);
            foreach ($files as $key => $file) {
                $files[$key] = preg_match("/^https?:\/\//i", $file) ? $file : $INI['host_web'] . $file;
            }
            $res = self::check_remote_file_exists($files);
            if ($res) {
                //远端文件存在

                $updatedir = self::load_file($files, $version['old_version']);
                if ($updatedir['result'] === false) {
                    return $updatedir;
                }
                //更新开始
                $localdomain = Utility::GetHost('all', $_SERVER['HTTP_HOST']);
                $website = DB::GetTableRow('website', array('domain' => $localdomain));
                if ($website['stat'] == 1) {
                    DB::Update('website', $website['id'], array('stat' => 0));
                }
                $sqlres = 1;
                $fileres = 1;
                if (file_exists($localdir . '/data/update/' . $version['old_version'] . '/sql.php')) {
//备份数据库，更新数据库
                    $sqlres = self::update_sql($version['old_version']);
                }
                if ($sqlres) {
//数据库更新完成,更新文件
                    $fileres = self::update_file($version['old_version']);
                    if ($website['stat'] == 1) {
                        DB::Update('website', $website['id'], array('stat' => 1));
                    }
                    if (!$fileres) {
                        return array('result' => 'fail', 'note' => '文件更新失败！');
                    }
                } else {
                    if ($website['stat'] == 1) {
                        DB::Update('website', $website['id'], array('stat' => 1));
                    }
                    return array('result' => 'fail', 'note' => '数据库更新失败！');
                }
            } else {
                return array('result' => 'fail', 'note' => '文件不存在！');
            }
        } else {
            return array('result' => 'fail', 'note' => '升级失败！');
        }
        return array('result' => 'success', 'note' => '升级成功！');
    }

    static public function RestoreUpdate($updatedir) {
        $localdir = dirname(dirname(dirname(__FILE__)));
        self::write_update_log($updatedir, '更新恢复开始');
        if (file_exists($localdir . '/data/update/' . $updatedir . '/sql.php') && file_exists($localdir . '/data/update/backup/' . $updatedir . '.sql')) {
            self::write_update_log($updatedir, '数据库恢复开始');
            $t = new DatabaseTool();
            $t->restore($localdir . '/data/update/backup/' . $updatedir . '.sql');
            self::write_update_log($updatedir, '数据库恢复结束');
        }
        if (file_exists($localdir . '/data/update/' . $updatedir . '/file.php')) {
            self::write_update_log($updatedir, '文件恢复开始');
            require($localdir . '/data/update/' . $updatedir . '/file.php');
            $updatefiles = listdir($localdir . '/data/update/' . $updatedir . '/file');
            foreach ($updatefiles as $file) {

                $file = iconv("utf-8", "gb2312", $file);
                if (file_exists($localdir . '/' . $file) && file_exists($localdir . '/data/update/tmp/' . $updatedir . '/' . $file) && !in_array($file, $addfiles)) {
//文件恢复
                    copy($localdir . '/data/update/tmp/' . $updatedir . '/' . $file, $localdir . '/' . $file);
                    self::write_update_log($updatedir, '恢复更新文件' . $file);
                }
            }

            $addfiles = array_reverse($addfiles);
            foreach ($addfiles as $file) {

//文件删除
                $file = iconv("utf-8", "gb2312", $file);
                if (file_exists($localdir . '/' . $file)) {
                    if (is_dir($localdir . '/' . $file)) {
                        rmdir($localdir . '/' . $file);
                        self::write_update_log($updatedir, '恢复增加文件夹' . $file);
                    } else {
                        @unlink($localdir . '/' . $file);
                        self::write_update_log($updatedir, '恢复增加文件' . $file);
                    }
                }
            }
            foreach (array_reverse($delfiles) as $file) {
                $file = iconv("utf-8", "gb2312", $file);
                if (!file_exists($localdir . '/' . $file) && (file_exists($localdir . '/data/update/tmp/' . $updatedir . '/' . $file) || is_dir($file))) {
//文件存在，文件备份
                    if (is_dir($file)) {
                        @mkdir($localdir . '/' . $file, 0777);
                        self::write_update_log($updatedir, '恢复删除文件夹' . $file);
                    } else {
                        if ((file_exists(dirname($localdir . '/' . $file)) || @mkdir(dirname($localdir . '/' . $file), 0777, true))) {
//文件增加
                            copy($localdir . '/data/update/tmp/' . $updatedir . '/' . $file, $localdir . '/' . $file);
                            self::write_update_log($updatedir, '恢复删除文件' . $file);
                        }
                    }
                }
            }
            self::write_update_log($updatedir, '文件恢复结束');
        }
    }

    static public function CheckRemoteFile($files) {
        return self::check_remote_file_exists($files);
    }

    static private function check_remote_file_exists($files) {
        settype($files, 'array');
        foreach ($files as $url) {
            $curl = curl_init($url);
            //不取回数据
            curl_setopt($curl, CURLOPT_NOBODY, true);
            //发送请求
            $result = curl_exec($curl);

            if ($result !== false) {
                //检查http响应码是否为200
                $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                if ($statusCode != 200) {

                    return false;
                }
            }
            curl_close($curl);
        }
        return true;
    }

    static private function load_file($files, $d = '') {
        settype($files, 'array');
//api获取到数据
        $dir = dirname(dirname(dirname(__FILE__))) . '/data/update' . ($d ? '/' . $d : '');
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }
        $result = array('result' => true, 'note' => '文件下载成功！');

        foreach ($files as $fileurl) {
            $filename = basename($fileurl); //substr($result['file'], strrpos($result['file'], '/') + 1);
            $size = file_put_contents($dir . '/' . $filename, file_get_contents($fileurl));
            if ($size > 0) {
//下载文件成功
                $p = pathinfo($dir . '/' . $filename);
                if (strtolower($p['extension']) == 'zip' && function_exists('zip_open')) {

                    $res1 = self::get_zip_originalsize($dir . '/' . $filename, $dir . '/');
                    if (!$res1) {
//解压缩失败
                        $result = array('result' => false, 'note' => '文件解压缩失败！');
                        break;
                    }
                } else {
                    $result = array('result' => false, 'note' => '文件不是ZIP格式或服务器不支持解压缩！');
                    break;
                }
            } else {
                $result = array('result' => false, 'note' => '文件下载失败！');
                break;
            }
        }
        foreach ($files as $fileurl) {
            $filename = basename($fileurl);
            @unlink($dir . '/' . $filename); //删除压缩文件
        }

        return $result;
    }

    static private function get_zip_originalsize($filename, $path) {
//先判断待解压的文件是否存在
        if (!file_exists($filename)) {
            return false;
        }

//将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
        $filename = iconv("utf-8", "gb2312", $filename);
        $path = iconv("utf-8", "gb2312", $path);
//打开压缩包
        $resource = zip_open($filename);

        $i = 1;
//遍历读取压缩包里面的一个个文件
        while ($dir_resource = zip_read($resource)) {

//如果能打开则继续
            if (zip_entry_open($resource, $dir_resource)) {
//获取当前项目的名称,即压缩包里面当前对应的文件名
                $file_name = $path . zip_entry_name($dir_resource);
//以最后一个“/”分割,再用字符串截取出路径部分
                $file_path = substr($file_name, 0, strrpos($file_name, "/"));
//如果路径不存在，则创建一个目录，true表示可以创建多级目录
                if (!is_dir($file_path)) {
                    mkdir($file_path, 0777, true);
                }
//如果不是目录，则写入文件
                if (!is_dir($file_name)) {
//读取这个文件
                    $file_size = zip_entry_filesize($dir_resource);
//最大读取6M，如果文件过大，跳过解压，继续下一个
                    $file_content = zip_entry_read($dir_resource, $file_size);
                    file_put_contents($file_name, $file_content);
                }
//关闭当前
                zip_entry_close($dir_resource);
            }
        }
//关闭压缩包
        zip_close($resource);
        return true;
    }

    static private function write_update_log($filename, $text) {
        //$word = json_encode($flow);
        //$targetFolder = WEB_ROOT . '/data/update/tmp';
        //$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
        $targetPath = dirname(dirname(dirname(__FILE__))) . '/data/update/tmp';
        $basePath = rtrim($targetPath, '/') . '/';
        if (!file_exists($basePath)) {
            @mkdir($basePath, 0777, true);
        }
        $targetFile = str_replace('/', DIRECTORY_SEPARATOR, $basePath) . $filename . '.txt';
        $fp = fopen($targetFile, 'a');
        flock($fp, LOCK_EX);  //锁定文件，避免读写
        fwrite($fp, date('Y-m-d H:i:s', time()) . ' ' . $text . "\r\n");
        flock($fp, LOCK_UN);   //解锁
        fclose($fp); //关闭程序流
    }

    static private function update_sql($updatedir) {
        $localdir = dirname(dirname(dirname(__FILE__)));
        self::write_update_log($updatedir, '数据库更新开始');
        $dir = $localdir . '/data/update/backup';
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }
        $t = new DatabaseTool(array('target' => $localdir . '/data/update/backup/' . $updatedir));
        $t->backup();
        require_once($localdir . '/data/update/' . $updatedir . '/sql.php');
        $res = updatesql();
        if ($res) {
            self::write_update_log($updatedir, '数据库更新成功');
        } else {
            self::write_update_log($updatedir, '数据库更新失败');
            self::RestoreUpdate($updatedir);
        }
        return $res;
    }

    static private function update_file($updatedir) {

        $localdir = dirname(dirname(dirname(__FILE__)));
        $fileres = 1;
        if (file_exists($localdir . '/data/update/' . $updatedir . '/file.php')) {
            self::write_update_log($updatedir, '文件更新开始');
            require_once($localdir . '/data/update/' . $updatedir . '/file.php');


            foreach ($delfiles as $file) {

                $file = iconv("utf-8", "gb2312", $file);
                if (file_exists($localdir . '/' . $file)) {
//文件存在，文件备份
                    if (is_dir($localdir . '/' . $file)) {
                        self::write_update_log($updatedir, '删除文件夹' . $file);
                        rmdir($localdir . '/' . $file);
                    } else {
                        if ((file_exists(dirname($localdir . '/data/update/tmp/' . $updatedir . '/' . $file)) || @mkdir(dirname($localdir . '/data/update/tmp/' . $updatedir . '/' . $file), 0777, true)) && copy($localdir . '/' . $file, $localdir . '/data/update/tmp/' . $updatedir . '/' . $file)) {
//文件删除
                            self::write_update_log($updatedir, '删除文件' . $file . '成功');
                            @unlink($localdir . '/' . $file);
                        } else {
                            self::write_update_log($updatedir, '删除文件' . $file . '未能备份及删除');
                            $fileres = 0;
                            break;
                        }
                    }
                } else {
                    self::write_update_log($updatedir, '删除文件' . $file . '不存在');
                }
            }
            if ($fileres) {
                foreach ($addfiles as $file) {
//增加文件
                    $file = iconv("utf-8", "gb2312", $file);
                    if (file_exists(dirname($localdir . '/' . $file)) && !file_exists($localdir . '/' . $file) && file_exists($localdir . '/data/update/' . $updatedir . '/file/' . $file)) {
                        if (is_dir($localdir . '/data/update/' . $updatedir . '/file/' . $file)) {
                            $fileres = $fileres && @mkdir($localdir . '/' . $file, 0777);
                            if ($fileres) {
                                self::write_update_log($updatedir, '增加文件夹' . $file . '成功');
                            } else {
                                self::write_update_log($updatedir, '增加文件夹' . $file . '失败');
                                break;
                            }
                        } else {
                            $fileres = $fileres && copy($localdir . '/data/update/' . $updatedir . '/file/' . $file, $localdir . '/' . $file);
                            if ($fileres) {
                                self::write_update_log($updatedir, '增加文件' . $file . '成功');
                            } else {
                                self::write_update_log($updatedir, '增加文件' . $file . '失败');
                                break;
                            }
                        }
                    } else {
                        self::write_update_log($updatedir, '增加文件' . $file . '出错，更新失败！');
                        $fileres = 0;
                        break;
                    }
                }
            }
            if ($fileres) {
                $updatefiles = self::list_dir($localdir . '/data/update/' . $updatedir . '/file');

                foreach ($updatefiles as $file) {
                    $file = iconv("utf-8", "gb2312", $file);
                    if (!in_array($file, $addfiles) && file_exists($localdir . '/' . $file) && file_exists($localdir . '/data/update/' . $updatedir . '/file/' . $file)) {
//文件存在，文件备份
                        if ((file_exists(dirname($localdir . '/data/update/tmp/' . $updatedir . '/' . $file)) || @mkdir(dirname($localdir . '/data/update/tmp/' . $updatedir . '/' . $file), 0777, true)) && copy($localdir . '/' . $file, $localdir . '/data/update/tmp/' . $updatedir . '/' . $file)) {
//文件更新
                            $fileres = $fileres && copy($localdir . '/data/update/' . $updatedir . '/file/' . $file, $localdir . '/' . $file);
                            if ($fileres) {
                                self::write_update_log($updatedir, '更新文件' . $file . '成功');
                            } else {
                                self::write_update_log($updatedir, '更新文件' . $file . '失败');
                                break;
                            }
                        } else {
                            self::write_update_log($updatedir, '更新文件' . $file . '出错，更新失败！');
                            $fileres = 0;
                            break;
                        }
                    } elseif (!in_array($file, $addfiles)) {
                        self::write_update_log($updatedir, '更新文件' . $file . '不存在，更新失败！');
                        $fileres = 0;
                        break;
                    }
                }
            }
        }
        if ($fileres) {
            self::write_update_log($updatedir, '文件更新成功！');
        } else {
            self::write_update_log($updatedir, '文件更新失败！');
            self::RestoreUpdate($updatedir);
        }
        return $fileres;
    }

    static private function list_dir($dir = "", $files = array(), $pre = '') {
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

}
