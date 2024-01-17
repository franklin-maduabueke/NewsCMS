<?php
	//visual elements for add user
?>
	<div class="formContentHolder" id="addNewCmsUser" style="text-align:left; height:auto">
		<?php
			if (isset($_GET['rmsg']) && !empty($_GET['rmsg']) && $current_task == ADMIN_TASK_ADD_USER)
			{
		?>
			<div class="noticeBoard" style="text-align:center; height:50px">
				<div><?php echo urldecode($_GET['rmsg']);?></div>
			</div>
		<?php
			}
		?>
		<form method="post" action="../processing/add_user.php">
			<div id="formElements" style="width:500px; margin-left:35px; margin-top:48px; height:auto; margin-bottom:20px">
				<div class="textElementHolder" style="text-align:left">
					<div class="entryLabel">
						Username
					</div>
					<input type="text" class="textbox" name="username" style="width:300px" value="<?php echo $_GET['un'];?>" maxlength="12" /> <label class="requiredField">*</label>
				</div>
				
				<div class="textElementHolder" style="text-align:left">
					<div class="entryLabel">
						Password
					</div>
					<input type="text" class="textbox" name="password" style="width:300px" maxlength="20" /> <label class="requiredField">*</label>
				</div>
				
				<div class="textElementHolder" style="text-align:left">
					<div class="entryLabel">
						Surname
					</div>
					<input type="text" class="textbox" name="surname" style="width:300px" value="<?php echo $_GET['sn'];?>" /> <label class="requiredField">*</label>
				</div>
				
				<div class="textElementHolder" style="text-align:left">
					<div class="entryLabel">
						Firstname
					</div>
					<input type="text" class="textbox" name="firstname" style="width:300px" value="<?php echo $_GET['fn'];?>" /> <label class="requiredField">*</label>
				</div>
				
				<div class="textElementHolder" style="text-align:left">
					<div class="entryLabel">
						Othername
					</div>
					<input type="text" class="textbox" name="othername" style="width:300px" value="<?php echo $_GET['on'];?>" />
				</div>
				<div id="userRolesHolder" style="font-size:12px; margin-top:10px;  margin-bottom:10px">
					<label>Admin</label><input type="radio" name="role" value="admin" />
					<label style="margin-left:50px">Editor</label><input type="radio" name="role" value="editor" checked="checked" />
				</div>
				<div class="buttonOutline" style="float:left">
					<input type="submit" class="clickButton clickable"  value="Add User" />
				</div>
			</div>
			</form>
			<div style="clear:both; height:40px"></div>
	</div>
<?php
?>