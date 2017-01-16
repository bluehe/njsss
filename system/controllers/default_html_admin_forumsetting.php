<?php include template("admin/header");?>

<div class="container" id="cpcontainer">
    <script type="text/JavaScript">
        var rowtypedata = [
        [
        [1,''],
        [1,'<select name="newfloor[]" > <?php if(is_array($floornames)){foreach($floornames AS $key=>$floorname) { ?> <option value="<?php echo $key; ?>" ><?php echo $floorname; ?></option><?php }}?></select>'],
        [1,'<input type="text" class="txt" name="newbroom[]">'],
        <?php if($forum['mold']=='mul'){?>
        [1,'<input type="text" class="txt" name="newsroom[]">'],
        <?php }?>
        [1,'<input type="text" class="txt" name="newbed[]">'],
        [1,'<select name="newstat[]" ><?php if(is_array($bed_stats)){foreach($bed_stats AS $bed_stat) { ?><option value="<?php echo $bed_stat[k]; ?>" ><?php echo $bed_stat[v]; ?></option><?php }}?></select>'],
        [1,'<input type="text" class="txt" name="newnote[]">'],
        ],
        ];
    </script>
    <script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='楼苑&nbsp;&raquo;&nbsp;编辑楼苑';</script>
    <div class="floattop">
        <div class="itemtitle"><span id="fselect" class="right popupmenu_dropmenu" onmouseover="showMenu({'ctrlid': this.id, 'pos': '34'});
                $('fselect_menu').style.top = (parseInt($('fselect_menu').style.top) - document.documentElement.scrollTop) + 'px'">切换楼苑<em>&nbsp;&nbsp;</em></span>
            <div id="fselect_menu" class="popupmenu_popup" style="width:200px;display:none">
                <?php if(is_array($courts)){foreach($courts AS $court) { ?>
                <em class="hover" ><?php echo $court['name']; ?></em>
                <?php if(is_array($builds)){foreach($builds AS $build) { ?>
                <?php if($build['fup'] == $court['id']){?>
                <a class="f <?php echo $build[id]==$fid?'current':''; ?>" href="###" onclick="location.href = '/admin/forumsetting?fid=<?php echo $build[id]; ?>'"><?php echo $build['name']; ?></a>
                <?php }?>
                <?php }}?>
                <?php }}?>
            </div>
            <h3>楼苑设置 - <?php echo $forum['name']; ?></h3>
            <ul class="tab1" id="submenu">
                <li id="nav_basic" onclick="showanchor(this)"><a href="#"><span>基本设置</span></a></li>
                <li id="nav_bed" onclick="showanchor(this)" class="current"><a href="#"><span>床位设置</span></a></li>

            </ul>
        </div>
    </div>
    <div class="floattopempty"></div>
    <form name="cpform" method="post" action="/admin/forumsetting?fid=<?php echo $fid; ?>" id="cpform" >
        <div id="basic"  style="display: none">
            <table class="tb tb2 nobdb">
                <tr>
                    <th colspan="15" class="partition">基本设置</th>
                </tr>
                <tr>
                    <td colspan="2" class="td27">楼苑名称:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><input name="namenew" value="<?php echo $forum['name']; ?>" type="text" class="txt"   /></td>
                    <td class="vtop tips2"></td>
                </tr>
                <tr>
                    <td colspan="2" class="td27">楼苑状态:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><ul onmouseover="altStyle(this);">
                            <?php if($forum['stat']==1){?>
                            <li class="checked">
                                <input class="radio" type="radio" name="statnew" value="1" checked="checked" />
                                &nbsp;开放</li>
                            <li>
                                <input class="radio" type="radio" name="statnew" value="0" />
                                &nbsp;关闭</li>
                            <?php } else { ?>
                            <li>
                                <input class="radio" type="radio" name="statnew" value="1" />
                                &nbsp;开放</li>
                            <li class="checked">
                                <input class="radio" type="radio" name="statnew" value="0"  checked="checked" />
                                &nbsp;关闭</li>
                            <?php }?>
                        </ul>
                    <td class="vtop tips2"></td>
                </tr>
                <tr>
                    <td colspan="2" class="td27">上级楼苑:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><select name="fupnew">
                            <?php if(is_array($courts)){foreach($courts AS $court) { ?>
                            <option value="<?php echo $court['id']; ?>" <?php echo $court['id']==$forum['fup']?'selected="selected"':''; ?>><?php echo $court['name']; ?></option>
                            <?php }}?>
                        </select></td>
                    <td class="vtop tips2">本楼苑的上级楼苑或分类</td>
                </tr>
                <tr>
                    <td colspan="2" class="td27">楼层:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><select name="floornew" disabled >
                            <?php if(is_array($floornames)){foreach($floornames AS $key=>$floorname) { ?>
                            <option value="<?php echo $key; ?>"  <?php echo $forum['floor']==$key?'selected="selected"':''; ?> ><?php echo $floorname; ?></option>
                            <?php }}?>
                        </select></td>
                    <td class="vtop tips2">本楼苑共有多少楼层（暂时设置为不可更改）</td>
                </tr>
                <tr>
                    <td colspan="2" class="td27">房型:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><select name="moldnew" disabled >
                            <option value="sig" <?php echo $forum['mold']=='sig'?'selected="selected"':''; ?>>单间</option>
                            <option value="mul" <?php echo $forum['mold']=='mul'?'selected="selected"':''; ?>>套间</option>
                        </select></td>
                    <td class="vtop tips2">本楼苑的房间户型（暂时设置为不可更改）</td>
                </tr>
                <tr>
                    <td colspan="2" class="td27">类型:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><select name="roomtypenew">
                            <option value="common" <?php echo $forum['roomtype']=='common'?'selected="selected"':''; ?>>常住宿舍</option>
                            <option value="overtime" <?php echo $forum['roomtype']=='overtime'?'selected="selected"':''; ?>>加班宿舍</option>
                            <option value="train" <?php echo $forum['roomtype']=='train'?'selected="selected"':''; ?>>培训宿舍</option>
                        </select></td>
                    <td class="vtop tips2"></td>
                </tr>
                <tr>
                    <td colspan="2" class="td27">房费（元/天）:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><input name="costroomnew" value="<?php echo $forum['cost_room']; ?>" type="text" class="txt"   /></td>
                    <td class="vtop tips2"></td>
                </tr>
                <tr>
                    <td colspan="2" class="td27">水费（元/吨）:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><input name="costwaternew" value="<?php echo $forum['cost_water']; ?>" type="text" class="txt"   /></td>
                    <td class="vtop tips2"></td>
                </tr>
                <tr>
                    <td colspan="2" class="td27">电费（元/度）:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><input name="costelectricnew" value="<?php echo $forum['cost_electric']; ?>" type="text" class="txt"   /></td>
                    <td class="vtop tips2"></td>
                </tr>

                <!--                <tr>
                                    <td colspan="2" class="td27">平面图:</td>
                                </tr>
                                <tr class="noborder">
                                    <td class="vtop rowform"><input name="imgnew" value="<?php echo $forum['img']; ?>" type="text" class="txt"   /></td>
                                    <td class="vtop tips2"><?php echo $forum['img']!=''?'<img src="/data/'.$pre.'/ad/'.$forum['img'].'" style="max-width:800px; max-height:800px;" />':''; ?></td>
                                </tr>-->
                <tr>
                    <td colspan="2" class="td27">楼苑备注:</td>
                </tr>
                <tr class="noborder">
                    <td class="vtop rowform"><textarea  rows="6" ondblclick="textareasize(this, 1)" name="descriptionnew" id="descriptionnew" cols="50" class="tarea"><?php echo $forum['description']; ?></textarea></td>
                    <td class="vtop tips2">将显示于楼苑名称的下面，提供对本楼苑的备注<br />
                        双击输入框可扩大/缩小</td>
                </tr>
            </table>
        </div>
        <div id="bed">
            <table class="tb tb2 " id="tips">
                <tr>
                    <th  class="partition">技巧提示</th>
                </tr>
                <tr>
                    <td class="tipsblock"><ul id="tipslis">
                            <li>输入框中用英文半角“,”隔开，可以批量添加</li>
                        </ul></td>
                </tr>
            </table>
            <table class="tb tb2 noborder fixpadding">
                <tr>
                    <th  colspan="15" class="partition">共有<strong><?php echo $nums; ?></strong>个床位<?php echo $pagestring; ?></th>
                </tr>
                <tr class="header">
                    <th style="width:60px;"><input type="checkbox" name="chkall" onclick="checkAll('prefix', this.form, 'bidarray')" class="checkbox">
                        删？</th>
                    <th>楼层</th>
                    <th>大室</th>
                    <?php if($forum['mold']=='mul'){?>
                    <th>小室</th>
                    <?php }?>
                    <th>床位</th>
                    <th>状态</th>
                    <th>备注</th>
                </tr>
                <?php if(is_array($beds)){foreach($beds AS $bed) { ?>
                <tr class="hover">
                    <td class="td25"><input type="checkbox" name="bidarray[]" value="<?php echo $bed['id']; ?>" class="checkbox"></td>
                    <td><?php echo $floornames[$bed['floor']]; ?></td>
                    <td><?php echo $bed['broom']; ?></td>
                    <?php if($forum['mold']=='mul'){?>
                    <td><?php echo $bed['sroom']; ?></td>
                    <?php }?>
                    <td><?php echo $bed['bed']; ?></td>
                    <td><?php echo $stat[$bed['stat']]; ?></td>
                    <td><?php echo $bed['note']; ?></td>
                </tr>
                <?php }}?>
                <tr>
                    <td colspan="6"><div><a href="###" onclick="addrow(this, 0)" class="addtr">&nbsp;添加新床位</a></div></td>
                </tr>
            </table>
        </div>

        <table class="tb tb2 nobdt">
            <tr>
                <td colspan="15"><div class="fixsel">
                        <input type="submit" class="btn" id="submit_detailsubmit" name="detailsubmit" title="按 Enter 键可随时提交您的修改" value="提交" />
                    </div></td>
            </tr>
            <tr>
                <td height="30px;"></td>
            </tr>
            <script type="text/JavaScript">_attachEvent(document.documentElement, 'keydown', function (e) { entersubmit(e, 'detailsubmit'); });</script>
        </table>
    </form>
</div>
<?php include template("admin/footer");?>
