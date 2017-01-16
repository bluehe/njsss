function time2string(time) {
    var datetime = new Date();
    datetime.setTime(time);
    var year = datetime.getFullYear();
    var month = datetime.getMonth() + 1 < 10 ? "0" + (datetime.getMonth() + 1) : datetime.getMonth() + 1;
    var date = datetime.getDate() < 10 ? "0" + datetime.getDate() : datetime.getDate();
    var hour = datetime.getHours() < 10 ? "0" + datetime.getHours() : datetime.getHours();
    var minute = datetime.getMinutes() < 10 ? "0" + datetime.getMinutes() : datetime.getMinutes();
    var second = datetime.getSeconds() < 10 ? "0" + datetime.getSeconds() : datetime.getSeconds();
    return year + "-" + month + "-" + date + " " + hour + ":" + minute + ":" + second;
}
function loadmessage(id, s) {
    $.ajax({
        type: "POST",
        url: "/home/domessage",
        data: "form_type=load&id=" + id + "&s=" + s,
        success: function(data) {

            var ss = data.split("|");
            $('.loading_small').addClass('hide');
            if (ss[1]) {
                var jsonText = jQuery.parseJSON(ss[1]);
                $.each(jsonText, function(idx, item) {
                    if (item.url) {
                        item.title = '<a href="' + item.url + '">' + item.title + '</a>';
                    }
                    $('.message-list').append('<div class="message-item" data-id="' + item.id + '"><em class="status"><i class="' + (s ? 'icon-envelope' : 'icon-envelope-alt') + '"></i></em><div class="message-box"><div class="hd mb-5 bold">' + item.title + '</div><div class="content mb-5 hl">' + item.content + '</div><div><span class="fs-12 c-999">' + time2string(item.send_time * 1000) + '</span></div></div></div>');
                });
                $(".main-bd").getNiceScroll().resize();
                $('.message-list').removeClass('hide');
                $('.nodata-holder').addClass('hide');
                if (ss[0] == 'yes') {
                    $('.common-more').removeClass('hide');
                } else {
                    $('.common-more').addClass('hide');
                }

            } else if (id == '') {
                $('.nodata-holder').removeClass('hide');
                $('.common-more').addClass('hide');
            } else {
                $('.common-more').addClass('hide');
            }

        }
    });
}
function readmessage(id) {
    $.ajax({
        type: "POST",
        url: WEB_ROOT + "/home/domessage",
        data: "form_type=read&id=" + id,
        success: function(data) {
            var ss = data.split("|");
            if (ss[0]) {
                $('.notice-text').text(ss[1]);
                if (id == 'all') {
                    $(".message-item").find('.icon-envelope').removeClass('icon-envelope').addClass('icon-envelope-alt');
                    $('.dropdown-menu-toggle').removeClass('open');
                } else {
                    var ids = id.split(",");
                    for (var i = 0; i < ids.length; i++) {
                        if (ids[i] != "") {
                            $("div[data-id=" + ids[i] + "]").find('.icon-envelope').removeClass('icon-envelope').addClass('icon-envelope-alt');
                        }
                    }
                }
            }

        }
    });
}

function filesize(fsize) {
    var size = '';
    if (fsize > 1024) {
        fsize = Math.round(fsize / 1024);
        if (fsize > 1024) {
            fsize = Math.round(fsize / 1024);
            size = fsize + 'MB';
        } else {
            size = fsize + 'KB';
        }
    } else {
        size = fsize + 'B';
    }
    return size;
}

function loadmore(id, s) {
    $.ajax({
        type: "POST",
        url: "/home/domore",
        data: "t=" + id + "&" + s,
        success: function(data) {

            var ss = data.split("^|^");

            if (ss[1]) {

                var jsonText = jQuery.parseJSON(ss[1]);
                if (id == 'setting_notice_select') {
                    $.each(jsonText, function(idx, item) {
                        $('.materiel_body').append('<tr><td>' + item.send_date + '</td><td>' + item.title + '</td><td>' + item.recode + '</td><td class="hl" style="max-width: 300px">' + item.content + '</td><td><a class="btn btn-primary" href="' + WEB_ROOT + '/home/setting_notice?id=' + item.id + '">查看</a></td></tr>');
                    });
                } else if (id == 'setting_user_select') {
                    $.each(jsonText, function(idx, item) {
                        $('.materiel_body').append('<tr><td class="mwd-40"><input type="checkbox" class="subcheck" name="id" value="' + item.user_id + '" /></td><td>' + item.username + '</td><td>' + item.email + '</td><td>' + item.tel + '</td><td>' + item.reg_ip + '</td><td>' + item.reg_time + '</td><td>' + item.last_time + '</td><td>' + item.stats + '</td><td><a class="btn btn-primary" href="' + WEB_ROOT + '/home/setting_user?id=' + item.user_id + '">编辑</a></td></tr>');
                    });
                } else if (id == 'site_website_select') {
                    $.each(jsonText, function(idx, item) {
                        var str = '<tr><td class="mwd-40"><input type="checkbox" class="subcheck" name="id" value="' + item.id + '" /></td>';
                        if (item.admin == '1') {
                            str += '<td>' + item.username + '</td>';
                        }
                        str += '<td><a class="domain" href="http://' + item.domain + '" target="_blank">' + item.domain + '</a></td><td>' + item.name + '</td><td><img src="' + item.logo + '" height="30" /></td><td>' + item.show_type + '</td><td>' + item.style + '</td>';
//                        str += '<td>' + item.click_count + '</td><td>' + item.click_num + '</td>';
                        str += '<td class="template"></td><td><i class="icon-stop icon-2x color" style="color:#' + item.color + '" data-color="' + item.color + '"></i></td><td><span class="change label radius ' + item.label + '" data-id="' + item.id + '" data-label="stat" data-value="' + item.stats_b + '">' + item.stats + '</span></td><td><i class="status icon-circle"></i></td><td class="version"></td><td class="ip"></td><td>&nbsp;&nbsp;<a href="' + WEB_ROOT + '/home/site_website?id=' + item.id + '" title="编辑"><i class="icon-pencil"></i></a>&nbsp;&nbsp;<i class="icon-trash sure change" data-id="' + item.id + '" data-type="delete"></i></td></tr> ';
                        $('.materiel_body').append(str);
                    });
                }

                $(".main-bd").getNiceScroll().resize();
            }
            if (ss[0] > 0) {
                $('.more').attr('data-id', ss[0]);
            } else {
                $('.more').closest('.order-list').hide();
            }

        }
    });
}

function allchk() {
    var chknum = $(".subcheck").size(); //选项总个数
    var chk = 0;
    $(".subcheck").each(function() {
        if ($(this).prop("checked")) {
            chk++;
        }
    });
    if (chknum == chk) {//全选
        $(".selectall").prop("checked", true);
    } else {//不全选
        $(".selectall").prop("checked", false);
    }
}

function loadexcel(id, s, r) {
    r = r ? r : 0;
    location.href = WEB_ROOT + '/home/doexcel?r=' + r + '&t=' + id + '&' + s;

    //window.open(WEB_ROOT + '/home/doexcel?t=' + id + '&' + s);
}

function showexcel(id, s) {

    if (confirm("导出需要较长时间，是否继续？\n[提示：请尽量保证域名通讯正常，如果数据处理超过100秒将强制停止，请选择分批下载]")) {
        var r = Date.parse(new Date());
        loadexcel(id, s, r);
        $('#cover').show();
        $('.progress-bar').attr('aria-valuenow', 0).css('width', "0%");
        $('.progress-text').css('color', '#000');
        $('.progress-text').text('数据处理中···');
        get_connect(r);
    }
}

function get_connect(r) {
    $.ajax({
        type: "POST",
        url: "/home/doprogress",
        data: "r=" + r,
        //async: false,
        success: function(data) {

            var ss = data.split("|");
            if (ss[1] > 0) {
                var p = ss[0] * 100 / ss[1];
                $('.progress-bar').attr('aria-valuenow', p).css('width', p + "%");
                $('.progress-text').text(ss[0] + '/' + ss[1]);
                if (p > 50) {
                    $('.progress-text').css('color', '#fff');
                }
            }
            if (ss[0] == ss[1]) {
                $('#cover').hide();
            } else {
                setTimeout(function() {
                    get_connect(r);
                }, 1000);
            }

        }
    });
}

function invoice() {
    var type = $(':radio[class=type]:checked').val();
    if (type == 'person') {
        $('.invoice_name').val('个人').attr("readonly", "readonly").addClass("disabled");
        $(':radio[class=mold][value=common]').prop("checked", true);
        $(':radio[class=mold]').attr('disabled', true);
    } else {
        $('.invoice_name').removeAttr("readonly").removeClass("disabled");
        $(':radio[class=mold]').attr('disabled', false);
    }
    var mold = $(':radio[class=mold]:checked').val();
    if (mold == 'common') {
        $('.special').addClass('hide');
    } else {
        $('.special').removeClass('hide');
    }

}

function dochange(table, _this) {
    var id = _this.attr('data-id') ? _this.attr('data-id') : '';
    var label = _this.attr('data-label') ? _this.attr('data-label') : '';
    var value = _this.attr('data-value') ? _this.attr('data-value') : '';
    var pkname = _this.attr('data-pkname') ? _this.attr('data-pkname') : 'id';
    var type = _this.attr('data-type') ? _this.attr('data-type') : 'update';
    $.ajax({
        type: "POST",
        url: WEB_ROOT + "/home/dochange",
        data: "type=" + type + "&table=" + table + "&pkname=" + pkname + "&id=" + id + "&label=" + label + "&value=" + value,
//async: false,
        success: function(data) {
            if (data == 'success') {
                if ((table == 'article' || table == 'case') && label == 'is_open') {
                    var v = value == 1 ? 0 : 1;
                    var t = value == 1 ? '启用' : '关闭';
                    _this.toggleClass('label-success').toggleClass('label-default').attr('data-value', v).text(t);
                } else if ((table == 'article' || table == 'case') && label == 'is_top') {
                    var v = value == 1 ? 0 : 1;
                    _this.toggleClass('label-danger').attr('data-value', v);
                } else if (table == 'version' && label == 'auto') {
                    var v = value == 1 ? 0 : 1;
                    var t = value == 1 ? '是' : '否';
                    _this.toggleClass('label-success').toggleClass('label-default').attr('data-value', v).text(t);
                } else if (table == 'category' && label == 'show_in_nav') {
                    var v = value == 1 ? 0 : 1;
                    var t = value == 1 ? '显示' : '不显示';
                    _this.toggleClass('label-success').toggleClass('label-default').attr('data-value', v).text(t);
                } else if (table == 'user' && label == 'stat') {
                    var v = value == 1 ? 0 : 1;
                    var t = value == 1 ? '正常' : '锁定';
                    _this.toggleClass('label-success').toggleClass('label-danger').attr('data-value', v).text(t);
                } else if ((table == 'goods' || table == 'battery' || table == 'sidebar' || table == 'banner' || table == 'nav' || table == 'company' || table == 'link' || table == 'website' || table == 'version' || table == 'industry' || table == 'service') && label == 'stat') {
                    var v = value == 1 ? 0 : 1;
                    var t = value == 1 ? '启用' : '关闭';
                    _this.toggleClass('label-success').toggleClass('label-default').attr('data-value', v).text(t);
                } else if ((table == 'goods' || table == 'battery' || table == 'article' || table == 'case' || table == 'sidebar' || table == 'banner' || table == 'nav' || table == 'company' || table == 'link' || table == 'website' || table == 'version' || table == 'industry' || table == 'service' || table == 'role' || table == 'permission' || table == 'user') && type == 'delete') {
                    _this.closest('tr').remove();
                }
            } else if (data == 'fail') {
                if (table == 'battery' && label == 'stat' && type == 'update') {
                    toastr.error('站点与类别用户不匹配，请修改相应信息！');
                }
            }
        }
    });
}