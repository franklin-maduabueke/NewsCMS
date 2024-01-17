<?php
	//visual elements for register adds client
?>
	<div class="formContentHolder" id="createNewGroup" style="height:auto; text-align:left">
		<div class="noticeBoard" style="height:25px; text-align:center"><?php if (isset($_GET['pin'])) echo $_GET['msg'] . ". Please copy the clients PIN: " . $_GET['pin'];?></div>
		<form method="post" action="../processing/registerAdsClient.php">
			<div id="formElements"  style="width:500px; margin-left:10px;">
				<div class="textElementHolder">
					<div class="entryLabel">
						Company
					</div>
					<input type="text" class="textbox" style="float:left; width:300px" maxlength="50" name="companyName" id="companyName" /> <label class="requiredField" style="margin-left:10px;">*</label>
				</div> 
				
				<div class="textElementHolder">
					<div class="entryLabel">
						Website
					</div>
					<input type="text" class="textbox" style="float:left; width:300px" maxlength="50" name="website" id="website" />
				</div>
				
				<div class="textElementHolder">
					<div class="entryLabel">
						Email
					</div>
					<input type="text" class="textbox" style="float:left; width:300px" maxlength="50" name="email" id="email" /> <label class="requiredField" style="margin-left:10px;">*</label>
				</div>
				
				<div class="textElementHolder">
					<div class="entryLabel">
						Surname
					</div>
					<input type="text" class="textbox" style="float:left; width:300px" maxlength="20" name="surname" id="surname" />
				</div>
				
				<div class="textElementHolder">
					<div class="entryLabel">
						Firstname
					</div>
					<input type="text" class="textbox" style="float:left; width:300px" maxlength="20" name="firstname" id="firstname" />
				</div>
				
				<div class="textElementHolder">
					<div class="entryLabel">
						Othername
					</div>
					<input type="text" class="textbox" style="float:left; width:300px" maxlength="20" name="othername" id="othername" />
				</div>
				
				<div class="buttonOutline" style="clear:both; float:left">
					<input type="submit" class="clickButton clickable"  value="Register Client" />
				</div>
			</div>
			</form>
	</div>
<?php
?>