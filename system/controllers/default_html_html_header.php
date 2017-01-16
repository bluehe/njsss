<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $nav['title']?$nav['title'].'_':''; ?><?php echo $INI['system']['sitetitle']; ?></title>
        <meta name="keywords" content="<?php echo $nav['keywords']?$nav['keywords']:$INI['system']['keywords']; ?>" />
        <meta name="description" content="<?php echo $nav['description']?$nav['description']:$INI['system']['description']; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/css/toastr.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/css/font-awesome.min.css">

        <script src="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/js/toastr.min.js" type="text/javascript"></script>
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