//计算天数差的函数，通用
function  DateDiff(sDate1, sDate2) {    //sDate1和sDate2是2002-12-18格式
    var aDate, oDate1, oDate2, iDays;
    aDate = sDate1.split("-");
    oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);    //转换为12-18-2002格式
    aDate = sDate2.split("-");
    oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);
    iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24);    //把相差的毫秒数转换为天数
    return  iDays;
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
    location.href = WEB_ROOT + '/work/doexcel?r=' + r + '&t=' + id + '&' + s;

    //window.open(WEB_ROOT + '/home/doexcel?t=' + id + '&' + s);
}
