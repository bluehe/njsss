<?php include template("admin/header");?>

<div class="container" id="cpcontainer">
    <script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='楼苑&nbsp;&raquo;&nbsp;楼苑管理';</script>
    <div class="itemtitle">
        <h3>楼苑管理</h3>
    </div>
    <table class="tb tb2 " id="tips">
        <tr>
            <th  class="partition">技巧提示</th>
        </tr>
        <tr>
            <td class="tipsblock"><ul id="tipslis">
                    <li>管理员用户名为粗体，则表示该管理员权限可继承到下级楼苑</li>

                </ul></td>
        </tr>
    </table>
    <script type="text/JavaScript">
        var rowtypedata = [
        [[1,'<input type="text" class="txt" name="newcatorder[]" value="0" />', 'td25'],
        [1, '<input name="newcat[]" value="新苑名称" size="20" type="text" class="txt" />'],
        [7, ''],
        ],

        [[1,'<input type="text" class="txt" name="neworder[{1}][]" value="0" />', 'td25'],
        [1, '<div class="board"><input name="newforum[{1}][]" value="新楼名称" size="20" type="text" class="txt" /></div>'],
        [1,'<select name="newfloor[{1}][]" > <?php if(is_array($floornames)){foreach($floornames AS $floorname) { ?> <option value="<?php echo $floorname[k]; ?>" ><?php echo $floorname[v]; ?></option><?php }}?></select>'],
        [1,'<select name="newmold[{1}][]" ><option value="sig" >单间</option><option value="mul">套间</option></select>'],
        [1,'<select name="newroomtype[{1}][]" ><option value="common" >常住宿舍</option><option value="overtime">加班宿舍</option><option value="train">培训宿舍</option></select>'],
        [1,'<input type="text" class="txt" name="newcostroom[{1}][]" value="" />'],
        [1,'<input type="text" class="txt" name="newcostwater[{1}][]" value="" />'],
        [1,'<input type="text" class="txt" name="newcostelectric[{1}][]" value="" />'],
        [1, '']],
        ];
    </script>
    <form name="cpform" method="post" action="/admin/forum" >
        <table class="tb tb2 ">
            <tr class="header">
                <th class="td22">显示顺序</th>
                <th>楼苑名称</th>
                <th>楼层</th>
                <th>房型</th>
                <th>类型</th>
                <th>房费(元/天)</th>
                <th>水费(元/吨)</th>
                <th>电费(元/度)</th>

                <th></th>
            </tr>
            <?php if(is_array($courts)){foreach($courts AS $court) { ?>
            <tr class="hover">
                <td class="td25"><input type="text" class="txt" name="court[<?php echo $court['id']; ?>][displayorder]" value="<?php echo $court['displayorder']; ?>" /></td>
                <td><div class="parentboard">
                        <input type="text" name="court[<?php echo $court['id']; ?>][name]" value="<?php echo $court['name']; ?>" class="txt" />
                    </div></td>
                <td colspan="6"></td>

                <td><a href="/admin/forumdelete?fid=<?php echo $court['id']; ?>" title="删除本楼苑" class="act">删除</a> <?php echo $court['stat']==1?'<a href="/admin/forum?stat=0&fid='.$court['id'].'" title="关闭本楼苑" class="act">关闭</a>':'<a href="/admin/forum?stat=1&fid='.$court['id'].'" title="开放本楼苑" class="act">开放</a>'; ?></td>
            </tr>
            <?php if(is_array($builds)){foreach($builds AS $build) { ?>
            <?php if($build['fup']==$court['id']){?>
            <tr class="hover">
                <td class="td25"><input type="text" class="txt" name="build[<?php echo $build['id']; ?>][displayorder]" value="<?php echo $build['displayorder']; ?>" /></td>
                <td><div class="board">
                        <input type="text" name="build[<?php echo $build['id']; ?>][name]" value="<?php echo $build['name']; ?>" class="txt" />
                    </div></td>
                <td><select name="build[<?php echo $build['id']; ?>][floor]"  disabled>
                        <?php if(is_array($floornames)){foreach($floornames AS $floorname) { ?>
                        <option value="<?php echo $floorname['k']; ?>"  <?php echo $build['floor']==$floorname['k']?'selected="selected"':''; ?> ><?php echo $floorname['v']; ?></option>
                        <?php }}?>
                    </select></td>
                <td><select name="build[<?php echo $build['id']; ?>][mold]" disabled >
                        <option value="sig" <?php echo $build['mold']=='sig'?'selected="selected"':''; ?>>单间</option>
                        <option value="mul" <?php echo $build['mold']=='mul'?'selected="selected"':''; ?>>套间</option>
                    </select></td>
                <td><select name="build[<?php echo $build['id']; ?>][roomtype]" >
                        <option value="common" <?php echo $build['roomtype']=='common'?'selected="selected"':''; ?>>常住宿舍</option>
                        <option value="overtime" <?php echo $build['roomtype']=='overtime'?'selected="selected"':''; ?>>加班宿舍</option>
                        <option value="train" <?php echo $build['roomtype']=='train'?'selected="selected"':''; ?>>培训宿舍</option>
                    </select></td>
                <td><div>
                        <input type="text" name="build[<?php echo $build['id']; ?>][cost_room]" value="<?php echo $build['cost_room']; ?>" class="txt" />
                    </div></td>
                <td><div>
                        <input type="text" name="build[<?php echo $build['id']; ?>][cost_water]" value="<?php echo $build['cost_water']; ?>" class="txt" />
                    </div></td>
                <td><div>
                        <input type="text" name="build[<?php echo $build['id']; ?>][cost_electric]" value="<?php echo $build['cost_electric']; ?>" class="txt" />
                    </div></td>
                <td><a href="/admin/forumdelete?fid=<?php echo $build['id']; ?>" title="删除本楼苑" class="act">删除</a> <?php echo $build['stat']==1?'<a href="/admin/forum?stat=0&fid='.$build['id'].'" title="关闭本楼苑" class="act">关闭</a>':'<a href="/admin/forum?stat=1&fid='.$build['id'].'" title="开放本楼苑" class="act">开放</a>'; ?> <a href="/admin/forumsetting?fid=<?php echo $build[id]; ?>" title="编辑本楼苑设置" class="act">编辑</a></td>
            </tr>
            <?php }?>
            <?php }}?>
            <tr>
                <td></td>
                <td colspan="3"><div class="lastboard"><a href="###" onclick="addrow(this, 1, '<?php echo $court[id]; ?>')" class="addtr">添加新楼</a></div></td>
            </tr>
            <?php }}?>
            <tr>
                <td></td>
                <td colspan="3"><div><a href="###" onclick="addrow(this, 0)" class="addtr">添加新苑</a></div></td>
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
    </form>
</div>
<?php include template("admin/footer");?>
