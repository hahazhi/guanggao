<?php include ROOTDIR . "/tpl/admin/head.tpl.php"; ?>
<div class="container">
    <h3 class="marginbot"><a href="/admin/news?op=add" class="sgbtn">增加新闻</a></h3>
    <div class="mainbox">
        <?php if (isset($msg) && !empty($msg)) { ?>		
            <div class="errormsg">
                <p><em><?php echo $msg; ?></em></p>
            </div>
        <?php } ?>		
        <form action="/admin/news?op=del" method="post">
            <table class="datalist"  onmouseover="addMouseEvent(this);">
                <tr>
                    <th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('id[]')" class="checkbox">
                        <label for="chkall">删除</label>
                    </th>
                    <th>分组</th>
                    <th>标题</th>
                    <th>创建时间</th>
                    <td>修改</td>
                </tr>
                <?php
                if (!empty($models))
                    foreach ($models as $k => $model) {
                        ?>					
                        <tr>
                            <td><input type="checkbox" name="id[]" value="<?php echo $model['id']; ?>" class="checkbox"></td>
                            <td><?php echo $model['type']; ?></td>
                            <td><?php echo $model['title']; ?></td>
                            <td><?php echo $model['createtime']; ?></td>
                            <td><a href="/admin/news?op=edit&id=<?php echo $model['id']; ?>">编辑</a></td>
                        </tr>
                <?php } ?>					
                <tr class="nobg">
                    <td><input type="submit" value="删 除" class="btn"></td>
                    <td class="tdpage" colspan='4'><?php echo paginate($page, $pagecount, '/admin/news?op=lists'); ?></td>
                </tr>		
            </table>
            <p class="page_list"></p>
        </form>
    </div>
</div>


<?php include ROOTDIR . "/tpl/admin/footer.tpl.php"; ?>