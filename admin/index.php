<?php

require_once(dirname(dirname(__FILE__)) . '/loader.php');
checkperm('system');
Utility::Redirect(WEB_ROOT . '/admin/admincp');
?>