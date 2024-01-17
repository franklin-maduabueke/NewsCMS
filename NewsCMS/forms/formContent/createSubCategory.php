<?php
	//visual elements for add user
?>
	<div class="formContentHolder" id="createNewCategory">
		<form method="post" action="../processing/create_new_sub_category.php">
			<input type="hidden" value="<?php echo $selectedCategory;?>" name="categoryId" />
			<div id="formElements"  style="width:auto; background-color:#CCCCCC; margin-left:10px;">
				<div class="textElementHolder">
					<div class="entryLabel">
						Name
					</div>
					<input type="text" class="textbox" style="float:left; width:300px" maxlength="50" name="subcategoryName" id="subcategoryName" />
				</div>
				
				<div class="buttonOutline" style="clear:both; float:left">
					<input type="submit" class="clickButton clickable"  value="Create Subcategory" />
				</div>
			</div>
			</form>
	</div>
<?php
?>