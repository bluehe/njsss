<?php include template("admin/header");?>

<div class="container" id="cpcontainer">
    <script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='首页&nbsp;&raquo;&nbsp;导航栏管理';</script>
    <div class="itemtitle">
        <h3>导航栏管理</h3>
        <ul class="tab1" id="submenu">
            <li id="nav_backsettings" onclick="showanchor(this);" <?php echo $f=='back'?'class="current"':''; ?>><a href="javascript:void(0);"><span>后台</span></a></li>
            <!--
            <li id="nav_frontsettings" onclick="showanchor(this);" <?php echo $f=='front'?'class="current"':''; ?>><a href="javascript:void(0);"><span>前台</span></a></li>
            -->
        </ul>
    </div>
    <script type="text/JavaScript">
        var rowtypedata = [
        [[1, '','td25'],
        [1,'<input type="text" class="txt" name="newcatorder[]" value="页面名称" />',],
        [1, '<input name="newcat[]" value="显示名称"  size="20" type="text" class="txt" />'],
        [2, '']],
        [[1,'', 'td25'],
        [1, '<div class="board"><input name="newforumorder[{1}][]" value="新分页名称" size="20" type="text" class="txt" /></div>'],
        [1, '<input name="newforum[{1}][]" value="新显示名称" size="20" type="text" class="txt" />'],
        [2, '']],
        [[1, '','td25'],
        [1,'<input type="text" class="txt" name="newmenuorder[{1}][]" value="页面名称" />',],
        [1, '<input name="newmenu[{1}][]" value="显示名称"  size="20" type="text" class="txt" />'],
        [2, '']],
        [[1,'', 'td25'],
        [1, '<div class="board"><input name="newmenusideorder{1}[]" value="新分页名称" size="20" type="text" class="txt" /></div>'],
        [1, '<input name="newmenuside{1}[]" value="新显示名称" size="20" type="text" class="txt" />'],
        [2, '']],
        ];
    </script>
    <form name="cpform" method="post" action="<?php echo WEB_ROOT; ?>/admin/current" id="cpform">
        <table class="tb tb2 "  id="backsettings" <?php echo $f=='back'?'':'style="display:none"'; ?>>
            <tr class="header">
                <th class="td25"></th>
                <th class="td22" style="width: 190px;">页面</th>
                <th class="td22" style="width: 130px;">显示名称</th>
                <th class="td22">默认页</th>
                <th></th>
            </tr>
            <?php if(is_array($as)){foreach($as AS $k=>$one) { ?>
            <tr class="hover">
                <td></td>
                <td><?php echo $k!='index'?'<input type="text" name="a['.$k.'][]" value="'.$k.'" class="txt" />':$k.'<input type="hidden" name="a['.$k.'][]" value="'.$k.'" />'; ?></td>
                <td><?php echo $k!='index'?'<input type="text" name="a['.$k.'][]" value="'.$one[1].'" class="txt" />':$one[1].'<input type="hidden" name="a['.$k.'][]" value="'.$one[1].'" />'; ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php if(is_array($bs)){foreach($bs AS $l=>$two) { ?>
            <?php if($k==$l){?>
            <?php if(is_array($two)){foreach($two AS $m=>$n) { ?>
            <tr class="hover">
                <td></td>
                <td><div class="board">
                        <?php echo $m!='home'&&$m!='current'?'<input type="text" name="b['.$k.']['.$m.'][]" value="'.$m.'" class="txt" />':$m.'<input type="hidden" name="b['.$k.']['.$m.'][]" value="'.$m.'" />'; ?>
                    </div></td>
                <td><?php echo $m!='home'&&$m!='current'?'<input type="text" name="b['.$k.']['.$m.'][]" value="'.$n.'" class="txt" />':$n.'<input type="hidden" name="b['.$k.']['.$m.'][]" value="'.$n.'" />'; ?></td>
                <td><?php if($k!='index'){?>
                    <input type="radio" class="radio" name="a[<?php echo $k; ?>][]" value="<?php echo $m; ?>" <?php echo $one[0]==$m?'checked':''; ?>>
                    <?php } else if($m=='home') { ?>
                    <input type="radio" class="radio" name="a[<?php echo $k; ?>][]" value="<?php echo $m; ?>" checked>
                    <?php }?></td>
                <td></td>
            </tr>
            <?php }}?>
            <?php }?>
            <?php }}?>
            <tr>
                <td></td>
                <td colspan="4"><div class="lastboard"><a href="javascript:void(0);" onclick="addrow(this, 1, '<?php echo $k; ?>');" class="addtr">添加新分页</a></div></td>
            </tr>
            <?php }}?>
            <tr>
                <td></td>
                <td colspan="4"><div><a href="javascript:void(0);" onclick="addrow(this, 0);" class="addtr">添加新标签</a></div></td>
            </tr>
            <tr>
                <td colspan="15"><div class="fixsel">
                        <input type="submit" class="btn" id="submit_editsubmit" name="editsubmit" title="按 Enter 键可随时提交您的修改" value="提交" />
                    </div></td>
            </tr>
            <tr>
                <td height="30px;"></td>
            </tr>
            <script type="text/JavaScript">_attachEvent(document.documentElement, 'keydown', function (e) { entersubmit(e, 'editsubmit'); });</script>
        </table>

        <table class="tb tb2 " id="frontsettings" <?php echo $f=='front'?'':'style="display:none"'; ?>>
            <tr class="header">
                <th class="td25">管理员</th>
                <th class="td22" style="width: 190px;">页面</th>
                <th class="td22" style="width: 130px;">显示名称</th>
                <th class="td22"></th>
                <th></th>
            </tr>
            <?php if(is_array($ms['member'])){foreach($ms['member'] AS $k=>$one) { ?>
            <tr class="hover">
                <td></td>
                <td><input type="text" name="m[member][<?php echo $k; ?>][]" value="<?php echo $k; ?>" class="txt" style="width:150px;" /></td>
                <td><input type="text" name="m[member][<?php echo $k; ?>][]" value="<?php echo $one[value]; ?>" class="txt" /></td>
                <td></td>
                <td></td>
            </tr>
            <?php if(is_array($one['side'])){foreach($one['side'] AS $index=>$value) { ?>


            <tr class="hover">
                <td></td>
                <td><div class="board"><input type="text" name="m[member][<?php echo $k; ?>][side][<?php echo $index; ?>][]" value="<?php echo $index; ?>" class="txt" style="width: 200px;" /></div></td>
                <td><input type="text" name="m[member][<?php echo $k; ?>][side][<?php echo $index; ?>][]" value="<?php echo $value; ?>" class="txt" /></td>
                <td></td>
                <td></td>
            </tr>

            <?php }}?>
            <tr>
                <td></td>
                <td colspan="4"><div class="lastboard"><a href="javascript:void(0);" onclick="addrow(this, 3, '[member][<?php echo $k; ?>]');" class="addtr">添加新分页</a></div></td>
            </tr>
            <?php }}?>
            <tr>
                <td></td>
                <td colspan="4"><div><a href="javascript:void(0);" onclick="addrow(this, 2, 'member');" class="addtr">添加新标签</a></div></td>
            </tr>
            <?php if(isset($INI['system']['register_teacher'])){?>
            <tr class="header">
                <th class="td25">采购商</th>
                <th class="td22" style="width: 190px;">页面</th>
                <th class="td22" style="width: 130px;">显示名称</th>
                <th class="td22"></th>
                <th></th>
            </tr>
            <?php if(is_array($ms['teacher'])){foreach($ms['teacher'] AS $k=>$one) { ?>
            <tr class="hover">
                <td></td>
                <td><input type="text" name="m[teacher][<?php echo $k; ?>][]" value="<?php echo $k; ?>" class="txt" style="width:150px;" /></td>
                <td><input type="text" name="m[teacher][<?php echo $k; ?>][]" value="<?php echo $one[value]; ?>" class="txt" /></td>
                <td></td>
                <td></td>
            </tr>
            <?php if(is_array($one['side'])){foreach($one['side'] AS $index=>$value) { ?>
            <tr class="hover">
                <td></td>
                <td><div class="board"><input type="text" name="m[teacher][<?php echo $k; ?>][side][<?php echo $index; ?>][]" value="<?php echo $index; ?>" class="txt" style="width: 200px;" /></div></td>
                <td><input type="text" name="m[teacher][<?php echo $k; ?>][side][<?php echo $index; ?>][]" value="<?php echo $value; ?>" class="txt" /></td>
                <td></td>
                <td></td>
            </tr>

            <?php }}?>
            <tr>
                <td></td>
                <td colspan="4"><div class="lastboard"><a href="javascript:void(0);" onclick="addrow(this, 3, '[teacher][<?php echo $k; ?>]');" class="addtr">添加新分页</a></div></td>
            </tr>
            <?php }}?>
            <tr>
                <td></td>
                <td colspan="4"><div><a href="javascript:void(0);" onclick="addrow(this, 2, 'teacher');" class="addtr">添加新标签</a></div></td>
            </tr>
            <?php }?>
            <?php if(isset($INI['system']['register_student'])){?>
            <tr class="header">
                <th class="td25">供应商</th>
                <th class="td22" style="width: 190px;">页面</th>
                <th class="td22" style="width: 130px;">显示名称</th>
                <th class="td22"></th>
                <th></th>
            </tr>
            <?php if(is_array($ms['student'])){foreach($ms['student'] AS $k=>$one) { ?>
            <tr class="hover">
                <td></td>
                <td><input type="text" name="m[student][<?php echo $k; ?>][]" value="<?php echo $k; ?>" class="txt" style="width:150px;" /></td>
                <td><input type="text" name="m[student][<?php echo $k; ?>][]" value="<?php echo $one[value]; ?>" class="txt" /></td>
                <td></td>
                <td></td>
            </tr>
            <?php if(is_array($one['side'])){foreach($one['side'] AS $index=>$value) { ?>
            <tr class="hover">
                <td></td>
                <td><div class="board"><input type="text" name="m[student][<?php echo $k; ?>][side][<?php echo $index; ?>][]" value="<?php echo $index; ?>" class="txt" style="width: 200px;" /></div></td>
                <td><input type="text" name="m[student][<?php echo $k; ?>][side][<?php echo $index; ?>][]" value="<?php echo $value; ?>" class="txt" /></td>
                <td></td>
                <td></td>
            </tr>

            <?php }}?>
            <tr>
                <td></td>
                <td colspan="4"><div class="lastboard"><a href="javascript:void(0);" onclick="addrow(this, 3, '[student][<?php echo $k; ?>]');" class="addtr">添加新分页</a></div></td>
            </tr>
            <?php }}?>
            <tr>
                <td></td>
                <td colspan="4"><div><a href="javascript:void(0);" onclick="addrow(this, 2, 'student');" class="addtr">添加新标签</a></div></td>
            </tr>
            <?php }?>
            <tr>
                <td colspan="15"><div class="fixsel">
                        <input type="submit" class="btn" id="submit_frontsubmit" name="editsubmit" title="按 Enter 键可随时提交您的修改" value="提交" onclick="this.form.action = '<?php echo WEB_ROOT; ?>/admin/current?f=front';" />
                    </div></td>
            </tr>
            <tr>
                <td height="30px;" colspan="15"></td>
            </tr>


        </table>
        <script type="text/JavaScript">_attachEvent(document.documentElement, 'keydown', function (e) { entersubmit(e, 'frontsubmit'); });</script>
    </form>
</div>
<?php include template("admin/footer");?>
