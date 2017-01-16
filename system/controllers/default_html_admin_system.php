<?php include template("admin/header");?>

<div class="container" id="cpcontainer">
    <script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='全局&nbsp;&raquo;&nbsp;系统设置';</script>
    <div class="floattop">
        <div class="itemtitle">
            <h3>系统设置</h3>
        </div>
    </div>
    <div class="floattopempty"></div>
    <form name="cpform" method="post" action="<?php echo WEB_ROOT; ?>/admin/system" id="cpform" >
        <table class="tb tb2 nobdb" id="register">
            <tr>
                <td colspan="2" class="td27">系统状态:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><ul class="nofloat" onmouseover="altStyle(this);">
                        <li <?php echo $INI['system']['site_stat']=='1'?'class="checked"':''; ?>>
                            <input class="radio" type="radio" name="system[site_stat]" value="1" <?php echo $INI['system']['site_stat']==1?'checked="checked"':''; ?> onclick="$('showsitereason').style.display = 'none';">
                            &nbsp;开启</li>
                        <li <?php echo $INI['system']['site_stat']=='0'?'class="checked"':''; ?>>
                            <input class="radio" type="radio" name="system[site_stat]" value="0" <?php echo $INI['system']['site_stat']==0?'checked="checked"':''; ?> onclick="$('showsitereason').style.display = '';">
                            &nbsp;关闭</li>
                    </ul></td>
                <td class="vtop tips2">系统状态，如果关闭，只有超级管理员可以登录</td>
            </tr>
            <tbody class="sub" id="showsitereason" <?php echo $INI['system']['site_stat']==1?'style="display: none"':''; ?>>
                   <tr>
                    <td colspan="2" class="td27">系统关闭原因:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><textarea  rows="6" ondblclick="textareasize(this, 1)" name="system[site_reason]" id="site_reason" cols="50" class="tarea"><?php echo $INI['system']['site_reason']; ?></textarea></td>
                    <td class="vtop tips2">系统关闭原因<br />
                        双击输入框可扩大/缩小</td>
                </tr>
            </tbody>
            <tr>
                <td colspan="2" class="td27">允许新用户注册:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><ul onmouseover="altStyle(this);">
                        <li <?php echo $INI['system']['register']==0?'class="checked"':''; ?>>
                            <input class="radio" type="radio" name="system[register]" value="0" <?php echo $INI['system']['register']==0?'checked="checked"':''; ?> >
                            &nbsp;关闭注册</li>
                        <li <?php echo $INI['system']['register']==1?'class="checked"':''; ?>>
                            <input class="radio" type="radio" name="system[register]" value="1" <?php echo $INI['system']['register']==1?'checked="checked"':''; ?> >
                            &nbsp;开放注册</li>
                    </ul></td>
                <td class="vtop tips2">设置是否允许用户注册</td>
            </tr>
            <tr>
                <td colspan="2" class="td27">显示友情链接:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><ul onmouseover="altStyle(this);">
                        <li <?php echo $INI['system']['forum_linkstatus']=='1'?'class="checked"':''; ?>>
                            <input class="radio" type="radio" name="system[forum_linkstatus]" value="1" <?php echo $INI['system']['forum_linkstatus']==1?'checked="checked"':''; ?>>
                            &nbsp;是</li>
                        <li <?php echo $INI['system']['forum_linkstatus']=='0'?'class="checked"':''; ?>>
                            <input class="radio" type="radio" name="system[forum_linkstatus]" value="0" <?php echo $INI['system']['forum_linkstatus']==0?'checked="checked"':''; ?>>
                            &nbsp;否</li>
                    </ul></td>
                <td class="vtop tips2">首页是否显示友情链接</td>
            </tr>


            <tr>
                <td colspan="2" class="td27">无操作退出时长（分钟）:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><input name="system[logout_time]" value="<?php echo $INI['system']['logout_time']; ?>" type="text" class="txt"   /></td>
                <td class="vtop tips2">一段时间无操作，登录用户登出（留空为服务器默认）</td>
            </tr>

            <tr>
                <td colspan="2" class="td27">每页显示数目:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><input name="system[page_num]" value="<?php echo $INI['system']['page_num']; ?>" type="text" class="txt"   /></td>
                <td class="vtop tips2">分页功能中每一页显示的数据数目</td>
            </tr>

            <tr>
                <td colspan="2" class="td27">系统名称:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><input name="system[sitename]" value="<?php echo $INI['system']['sitename']; ?>" type="text" class="txt"   /></td>
                <td class="vtop tips2">本系统的全局名称</td>
            </tr>
            <tr>
                <td colspan="2" class="td27">系统标题:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><input name="system[sitetitle]" value="<?php echo $INI['system']['sitetitle']; ?>" type="text" class="txt"   /></td>
                <td class="vtop tips2">显示在网页标签页的内容</td>
            </tr>
            <tr>
                <td colspan="2" class="td27">系统关键词:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><input name="system[sitekeywords]" value="<?php echo $INI['system']['sitekeywords']; ?>" type="text" class="txt"   /></td>
                <td class="vtop tips2">系统head的关键词</td>
            </tr>
            <tr>
                <td colspan="2" class="td27">系统描述:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><textarea rows="6" ondblclick="textareasize(this, 1)" name="system[sitedescription]" id="description" cols="50" class="tarea"><?php echo $INI['system']['sitedescription']; ?></textarea></td>
                <td class="vtop tips2">系统head的描述<br />
                    双击输入框可扩大/缩小</td>
            </tr>

            <tr>
                <td colspan="2" class="td27">网站备案信息代码:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><input name="system[forum_icp]" value="<?php echo $INI['system']['forum_icp']; ?>" type="text" class="txt"   /></td>
                <td class="vtop tips2">页面底部可以显示 ICP 备案信息，如果网站已备案，在此输入您的授权码，它将显示在页面底部，如果没有请留空</td>
            </tr>
            <tr>
                <td colspan="2" class="td27">网站第三方统计代码:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform"><textarea rows="6" ondblclick="textareasize(this, 1)" name="system[forum_statcode]" id="forum_statcode" cols="50" class="tarea"><?php echo $INI['system']['forum_statcode']; ?></textarea></td>
                <td class="vtop tips2">页面底部可以显示第三方统计<br />
                    双击输入框可扩大/缩小</td>
            </tr>
        </table>
        <table class="tb tb2 nobdt">
            <tr>
                <td colspan="15"><div class="fixsel">
                        <input type="submit" class="btn" id="submit_systemsubmit" name="systemsubmit" title="按 Enter 键可随时提交您的修改" value="提交" />
                    </div></td>
            </tr>
            <tr>
                <td height="30px;"></td>
            </tr>

        </table>
        <script type="text/JavaScript">_attachEvent(document.documentElement, 'keydown', function (e) { entersubmit(e, 'systemsubmit'); });</script>
    </form>
</div>
<?php include template("admin/footer");?>
