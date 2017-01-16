/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var secflag = 0;
var userflag = 0, paswflag = 0, paswqflag = 0, emailflag = 0, telflag = 0;
var img_error = "<img src=/themes/default/images/admin/check_error.gif>";
var img_right = "<img src=/themes/default/images/admin/check_right.gif>";
function trim(str) {
    //删除左右两端的空格
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
function entry(event) {
    if (event.keyCode == 13)
    {
        event.keyCode = 9;
    }
}
function checksec(obj) {
//var obj = document.getElementById('codeinput').value;
    jQuery.ajax({
        type: 'GET',
        data: '',
        url: WEB_ROOT + '/account/dosec?seccode=' + obj,
        success: function(data) {
            if (data === 'yes') {
                document.getElementById('tip_sec').innerHTML = img_right;
                secflag = 1;
            }
            if (data === 'no') {
                document.getElementById('tip_sec').innerHTML = img_error;
                secflag = 0;
            }
        },
        dataType: 'text',
        error: function() {
            secflag = 0;
        }
    });
}

function checkrepass() {
    var email = trim(document.getElementById('mailinput').value);
    var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
    if (email == "") {
        document.getElementById('tip_email').innerHTML = img_error + "电子邮箱不能为空！";
        document.getElementById('mailinput').focus();
        return false;
    } else if (!pattern.test(email)) {
        document.getElementById('tip_email').innerHTML = img_error + "电子邮箱格式错误！";
        document.getElementById('mailinput').focus();
        return false;
    } else {
        document.getElementById('tip_email').innerHTML = "您注册时的电子邮箱";
    }
    if (!secflag)
    {
        document.getElementById('tip_seccode').innerHTML = img_error;
        document.getElementById('seccode').focus();
        return false;
    }
    return true;
}

function checkdl() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    if (username === "") {
        document.getElementById('tip').innerHTML = img_error + "用户名不能为空！";
        document.getElementById('username').focus();
        return false;
    }
    if (password === "") {
        document.getElementById('tip').innerHTML = img_error + "密码不能为空！";
        document.getElementById('password').focus();
        return false;
    }
    if (!secflag) {
        document.getElementById('tip').innerHTML = img_error + "验证码错误！";
        document.getElementById('codeinput').focus();
        return false;
    }
    return true;
}
function checktel(obj) {
    if (obj.length > 0) {
        var pattern = /^((0\d{2,3}-\d{7,8}(-\d{1,6})?)|(1[34578]\d{9}))$/;
        if (!pattern.test(obj)) {
            document.getElementById('tip_tel').innerHTML = img_error + "电话格式错误！";
            telflag = 0;
        } else {
            jQuery.ajax({
                type: 'GET',
                data: '',
                url: WEB_ROOT + '/account/douser?username=' + obj,
                success: function(data) {
                    if (data) {
                        document.getElementById('tip_tel').innerHTML = img_error + "电话已经存在！";
                        telflag = 0;
                    } else {
                        document.getElementById('tip_tel').innerHTML = img_right;
                        telflag = 1;
                    }
                },
                dataType: 'text',
                error: function() {
                    telflag = 0;
                }
            });
        }
    } else {
        document.getElementById("tip_tel").innerHTML = img_error + "请输入电话";
        telflag = 0;
    }
}

function checkemail(obj) {
    if (obj.length > 0) {
        var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
        if (!pattern.test(obj)) {
            document.getElementById('tip_email').innerHTML = img_error + "电子邮箱格式错误！";
            emailflag = 0;
        } else {
            jQuery.ajax({
                type: 'GET',
                data: '',
                url: WEB_ROOT + '/account/douser?username=' + obj,
                success: function(data) {
                    if (data) {
                        document.getElementById('tip_email').innerHTML = img_error + "电子邮箱已经存在！";
                        emailflag = 0;
                    } else {
                        document.getElementById('tip_email').innerHTML = img_right;
                        emailflag = 1;
                    }
                },
                dataType: 'text',
                error: function() {
                    emailflag = 0;
                }
            });
        }
    } else {
        document.getElementById("tip_email").innerHTML = img_error + "请输入电子邮箱";
        emailflag = 0;
    }
}

function checkuser(obj) {
    obj = trim(obj);
    var l = obj.length;
    if (obj !== "") {
//var pattern = /^[a-zA-Z0-9]+$/;
        var re = /^[0-9]*$/;
        var res = /^[\u4e00-\u9fa5a-z0-9]+$/gi;
        if (re.test(obj)) {
            document.getElementById('tip_username').innerHTML = img_error + "用户名不能全为数字！";
            userflag = 0;
        } else if (!res.exec(obj)) {
            document.getElementById('tip_username').innerHTML = img_error + "用户名为数字或英文字母！";
            userflag = 0;
        } else if (l < 4 || l > 16) {
            document.getElementById('tip_username').innerHTML = img_error + "用户名在4-16个字符之间";
            userflag = 0;
        } else {
            jQuery.ajax({
                type: 'GET',
                data: '',
                url: WEB_ROOT + '/account/douser?username=' + obj,
                success: function(data) {
                    if (data) {
                        document.getElementById('tip_username').innerHTML = img_error + "用户名已被注册！";
                        userflag = 0;
                    } else {
                        document.getElementById('tip_username').innerHTML = img_right;
                        userflag = 1;
                    }
                },
                dataType: 'text',
                error: function() {
                    userflag = 0;
                }
            });
        }
    } else {
        document.getElementById("tip_username").innerHTML = img_error + "4-16个中文、数字或英文字母，不能全为数字";
        userflag = 0;
    }

}

function checkpasw(obj)
{
    obj = trim(obj);
    var l = obj.length;
    if (obj !== "") {
        if (l < 4 || l > 16) {
            document.getElementById('tip_password').innerHTML = img_error + "密码在4-16个字符间";
            paswflag = 0;
        } else {
            var patrn = /^[a-zA-Z0-9]+$/;
            if (!patrn.exec(obj)) {
                document.getElementById('tip_password').innerHTML = img_error + "密码为数字或英文字母！";
                paswflag = 0;
            } else {
                document.getElementById('tip_password').innerHTML = img_right;
                paswflag = 1;
            }
        }
    } else {
        paswflag = 0;
        document.getElementById('tip_password').innerHTML = img_error + "4-16个数字或英文字母";
    }
}

function checkpaswq(obj)
{
    obj = trim(obj);
    var a = trim(document.getElementById('password').value);
    if (obj.length > 0)
    {
        if (obj === a)
        {
            document.getElementById('tip_password2').innerHTML = img_right;
            paswqflag = 1;
        }
        else
        {
            document.getElementById('tip_password2').innerHTML = img_error + "两次密码输入不一致！";
            paswqflag = 0;
        }
    }
    else
    {
        document.getElementById('tip_password2').innerHTML = img_error + "再次输入密码 ";
        paswqflag = 0;
    }
}

function checkall() {
    if (!emailflag) {
        document.getElementById('tip_email').innerHTML = img_error;
        document.getElementById('useremail').focus();
        return false;
    }

    if (!userflag) {
        document.getElementById('tip_username').innerHTML = img_error;
        document.getElementById('username').focus();
        return false;
    }
    if (!paswflag) {
        document.getElementById('tip_password').innerHTML = img_error;
        document.getElementById('password').focus();
        return false;
    }
    if (!paswqflag) {
        document.getElementById('tip_password2').innerHTML = img_error;
        document.getElementById('password2').focus();
        return false;
    }

    if (trim(document.getElementById('company').value) === "") {
        document.getElementById('tip_company').innerHTML = "请输入公司名称";
        document.getElementById('company').focus();
        return false;
    }
    if (trim(document.getElementById('contacts').value) === "") {
        document.getElementById('tip_contacts').innerHTML = "请输入联系人";
        document.getElementById('contacts').focus();
        return false;
    }
    if ((trim(document.getElementById('tel1').value) === "" || trim(document.getElementById('tel2').value) === "") && trim(document.getElementById('tel4').value) === "") {
        document.getElementById('tip_tel').innerHTML = "请输入联系电话";
        document.getElementById('tel4').focus();
        return false;
    }
    if (!document.getElementById('readed').checked) {
        document.getElementById('tip_readed').innerHTML = "请确认协议";
        document.getElementById('readed').focus();
        return false;
    }
    if (!secflag) {
        document.getElementById('tip_sec').innerHTML = img_error;
        document.getElementById('seccode').focus();
        return false;
    }

    return true;
}

function checkmember()
{

    if (!userflag)
    {
        $('tip_username').innerHTML = img_error;
        $('username').focus();
        return false;
    }
    if (!paswflag)
    {
        $('tip_password').innerHTML = img_error;
        $('password').focus();
        return false;
    }
    if (!paswqflag)
    {
        $('tip_password2').innerHTML = img_error;
        $('password2').focus();
        return false;
    }
    if (!emailflag)
    {
        $('tip_email').innerHTML = img_error;
        $('useremail').focus();
        return false;
    }
    if (!telflag)
    {
        $('tip_tel').innerHTML = img_error;
        $('usertel').focus();
        return false;
    }
}