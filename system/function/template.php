<?php

/**
 * @author:blue
 */
if (false == defined('DIR_COMPILED'))
    define('DIR_COMPILED', '/tmp');
if (false == defined('DIR_TEMPLATE'))
    define('DIR_TEMPLATE', '/tmp');

function __parsecall($matches) {
    return '<?php include template("' . $matches[1] . '"); ?>';
}

function __parse($tFile, $cFile) {

    $fileContent = false;
    if (!($fileContent = file_get_contents($tFile)))
        return false;

    $fileContent = preg_replace('/^(\xef\xbb\xbf)/', '', $fileContent); //EFBBBF
    $fileContent = preg_replace_callback("/\<\!\-\-\s*\\\$\{(.+?)\}\s*\-\-\>/is", function($r) {
        return __replace('<?php ' . $r[1] . '; ?>');
    }, $fileContent);
    $fileContent = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\\\ \-\'\,\%\*\/\.\(\)\>\'\"\$\x7f-\xff]+)\}/s", "<?php echo \\1; ?>", $fileContent);
    $fileContent = preg_replace_callback("/\\\$\{(.+?)\}/is", function($r) {
        return __replace('<?php echo ' . $r[1] . '; ?>');
    }, $fileContent);
    $fileContent = preg_replace_callback("/\<\!\-\-\s*\{else\s*if\s+(.+?)\}\s*\-\-\>/is", function($r) {
        return __replace('<?php } else if(' . $r[1] . ') { ?>');
    }, $fileContent);
    $fileContent = preg_replace_callback("/\<\!\-\-\s*\{elif\s+(.+?)\}\s*\-\-\>/is", function($r) {
        return __replace('<?php } else if(' . $r[1] . ') { ?>');
    }, $fileContent);
    $fileContent = preg_replace("/\<\!\-\-\s*\{else\}\s*\-\-\>/is", "<?php } else { ?>", $fileContent);

    for ($i = 0; $i < 6; ++$i) {
        $fileContent = preg_replace_callback("/\<\!\-\-\s*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\s*\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/loop\}\s*\-\-\>/is", function($r) {
            return __replace('<?php if(is_array(' . $r[1] . ')){foreach(' . $r[1] . ' AS ' . $r[2] . '=>' . $r[3] . ') { ?>' . $r[4] . '<?php }}?>');
        }, $fileContent);
        $fileContent = preg_replace_callback("/\<\!\-\-\s*\{loop\s+(\S+)\s+(\S+)\s*\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/loop\}\s*\-\-\>/is", function($r) {
            return __replace('<?php if(is_array(' . $r[1] . ')){foreach(' . $r[1] . ' AS ' . $r[2] . ') { ?>' . $r[3] . '<?php }}?>');
        }, $fileContent);
        $fileContent = preg_replace_callback("/\<\!\-\-\s*\{if\s+(.+?)\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/if\}\s*\-\-\>/is", function($r) {
            return __replace('<?php if(' . $r[1] . '){?>' . $r[2] . '<?php }?>');
        }, $fileContent);
    }

    //Add for call <!--{include othertpl}-->
    $fileContent = preg_replace("#<!--\s*{\s*include\s+([^\{\}]+)\s*\}\s*-->#i", '<?php include template("\\1");?>', $fileContent);

    //Add value namespace
    if (!file_put_contents($cFile, $fileContent))
        return false;
    return true;
}

function __replace($string) {
    return str_replace('\"', '"', $string);
}

function __template($tFile) {

    $tFileN = preg_replace('/\.html$/', '', $tFile);
    $tFile = DIR_TEMPLATE . '/' . $tFileN . '.html';
    $cFile = DIR_COMPILED . '/' . str_replace('/', '_', $tFileN) . '.php';

    if (false === file_exists($tFile)) {
        die("Templace File [$tFile] Not Found!");
    }

    if (false === file_exists($cFile) || @filemtime($tFile) > @filemtime($cFile)) {
        __parse($tFile, $cFile);
    }

    return $cFile;
}

?>
