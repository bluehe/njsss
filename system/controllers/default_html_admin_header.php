<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title><?php echo $INI['system']['sitetitle']; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/css/admincp.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/css/toastr.min.css">
        <script src="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/js/toastr.min.js" type="text/javascript"></script>
        <script src="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/js/admin/common.js" type="text/javascript"></script>
        <script src="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/js/admin/admincp.js" type="text/javascript"></script>


        <script type="text/javascript">
                    var WEB_ROOT = "<?php echo WEB_ROOT; ?>";
        </script>
    </head>
    <body>
        <?php if(Session::Get('notice',true)){?>
        <script type="text/javascript">
            var txt = "<?php echo Session::Get('message', true); ?>";
            var type = "<?php echo Session::Get('message_type', true); ?>";
            var title = "<?php echo Session::Get('title', true); ?>";

            if (type == 'error') {
                toastr.error(txt, title);
            } else if (type == 'success') {
                toastr.success(txt, title);
            } else if (type == 'warning') {
                toastr.warning(txt, title);
            } else if (type == 'info') {
                toastr.info(txt, title);
            }
        </script>
        <?php }?>
        <div id="append_parent"></div>
