<?php
	//function used to get the categorys in the news site and render them as tabs
	function getCategory($dbConn)
	{
		$sql = "SELECT CategoryName, CatGenID FROM category ORDER BY WebCatTabIndex ASC, CategoryName ASC";
		$categoryResult = $dbConn->query($sql);
		
		if ($categoryResult && $categoryResult->num_rows > 0)
		{
			for (; ($row = $categoryResult->fetch_array()) != NULL; )
			{
			?>
				<div class="categoryTab">
					<div class="catTabLConer">
					</div>
					<div class="catNameHolder">
						<a href="room.php?sel=<?php echo $row['CatGenID']; ?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&t=active_category&tabName=<?php echo urlencode($row['CategoryName']);?>&tsk=<?php echo GENERAL_TASK_SUB_CATEGORY_LISTING;?>" style="text-shadow:#333333 1px 1px 1px">
							<?php
								echo $row['CategoryName'];
							?>
						</a>
					</div>
					<div class="catTabRConer">
					</div>
				</div>
			<?php
			}
		}
		//create tab for creating new category
		?>
			<div class="categoryTab" style="opacity:0.85;">
					<div class="catTabLConer">
					</div>
					<div class="catNameHolder">
						<a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&t=active_category&tabName=<?php echo urlencode("Create New Category");?>&tsk=<?php echo GENERAL_TASK_CREATE_CATEGORY;?>">
							Create New Ctgry
						</a>
					</div>
					<div class="catTabRConer">
					</div>
			</div>
			
			<div class="categoryTab" style="opacity:0.85;">
					<div class="catTabLConer">
					</div>
					<div class="catNameHolder">
						<a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&t=active_category&tabName=<?php echo urlencode("Index Template Setup");?>&tsk=<?php echo ADMIN_TASK_SECTION_INDEX_TEMPLATE;?>">
							Index Setup
						</a>
					</div>
					<div class="catTabRConer">
					</div>
			</div>
		<?php
		return $categoryResult->num_rows;
	}
?>