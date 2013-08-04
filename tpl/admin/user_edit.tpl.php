<?php include ROOTDIR."/tpl/admin/head.tpl.php";?>
<div class="container">
<h3 class="marginbot">编辑网站主信息<a href="" class="sgbtn">返回用户列表</a></h3>
	<div class="mainbox">
	 <?php if(isset($errormsg) && !empty($errormsg)) {?>		<div class="errormsg">
				<p><em><?php echo $errormsg;?></em></p>
			</div>
			<?php } ?>
		<div id="custom">
			<form action="<?php echo createUrl("admin", "user_edit_post");?>" method="post">
			<table class="opt">
				<tbody>
<!--
					                    <tr>
						<th colspan="2">邮箱:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="title" value=""></td>
						<td></td>
                    </tr>
-->
                    
                     <tr>
						<th colspan="2">密码:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="password" value="<?php echo $row['password'];?>"></td>
						<td></td>
                    </tr>
 <!--
                    <tr>
						<th colspan="2">注册时间:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="showtitle" value=""></td>
						<td></td>
                    </tr>
                   
                    <tr>
						<th colspan="2">注册ip:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="showtitle" value=""></td>
						<td></td>
                    </tr>
					<tr>
						<th colspan="2">登陆时间:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="showtitle" value=""></td>
						<td></td>
                    </tr>
                    <tr>
						<th colspan="2">登陆ip:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="showtitle" value=""></td>
						<td></td>
                    </tr>
                     <tr>
						<th colspan="2">登陆次数:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="showtitle" value=""></td>
						<td></td>
                    </tr>
                -->
                     <tr>
						<th colspan="2">状态:</th>
					</tr>
					<tr>
						<td>通过: <input <?php if($row['status'] == 0) echo "checked";?> type="radio" name="status" value="0"/>拒绝: <input  <?php if($row['status'] == 1) echo "checked";?> type="radio" name="status" value="1"/></td>
						<td></td>
                    </tr>
					 <tr>
						<th colspan="2">拒绝理由:</th>
					</tr>
					<tr>
						<td><textarea  class="txt" name="lockinfo" style="width:600px;height:150px;"><?php echo $row['lockinfo'];?></textarea></td>
						<td></td>
                    </tr>
					
									</tbody>
			</table>
			<div class="opt">
			<input type="hidden" name="id"	value="<?php echo $row['id'];?>">
			<input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3">
			</div>
			</form>
		</div>
	</div>
</div>
<?php include ROOTDIR."/tpl/admin/footer.tpl.php";?>