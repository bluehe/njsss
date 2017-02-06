<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
need_login();

if (checkperm('work', false)) {
    Utility::Redirect('work');
} elseif (checkperm('order_list', false)) {
    Utility::Redirect('order_list');
} elseif (checkperm('fee_list', false)) {
    Utility::Redirect('fee_list');
} else {
    Utility::Redirect('home/index');
}
?>