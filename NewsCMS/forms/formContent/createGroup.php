<?php
	//visual elements for add user
?>
	<div class="formContentHolder" id="createNewGroup">
		<form method="post" action="../processing/create_new_grouping.php">
			<input type="hidden" value="<?php echo $scGenID;?>" name="subcategoryId" />
			<input type="hidden" value="<?php echo $selectedCategory;?>" name="categoryId" />
			<input type="hidden" value="<?php echo $tabName;?>" name="tabName" />
			<div id="formElements"  style="width:auto; background-color:#CCCCCC; margin-left:10px;">
				<div class="textElementHolder">
					<div class="entryLabel">
						Name
					</div>
					<input type="text" class="textbox" style="float:left; width:300px" maxlength="50" name="subcategoryGroupName" id="subcategoryGroupName" />
				</div>
				
				<div class="buttonOutline" style="clear:both; float:left">
					<input type="submit" class="clickButton clickable"  value="Create Subcategory Group" />
				</div>
			</div>
			</form>
	</div>
<?php
?>