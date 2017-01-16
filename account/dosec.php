<?php

/**
 * @author blue
 */
require_once(dirname(dirname(__FILE__)) . '/loader.php');
$sec = md5(strtoupper($_GET['seccode']));
$seccode = $_SESSION['seccode'];
if ($sec == $seccode) {
    echo 'yes';
} else {
    echo 'no';
}
?>