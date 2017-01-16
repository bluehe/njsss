<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Blank Page</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo WEB_ROOT; ?>/themes/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo WEB_ROOT; ?>/themes/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo WEB_ROOT; ?>/themes/dist/css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="<?php echo WEB_ROOT; ?>/themes/default/css/work.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue-light sidebar-mini fixed">
        <!-- Site wrapper -->
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="<?php echo WEB_ROOT; ?>/" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">DMS</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><?php echo $INI['system']['sitename']; ?></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">切换导航</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">


                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo WEB_ROOT; ?>/themes/dist/img/user.png" class="user-image" alt="用户头像">
                                    <span class="hidden-xs"><?php echo $login_user['username']; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?php echo WEB_ROOT; ?>/themes/dist/img/user.png" class="img-circle" alt="用户头像">
                                        <p><?php echo $login_user['username']; ?></p>
                                    </li>

                                    <!-- Menu Footer-->
                                    <li class="user-footer">

                                        <div class="text-center">
                                            <a href="/account/logout" class="btn btn-default btn-flat">退出</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <!--                            <li>
                                                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                                                        </li>-->
                        </ul>
                    </div>
                </nav>
            </header>

            <!-- =============================================== -->

            <!-- Left side column. contains the sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <?php if(is_array($courts)){foreach($courts AS $court) { ?>
                        <li class="treeview active">
                            <a href="#">
                                <i class="fa fa-building"></i> <span><?php echo $court[name]; ?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if(is_array($court[forum])){foreach($court[forum] AS $forum) { ?>
                                <li>
                                    <a href="#forum_<?php echo $forum[id]; ?>">
                                        <i class="fa fa-building-o"></i> <?php echo $forum[name]; ?>
                                        <span class="pull-right-container">
                                            <span class="label label-primary pull-right"><?php echo $forum[roomtype]=='train'?'培训宿舍':($forum[roomtype]=='overtime'?'加班宿舍':'常住宿舍'); ?></span>
                                        </span>
                                    </a>
                                </li>
                                <?php }}?>

                            </ul>
                        </li>
                        <?php }}?>

                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- =============================================== -->

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <?php if(is_array($courts)){foreach($courts AS $court) { ?>
                <!-- Content Header (Page header) -->
                <section class="content-header" id="court_<?php echo $court[id]; ?>">
                    <h1><?php echo $court[name]; ?></h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php if(is_array($court[forum])){foreach($court[forum] AS $forum) { ?>
                    <!-- Default box -->
                    <a class="target-fix" name="forum_<?php echo $forum[id]; ?>"></a>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title mr20"><?php echo $forum[name]; ?></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="收缩">
                                    <i class="fa fa-minus"></i></button>

                            </div>
                        </div>
                        <div class="box-body ">
                            <?php if(is_array($forum[f])){foreach($forum[f] AS $index=>$brooms) { ?>
                            <div class="floor">
                                <div class="col-md-1 col-xs-2 text-center clearspace"><?php echo $floornames[$index]; ?></div>
                                <div class="col-md-11 col-xs-10">
                                    <?php if(is_array($brooms)){foreach($brooms AS $broom) { ?>
                                    <?php if($forum[mold]=='sig'){?>
                                    <div class="col-md-1 col-xs-3 clearspace text-center">
                                        <div class="sroom <?php echo count($broom[order])>0?(count($broom[order])==$broom[bed_num]?'label-danger':'label-warning'):''; ?>" data-toggle="dropdown">
                                            <h4 class="text_ellipsis"><?php echo $broom[broom]; ?></h4>
                                            <i class="fa"><?php echo count($broom[order]); ?>/<?php echo $broom[bed_num]; ?></i>
                                        </div>
                                        <div class="dropdown-menu">
                                            <?php if(is_array($broom[bed])){foreach($broom[bed] AS $bed) { ?>
                                            <div data-id="<?php echo $bed[id]; ?>" class="bed <?php echo array_key_exists($bed[id],$broom[order])?'label-danger':'label-success'; ?>"><?php echo $bed[bed]; ?></div>
                                            <?php }}?>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    <div class="col-md-<?php echo count($broom[sroom]); ?> col-xs-<?php echo count($broom[sroom])*3; ?> clearspace">
                                        <div class="broom row clearspace text-center ">
                                            <h4 class="text_ellipsis col-xs-12 <?php echo count($broom[order])>0?(count($broom[order])==$broom[bed_num]?'label-danger':'label-warning'):''; ?>"><?php echo $broom[broom]; ?></h4>
                                            <?php if(is_array($broom[sroom])){foreach($broom[sroom] AS $sroom) { ?>
                                            <div class="col-xs-<?php echo 12/count($broom[sroom]); ?> clearspace text-center">
                                                <div class="sroom <?php echo count($sroom[order])>0?(count($sroom[order])==$sroom[bed_num]?'label-danger':'label-warning'):''; ?>">
                                                    <div data-toggle="dropdown">
                                                        <h4 class="text_ellipsis"><?php echo $sroom[sroom]; ?></h4>
                                                        <i class="fa"><?php echo count($sroom[order]); ?>/<?php echo $sroom[bed_num]; ?></i>
                                                    </div>
                                                    <div class="dropdown-menu">
                                                        <?php if(is_array($sroom[bed])){foreach($sroom[bed] AS $bed) { ?>
                                                        <div data-id="<?php echo $bed[id]; ?>" class="bed <?php echo array_key_exists($bed[id],$sroom[order])?'label-danger':'label-success'; ?>"><?php echo $bed[bed]; ?></div>

                                                        <?php }}?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }}?>

                                        </div>


                                    </div>
                                    <?php }?>
                                    <?php }}?>
                                </div>
                            </div>
                            <?php }}?>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <?php if($forum[mold]=='sig'){?>
                            <span class="label label-primary">房间数：<?php echo $forum[total_broom]; ?></span>
                            <?php } else { ?>
                            <span class="label label-primary">套间数：<?php echo $forum[total_broom]; ?></span>
                            <span class="label label-primary">小室数：<?php echo $forum[total_sroom]; ?></span>
                            <?php }?>
                            <span class="label label-info">床位数：<?php echo $forum[total_bed]; ?></span>
                            <span class="label label-warning">入住数：<?php echo $forum[check_bed]; ?></span>
                        </div>
                        <!-- /.box-footer-->
                    </div>
                    <!-- /.box -->
                    <?php }}?>

                </section>

                <!-- /.content -->
                <?php }}?>
            </div>
            <!-- /.content-wrapper -->

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.3.8
                </div>
                <strong>Copyright &copy; 2014-2016 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
                reserved.
            </footer>



        </div>
        <!-- ./wrapper -->

        <!-- jQuery 2.2.3 -->
        <script src="<?php echo WEB_ROOT; ?>/themes/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="<?php echo WEB_ROOT; ?>/themes/bootstrap/js/bootstrap.min.js"></script>
        <!-- SlimScroll -->
        <script src="<?php echo WEB_ROOT; ?>/themes/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="<?php echo WEB_ROOT; ?>/themes/plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo WEB_ROOT; ?>/themes/dist/js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="<?php echo WEB_ROOT; ?>/themes/dist/js/demo.js"></script>

    </body>
</html>
