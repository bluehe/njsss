<?php include template("admin/header");?>

<div class="container" id="cpcontainer">
    <h3>DMS 提示</h3>
    <div class="infobox">
        <h4 class="infotitle<?php echo $mtype; ?>"><?php echo $message; ?></h4>
        <?php echo $message_url; ?> </div>
</div>
<?php include template("admin/footer");?>
