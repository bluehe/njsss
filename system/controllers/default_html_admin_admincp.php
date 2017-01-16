<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $INI['system']['sitetitle']; ?></title>
        <link href="<?php echo WEB_ROOT; ?>/favicon.ico" rel="shortcut icon" />
        <link rel="stylesheet" href="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/css/admincp.css" type="text/css" media="all" />
        <script src="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/js/admin/common.js" type="text/javascript"></script>
        <script src="<?php echo WEB_ROOT; ?>/themes/<?php echo $INI['system']['skin']; ?>/js/admin/admincp.js" type="text/javascript"></script>
    </head>
    <body style="margin: 0px" scroll="no">
        <div id="append_parent"></div>
        <table cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tr>
                <td colspan="2" height="90"><div class="mainhd">
                        <div class="logo">DMS Administrator's Control Panel</div>
                        <div class="uinfo">
                            <p>您好, <em><?php echo $login_user['username']; ?></em> [ <a href="<?php echo WEB_ROOT; ?>/account/logout" target="_top">退出</a> ]</p>
                            <p class="btnlink"><a href="<?php echo WEB_ROOT; ?>/index" target="_blank">系统首页</a></p>
                        </div>
                        <div class="navbg"></div>
                        <div class="nav">
                            <ul id="topmenu">
                                <?php if(is_array($a)){foreach($a AS $key=>$one) { ?>
                                <li><em><a href="javascript:void(0);" id="header_<?php echo $key; ?>" hidefocus="true" onClick="toggleMenu('<?php echo $key; ?>', '<?php echo $one[0]; ?>');"><?php echo $one[1]; ?></a></em></li>
                                <?php }}?>
                            </ul>
                            <div class="currentloca">
                                <p id="admincpnav"></p>
                            </div>
                            <div class="navbd"></div>
                            <div class="sitemapbtn">
                                <div style="float: left; margin:-5px 10px 0 0"> </div>
                                <span id="add2custom"></span></div>
                        </div>
                    </div></td>
            </tr>
            <tr>
                <td valign="top" width="160" class="menutd"><div id="leftmenu" class="menu">
                        <?php if(is_array($b)){foreach($b AS $key=>$one) { ?>
                        <ul id="menu_<?php echo $key; ?>" style="display: none">
                            <?php if(is_array($one)){foreach($one AS $url=>$name) { ?>
                            <li><a href="<?php echo $url; ?>" hidefocus="true" target="main" <?php echo $url=='home'?'class="tabon"':''; ?>><?php echo $name; ?></a></li>
                            <?php }}?>
                        </ul>
                        <?php }}?>
                    </div></td>
                <td valign="top" width="100%" class="mask" id="mainframes"><iframe src="home" id="main" name="main" onload="mainFrame(0)" width="100%" height="100%" frameborder="0" scrolling="yes" style="overflow: visible;display:"></iframe></td>
            </tr>
        </table>
        <div class="custombar" id="custombarpanel"> &nbsp;<span id="custombar"></span><span id="custombar_add"></span> </div>
        <div class="copyright">
            <p><a href="<?php echo $AUTHOR_WEB; ?>" target="_blank">何文斌</a>&nbsp;版权所有&nbsp;</p>
            <p>&nbsp;<?php echo $INI['system']['sitename']; ?></p>
        </div>
        <script type="text/JavaScript">
            var headers = new Array(<?php echo $str!=''?$str:''; ?>);
            var admincpfilename = 'admincp.php';
            var menukey = '', custombarcurrent = 0;
            function toggleMenu(key, url) {
            if(key == 'index' && url == 'index') {
            if(BROWSER.ie) {
            doane(event);
            }
            parent.location.href = admincpfilename;
            return false;
            }
            menukey = key;
            for(var k in headers) {
            if($('menu_' + headers[k])) {
            $('menu_' + headers[k]).style.display = headers[k] == key ? '' : 'none';
            }
            }
            var lis = $('topmenu').getElementsByTagName('li');

            for(var i = 0; i < lis.length; i++) {
            if(lis[i].className == 'navon') lis[i].className = '';
            }
            $('header_' + key).parentNode.parentNode.className = 'navon';
            if(url) {
            parent.mainFrame(0);
            parent.main.location = url;
            var hrefs = $('menu_' + key).getElementsByTagName('a');
            for(var j = 0; j < hrefs.length; j++) {
            hrefs[j].className = hrefs[j].getAttribute('href',2) == url ? 'tabon' : (hrefs[j].className == 'tabon' ? '' : hrefs[j].className);

            }
            }
            return false;
            }

            function initCpMenus(menuContainerid) {
            var key = '';
            var hrefs = $(menuContainerid).getElementsByTagName('a');
            for(var i = 0; i < hrefs.length; i++) {
            if(menuContainerid == 'leftmenu' && !key && 'action=home'.indexOf(hrefs[i].href.substr(hrefs[i].href.indexOf(admincpfilename + '?action=') + 12)) != -1) {
            key = hrefs[i].parentNode.parentNode.id.substr(5);
            hrefs[i].className = 'tabon';
            }
            if(!hrefs[i].getAttribute('ajaxtarget')) hrefs[i].onclick = function() {
            if(menuContainerid != 'custommenu') {
            var lis = $(menuContainerid).getElementsByTagName('li');
            for(var k = 0; k < lis.length; k++) {
            if(lis[k].firstChild.className != 'menulink') lis[k].firstChild.className = '';
            }
            if(this.className == '') this.className = menuContainerid == 'leftmenu' ? 'tabon' : 'bold';
            }
            if(menuContainerid != 'leftmenu') {
            var hk, currentkey;
            var leftmenus = $('leftmenu').getElementsByTagName('a');
            for(var j = 0; j < leftmenus.length; j++) {
            hk = leftmenus[j].parentNode.parentNode.id.substr(5);
            if(this.href.indexOf(leftmenus[j].href) != -1) {
            leftmenus[j].className = 'tabon';
            if(hk != 'index') currentkey = hk;
            } else {
            leftmenus[j].className = '';
            }
            }
            if(currentkey) toggleMenu(currentkey);
            hideMenu();
            }
            }
            }
            return key;
            }
            var header_key = initCpMenus('leftmenu');
            toggleMenu(header_key ? header_key : 'index');

            function resetEscAndF5(e) {
            e = e ? e : window.event;
            actualCode = e.keyCode ? e.keyCode : e.charCode;
            if(actualCode == 27) {
            if($('cpmap_menu').style.display == 'none') {
            showMap();
            } else {
            hideMenu();
            }
            }
            if(actualCode == 116 && parent.main) {
            if(custombarcurrent) {
            parent.$('main_' + custombarcurrent).contentWindow.location.reload();
            } else {
            parent.main.location.reload();
            }
            if(document.all) {
            e.keyCode = 0;
            e.returnValue = false;
            } else {
            e.cancelBubble = true;
            e.preventDefault();
            }
            }
            }

            function mainFrame(id, src) {
            var setFrame = !id ? 'main' : 'main_' + id, obj = $('mainframes').getElementsByTagName('IFRAME'), exists = 0, src = !src ? '' : src;
            for(i = 0;i < obj.length;i++) {
            if(obj[i].name == setFrame) {
            exists = 1;
            }
            obj[i].style.display = 'none';
            }
            if(!exists) {
            if(BROWSER.ie) {
            frame = document.createElement('<iframe name="' + setFrame + '" id="' + setFrame + '"></iframe>');
            } else {
            frame = document.createElement('iframe');
            frame.name = setFrame;
            frame.id = setFrame;
            }
            frame.width = '100%';
            frame.height = '100%';
            frame.frameBorder = 0;
            frame.scrolling = 'yes';
            frame.style.overflow = 'visible';
            frame.style.display = 'none';
            if(src) {
            frame.src = src;
            }
            $('mainframes').appendChild(frame);
            }
            if(id) {
            custombar_set(id);
            }
            $(setFrame).style.display = '';
            if(!src && custombarcurrent) {
            $('custombar_' + custombarcurrent).className = '';
            custombarcurrent = 0;
            }
            }
            _attachEvent(document.documentElement, 'keydown', resetEscAndF5);
            if(BROWSER.ie){
            $('leftmenu').onmousewheel = function(e) { menuScroll(3, e) };
            $('custombarpanel').onmousewheel = function(e) { custombar_scroll(3, e) };
            } else {
            $('leftmenu').addEventListener("DOMMouseScroll", function(e) { menuScroll(3, e) }, false);
            $('custombarpanel').addEventListener("DOMMouseScroll", function(e) { custombar_scroll(3, e) }, false);
            }

        </script>
    </body>
</html>
