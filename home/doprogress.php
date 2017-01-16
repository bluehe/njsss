<?php

/**
 * @author blue
 */
session_start();
$r = $_REQUEST['r'];
$num = $_SESSION['load_num_' . $r];
$i = $_SESSION['load_i'];
echo $i . '|' . $num;
?>