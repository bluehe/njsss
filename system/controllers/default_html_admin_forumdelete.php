<?php include template("admin/header");?>
<div class="container" id="cpcontainer">
    <h3>DMS 提示</h3>
    <div class="infobox">
        <form method="post" action="/admin/forumdelete?fid=<?php echo $fid; ?>">
            <br />
            <h4 class="marginbot normal">本操作不可恢复，您确定要删除该楼苑，<strong style="color:#F00">清除其中所有床位资料吗？</strong><br />
            </h4>
            <br />
            <p class="margintop">
                <input type="submit" class="btn" name="confirmed" value="确定">
                &nbsp;
                <input type="button" class="btn" value="取消" onclick="history.go(-1);">
            </p>
        </form>
        <br />
    </div>
</div>
<?php include template("admin/footer");?>