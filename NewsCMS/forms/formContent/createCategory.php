<?php
	//visual elements for add user
?>
	<div class="formContentHolder" id="createNewSubCategory">
		<form method="post" action="../processing/create_new_category.php">
			<div id="formElements"  style="width:auto; background-color:#CCCCCC; margin-left:10px;">
				<div class="textElementHolder">
					<div class="entryLabel">
						Category Name
					</div>
					<input type="text" class="textbox" style="float:left; width:300px" maxlength="50" name="categoryName" id="categoryName" />
				</div>
				
				<div class="buttonOutline" style="clear:both; float:left">
					<input type="submit" class="clickButton clickable"  value="Create New Category" />
				</div>
			</div>
			</form>
	</div>
<?php
?>