<?php include template("html_header_login");?>
<body class="hold-transition login-page">


    <div class="login-box">
        <div class="login-logo">
            <a href="<?php echo WEB_ROOT; ?>/"><b><?php echo $INI['system']['sitename']; ?></b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">欢迎登录！</p>

            <form action="<?php echo WEB_ROOT; ?>/account/login" method="post">
                <input type="hidden" name="form_type" value="login" />
                <div class="form-group has-feedback">
                    <input type="text" name="username" value='<?php echo $username!=""?$username:""; ?>' class="form-control" placeholder="请输入用户名/电子邮箱/联系电话">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="请输入密码">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback <?php echo in_array('2',$seccodestatus)?'':'hide'; ?>">
                    <div class="row"><div class="col-xs-8"><input type="text" class="form-control" name="seccode" placeholder="请输入验证码"></div><div class="col-xs-4"><img id="bottomtd_img" src="<?php echo WEB_ROOT; ?>/account/create_seccode" title="看不清楚，换一张" style="cursor:pointer" /></div></div>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="rememberme" <?php echo $username!=""?'checked':''; ?> /> 记住我的登录状态
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                    </div>
                    <!-- /.col -->
                </div>

            </form>

            <a href="<?php echo WEB_ROOT; ?>/account/passwordreset">忘记密码？</a>

            <a class="pull-right" href="<?php echo WEB_ROOT; ?>/account/register">注册新账号</a>


        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 2.2.3 -->
    <script src="<?php echo WEB_ROOT; ?>/themes/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="<?php echo WEB_ROOT; ?>/themes/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo WEB_ROOT; ?>/themes/default/js/toastr.min.js"></script>
    <?php if(Session::Get('notice',true)){?>
    <script type="text/javascript">
        var txt = "<?php echo Session::Get('message', true); ?>";
        var type = "<?php echo Session::Get('message_type', true); ?>";
        var title = "<?php echo Session::Get('title', true); ?>";
        toastr.options = {
            positionClass: "toast-top-full-width",
            newestOnTop: true
        }
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
    <script>

        function create_code() {
            $('#bottomtd_img').attr('src', "<?php echo WEB_ROOT; ?>/account/create_seccode?" + Math.random() * 10000);
        }
        $('#bottomtd_img').on('click', function() {
            create_code();
        });
        $('form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: WEB_ROOT + "/account/dologin",
                data: $(this).serialize(),
                // async: false,
                success: function(data) {

                    var ss = data.split("|");
                    toastr.options = {
                        positionClass: "toast-top-full-width",
                        newestOnTop: true
                    }
                    if (ss[0] == 'error') {

                        toastr.error(ss[1]);
                        create_code();
                    } else if (ss[0] == 'success') {

                        toastr.success(ss[1]);
                    } else if (ss[0] == 'redirect') {
                        location.href = ss[1];
                    }

                }
            });
        });
    </script>

</body>
</html>
