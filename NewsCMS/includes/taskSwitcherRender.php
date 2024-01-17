<div id="breadCrumbHolder">
<?php
	switch ($current_task)
	{
	case GENERAL_TASK_SELECT_CATEGORY:
	?>
	<div>Select Category</div>
<?php
	break;
	case GENERAL_TASK_CREATE_CATEGORY:
	?>
	<div>Create Category</div>
<?php
	break;
	case GENERAL_TASK_SUB_CATEGORY_LISTING:
?>
	<div><?php echo $categoryName;?> &gt; Subcategory Selection</div>
<?php
	break;
	case GENERAL_TASK_CREATE_SUB_CATEGORY:
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $categoryName;?></a> &gt; Create Subcategory</div>
	<?php
	break;
	case GENERAL_TASK_SEARCH:
	?>
	<div>Search Results for: <?php echo $_GET['q'];?></div>
	<?php
	break;
	case GENERAL_TASK_PUBLISHED_ARTICLES:
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; Published Articles</div>
	<?php
	break;
	case GENERAL_TASK_WRITE_ARTICLE:
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; Write Article</div>
	<?php
	break;
	case GENERAL_TASK_UNPUBLISHED_ARTICLES:
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; Unpublished Articles</div>
	<?php
	break;
	case GENERAL_TASK_VIEW_SUBCATEGORY_GROUP:
		if (isset($sub_task) && $sub_task == GENERAL_TASK_VIEW_SUBCATEGORY_GROUP_ARTICLES)
		{
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; View <?php echo $groupName;?> Group Articles</div>
	<?php
		}
		else
		{
		?>
		<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; View Groups</div>
	<?php
		}
	break;
	case GENERAL_TASK_EDIT_ARTICLE:
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; Edit Article</div>
	<?php
	break;
	case GENERAL_TASK_RELATED_LINKS:
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; Related Article 
		<span style="font-size:10px; color:#81AA2B">(
		<?php
			//get the article heading.
			$sql = "SELECT Heading FROM articles WHERE ArticleGenID='".$_GET['article']."'";
			$articleHeadingResult = $dbConn->query($sql);
			if ($articleHeadingResult && $articleHeadingResult->num_rows > 0)
			{
				$headingRow = $articleHeadingResult->fetch_array();
				echo substr($headingRow['Heading'], 0, 55) . "...";
			}
		?>
		)</span>
	</div>
	<?php
	break;
	case GENERAL_TASK_SET_RELATED_ARTICLES:
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; Set Related Articles 
		<span style="font-size:10px; color:#81AA2B">(
		<?php
			//get the article heading.
			$sql = "SELECT Heading FROM articles WHERE ArticleGenID='".$_GET['article']."'";
			$articleHeadingResult = $dbConn->query($sql);
			if ($articleHeadingResult && $articleHeadingResult->num_rows > 0)
			{
				$headingRow = $articleHeadingResult->fetch_array();
				echo substr($headingRow['Heading'], 0, 55) . "...";
			}
		?>
		)</span></div>
	<?php
	break;
	case GENERAL_TASK_VIEW_TOP_STORIES:
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; View Top Stories</div>
<?php
	break;
	case GENERAL_TASK_VIEW_TOP_STORIES:
	?>
	<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; View Groups</div>
<?php
	break;
	case ADMIN_TASK_POLL_BOOT:
		switch($sub_task)
		{
		case ADMIN_TASK_POLL_BOOT_POST:
	?>
			<div>Weekly Poll &gt; Post Weekly Poll</div>
<?php
		break;
		case ADMIN_TASK_POLL_BOOT_LISTING:
		?>
			<div>Weekly Poll &gt; Weekly Poll Listing</div>
		<?php
		break;
		case ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT:
		?>
			<div>Weekly Poll &gt; Edit Poll</div>
		<?php
		break;
		}
	break;
	case ADMIN_TASK_SELECT_TEMPLATE:
		?>
			<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $categoryName;?></a> &gt; Set Template</div>
		<?php
	break;
	case ADMIN_TASK_ADD_USER:
		?>
			<div>Add New CMS User</div>
		<?php
	break;
	case ADMIN_TASK_SELECTED_TEMPLATE_SET_SECTIONS:
		?>
			<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $categoryName;?></a> &gt; Set Template Sections <span style="color:#ADD658; font-size:10px">(<?php echo ucfirst(basename($_GET['sample'], ".php"));?>)</span></div>
		<?php
	break;
	case ADMIN_TASK_SELECT_TEMPLATE_SUBCATEGORY:
		?>
			<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; Set Template</div>
		<?php
	break;
	case ADMIN_TASK_SUBCATEGORY_SELECTED_TEMPLATE_SET_SECTIONS:
		?>
			<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; Set Template Sections</div>
		<?php
	break;
	case ADMIN_TASK_ADVERTS:
		switch($sub_task)
		{
		case ADMIN_TASK_ADVERTS_SUB_UPLOAD:
	?>
			<div>Adverts &gt; Upload Adverts</div>
<?php
		break;
		case ADMIN_TASK_ADVERTS_SUB_VIEW:
		?>
			<div>Adverts &gt; View Adverts</div>
		<?php
		break;
			case ADMIN_TASK_ADVERTS_SUB_REGISTER_CLIENT:
		?>
			<div>Adverts &gt; Register Client</div>
		<?php
		break;
		}
	break;
	case ADMIN_TASK_CREATE_SUBCATEGORY_GROUP:
		?>
			<div><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&tabName=<?php echo urlencode($tabName); ?>"><?php echo $scName;?></a> &gt; Create Group</div>
		<?php
	break;
	case ADMIN_TASK_ABOUT_US:
		?>
			<div>Write About Us</div>
		<?php
	break;
	case ADMIN_TASK_HOW_TO_PLACE_ADS:
		?>
			<div>Write How To Place Adverts</div>
		<?php
	break;
	case ADMIN_TASK_SET_TAB_INDEX:
		?>
			<div>Set Tab Order for Categories</div>
		<?php
	break;
	case ADMIN_TASK_SECTION_INDEX_TEMPLATE:
		?>
			<div>Set Index Template</div>
		<?php
	break;
	case ADMIN_TASK_MAINTENANCE:
		?>
			<div>Perform Maintenance Procedure</div>
		<?php
	break;
	}
?>
</div>
<?php
	if (isset($scGenID) && !empty($scGenID) && $_GET['tsk'] != GENERAL_TASK_RELATED_LINKS)
	{
		$sql = sprintf("SELECT SubCatName, SCGenID, CatGenID FROM subcategory WHERE CatGenID='%s' ORDER BY SubCatName", $selectedCategory);
		$subCatResult = $dbConn->query($sql);
		if ($subCatResult && $subCatResult->num_rows > 0)
		{
?>
<form method="post" action="room.php?<?php echo $_SERVER['QUERY_STRING']; ?>" id="changeSubcategoryToViewForm">
	<div style="float:right; width:410px; margin-top:6px;">
		<div class="entryLabel">
		Subcategory
		</div>
		<select style="width:173px; float:left; border:1px inset #EBEBEB;" id="switchSCSelector">
			<?php
				//sub category options filled in here.
				for (; ($sRow = $subCatResult->fetch_array()) != FALSE; )
				{
			?>
					<option value="<?php echo $sRow['SCGenID'];?>" <?php if ($sRow['SCGenID'] == $scGenID) echo 'selected="selected"'; ?>><?php echo $sRow['SubCatName'];?></option>
			<?php
				}
			?>
		</select>
		<div class="buttonOutline" style="float:right; margin:0px; margin-right:5px;">
			<input type="submit" class="clickButton clickable"  value="Click To Search" />
		</div>
	</div>
</form>
<?php
		}
	}
?>