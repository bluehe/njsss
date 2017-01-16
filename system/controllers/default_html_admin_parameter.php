<?php include template("admin/header");?>

<div class="container" id="cpcontainer">
    <script type="text/JavaScript">
        var rowtypedata = [
        [
        [1,'', 'td25'],
        [1,'<input type="text" class="txt" name="newcode[]" value="" />', 'td24'],
        [1,'<input type="text" class="txt" name="newname[]" value="" />', 'td29']
        ],
        ];
    </script>
    <script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='参数&nbsp;&raquo;&nbsp;设置';</script>
    <div class="floattop">
        <div class="itemtitle">
            <h3>参数设置</h3>
        </div>
    </div>
    <div class="floattopempty"></div>
    <table class="tb tb2 " id="tips">
        <tr>
            <th  class="partition">技巧提示</th>
        </tr>
        <tr>
            <td class="tipsblock"><ul id="tipslis">
                    <li>进行类型名称修改，暂时不开放对编号的修改</li>
                    <li>请保证编号的唯一性</li>

                </ul></td>
        </tr>
    </table>
    <form name="cpform" method="post" action="<?php echo WEB_ROOT; ?>/admin/parameter?type=<?php echo $type; ?>" id="cpform" >
        <table class="tb tb2 noborder fixpadding">
            <tr>
                <th colspan="3" class="partition">参数设置</th>
            </tr>
            <tr class="header">
                <th class="td25"></th>
                <th class="td24">编号</th>
                <th class="td29">参数</th>
            </tr>
            <?php if(is_array($parameters)){foreach($parameters AS $parameter) { ?>
            <tr class="hover">
                <td class="td25"><input type="checkbox" name="delete[]" value="<?php echo $parameter['id']; ?>" class="checkbox"></td>
                <td class="td25"><input type="hidden" name="parameter[<?php echo $parameter['id']; ?>][k]" value="<?php echo $parameter['k']; ?>" /><?php echo $parameter['k']; ?></td>
                <td class="td29"><input type="text" class="txt"  name="parameter[<?php echo $parameter['id']; ?>][v]"  value="<?php echo $parameter['v']; ?>" /></td>
            </tr>
            <?php }}?>

            <tr>
                <td></td>
                <td colspan="3"><div><a href="javascript:void(0)" onclick="addrow(this, 0)" class="addtr">&nbsp;添加新参数</a></div></td>
            </tr>
        </table>
        <table class="tb tb2 nobdt">
            <tr>
                <td colspan="3"><div class="fixsel">
                        <input type="checkbox" name="chkall" onclick="checkAll('prefix', this.form, 'delete')" class="checkbox">
                        删? &nbsp;
                        <input type="submit" class="btn" id="submit_detailsubmit" name="detailsubmit" title="按 Enter 键可随时提交您的修改" value="提交" />
                    </div></td>
            </tr>
            <tr>
                <td style="height:30px;"></td>
            </tr>

        </table>
        <script type="text/JavaScript">_attachEvent(document.documentElement, 'keydown', function (e) { entersubmit(e, 'detailsubmit'); });</script>
    </form>
</div>
<?php include template("admin/footer");?>
