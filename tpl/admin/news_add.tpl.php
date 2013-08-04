<?php include ROOTDIR . "/tpl/admin/head.tpl.php"; ?>
<script type="text/javascript" src="/styles/ueditor/ueditor.config.js"></script>
<script type='text/javascript' src='/styles/ueditor/ueditor.all.js'></script>
<div class="container">
    <div class="mainbox">
        <form action='/admin/news?op=add_post' method='post' onsubmit='return submit_check();'>
            分组：
            <select name='type'>
                <?php foreach ($type_arr as $key => $value) { ?>
                    <option value='<?php echo $key; ?>'><?php echo $value;?></option>
                <?php } ?>
            </select>
            <br>
            标题：
            <input type='text' id='title' name='title' style='width:700px;'>
            
            <textarea name='content' id='richtxtedt' style='width:800px;'>
            </textarea>
            <input type='submit' value='提交' class="btn">
        </form>
    </div>
</div>
<script type='text/javascript'>
    var editor = new UE.ui.Editor();
    editor.render('richtxtedt');
    editor.ready(function(){
        //需要ready后执行，否则可能报错
        editor.setHeight(400);
    })
    function submit_check(){
        var title = $('#title').val();
        if(title.length == 0){
            alert('标题不能为空');
            return false;
        }
    }
</script>

<?php include ROOTDIR . "/tpl/admin/footer.tpl.php"; ?>