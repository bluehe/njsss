// JavaScript Document
var img_error = "<img src=/themes/default/images/admin/check_error.gif>";
function checkupload(obj) {
    var filename = $('userfile').value;
    if (filename == "") {
        $('tip_upload').innerHTML = img_error + "请选择图片";
        return false;
    }
    var fix = filename.match(/^(.*)(\.)(.{1,8})$/)[3].toLowerCase();
    if (fix != 'gif' && fix != 'jpg' && fix != 'jpeg' && fix != 'png') {
        $('tip_upload').innerHTML = img_error + "文件不是图片格式";
        return false;
    }
    jQuery.ajax({
        type: 'GET',
        data: '',
        url: WEB_ROOT + '/admin/doupload?filename=' + filename + '&dir=' + obj,
        success: function(data) {
            if (data == 'no') {
                $('tip_upload').innerHTML = img_error + "存在同名文件";
                return false;
            }
            if (data == 'yes') {
                $('tip_upload').innerHTML = "";
                $('uploadform').submit();
            }

        },
        dataType: 'text',
        error: function() {
            return false;
        }
    });
}


function showupload()
{
    //显示上传窗口
    if ($('upload').style.display == "none") {
        $('upload').style.display = "";
    } else {
        $('tip_upload').innerHTML = "";
        $('upload').style.display = "none";
    }
}

function empupload(obj)
{
    //显示上传窗口
    $('empdir').value = obj;
    if ($('upload').style.display == "none") {
        $('upload').style.display = "";
    } else {
        $('tip_upload').innerHTML = "";
        $('upload').style.display = "none";
    }
}

function hideupload() {
    //隐藏上传窗口
    $('uploadtip').innerHTML = "";
    $('upload').style.display = "none";
}