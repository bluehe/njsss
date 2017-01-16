<?php include template("html_header");?>
<link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/css/account.css?v=<?php echo $INI['system']['version']; ?>" />
<div class="container">
    <div class="login_body">
        <div class="login_body_logo"><a href="<?php echo WEB_ROOT; ?>/"><img src="<?php echo WEB_ROOT; ?>/themes/default/images/logo.gif" /></a></div>
        <p>欢迎登录！</p>
        <form method="post" action="<?php echo WEB_ROOT; ?>/account/login">
            <input type="hidden" name="form_type" value="login" />
            <div class="infoinput"><label>账&nbsp;&nbsp;&nbsp;&nbsp;号</label><input type="text" name="username" value='<?php echo $username!=""?$username:""; ?>' placeholder="请输入用户名/电子邮箱/联系电话" /></div>
            <div class="infoinput"><label>密&nbsp;&nbsp;&nbsp;&nbsp;码</label><input type="password" name="password" placeholder="请输入账户密码" /></div>

            <div class="infoinput <?php echo in_array('2',$seccodestatus)?'':'hide'; ?>"><label>验证码</label><input type="text" name="seccode" placeholder="请输入验证码" class="codeinput" /><img id="bottomtd_img" src="<?php echo WEB_ROOT; ?>/account/create_seccode" title="看不清楚，换一张" /></div>

            <div class="submitinfo pull-right" ><label class="pull-left"><input type="checkbox" name="rememberme" <?php echo $username!=""?'checked':''; ?> class="remember" />记住我的登录状态</label></div>
            <div class="submitinfo pull-right"><input type="submit" value="登&nbsp;&nbsp;&nbsp;&nbsp;录" class="btn login_submit" /></div>
            <div class="submitinfo pull-right">
                <div class="pull-left nonelogin"><a href="<?php echo WEB_ROOT; ?>/account/passwordreset">忘记密码？</a></div>
                <div class="pull-right nonelogin"><a href="<?php echo WEB_ROOT; ?>/account/register">立即注册</a></div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
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
<?php include template("html_footer");?>
