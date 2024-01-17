<ul id="rightSideMenu">
<?php
	if (!isset($scGenID) && $current_task == GENERAL_TASK_SELECT_CATEGORY)
	{
	?>
	<li><a href="room.php?user=<?php echo $_SESSION['authentication'];?>&amp;role=<?php echo $_SESSION['Role'];?>&t=active_category&tabName=<?php echo urlencode("Create New Category");?>&tsk=create_category">Create Category</a></li>
	<?php if ($categoryCount > 0 && $current_task != ADMIN_TASK_SET_TAB_INDEX) { ?><li><a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&t=active_category&amp;tabName=<?php echo urlencode("Create New Category");?>&tsk=set_tab_index">Set Tab Index</a></li>
	<?php } ?>
<?php
	}
	if (isset($selectedCategory) && !empty($selectedCategory) && !isset($scGenID))
	{
	?>
		<?php 
			if ($_SESSION['Role'] == 1)
			{
			?>
	<?php if ($current_task != GENERAL_TASK_CREATE_SUB_CATEGORY) { ?><li><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=create_sub_category&tabName=<?php echo urlencode($tabName); ?>">Create Subcategory</a></li><?php } ?>
		<?php
			}
			?>
			
	<?php if ($current_task == GENERAL_TASK_SUB_CATEGORY_LISTING && $_SESSION['Role'] == 1) { ?><li><a href="../processing/deleteCategory.php?sel=<?php echo $selectedCategory; ?>">Delete Category</a></li><?php } ?>
<?php
	}
	if (isset($scGenID) && !empty($scGenID))
	{
?>
	<?php if ($current_task != GENERAL_TASK_PUBLISHED_ARTICLES) {?><li><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=published_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>">Published Article Listing</a></li><?php } ?>
	
	<?php if ($current_task != GENERAL_TASK_UNPUBLISHED_ARTICLES) { ?><li><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=unpublished_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>">Unpublished Article Listing</a></li><?php } ?>
	
	<?php if ($current_task != GENERAL_TASK_WRITE_ARTICLE) { ?><li><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=write_article&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>">Write Article</a></li><?php } ?>
	
	<?php if ($current_task != GENERAL_TASK_VIEW_TOP_STORIES) { ?><li><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=view_top_stories&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>">View Top Stories</a></li><?php } ?>
	
	<?php if ($current_task != GENERAL_TASK_SET_RELATED_ARTICLES) { ?><li id="setRelatedArticleItem"><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=set_related_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&article=<?php echo $_GET['article'];?>">Set Related Articles</a></li>
	<?php } ?>
	
	<?php if ($current_task != GENERAL_TASK_SELECT_CATEGORY && $current_task != GENERAL_TASK_SUB_CATEGORY_LISTING && $_SESSION['Role'] == 1) { ?>
			<li>
				<a href="../processing/deleteSubcategory.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=sub_category_listing&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>">Delete Subcategory</a>
			</li>
		<?php } ?>
	
		<?php
			if ($user == USER_ADMIN)
			{
		?>
		<li>
				<a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&sel=<?php echo $selectedCategory;?>&sc=<?php echo $scGenID;?>&tsk=create_subcategory_group&tabName=<?php echo urlencode($tabName); ?>">Create Groups</a>
		</li>
		<?php
			}
		?>
		
		<?php
			if ($current_task != GENERAL_TASK_VIEW_SUBCATEGORY_GROUP)
			{
		?>
		<li>
				<a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&sel=<?php echo $selectedCategory;?>&sc=<?php echo $scGenID;?>&tsk=view_subcategory_group&tabName=<?php echo urlencode($tabName); ?>">View Groups</a>
		</li>
		<?php
			}
		?>
<?php
	}
?>
	<?php if ($current_task == ADMIN_TASK_POLL_BOOT) { ?>
		<?php
			if ($sub_task != ADMIN_TASK_POLL_BOOT_LISTING)
			{
		?>
			<li>
				<a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=poll_boot&sa=list_poll">Weekly Poll Listing</a>
			</li>
		<?php
			}
		?>
		
		<?php
			if ($sub_task != ADMIN_TASK_POLL_BOOT_POST)
			{
		?>
			<li>
				<a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=poll_boot&sa=post_poll">Post Poll</a>
			</li>
		<?php
			}
		?>
		
	<?php } ?>
	
	<?php
		if ($current_task == GENERAL_TASK_SUB_CATEGORY_LISTING && $_SESSION['Role'] == 1)
		{
		?>
			<li>
				<a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&sel=<?php echo $selectedCategory;?>&tsk=select_template&tabName=<?php echo urlencode($tabName); ?>">Set Category Template</a>
			</li>
		<?php
		}
	?>
	
	<?php
		if (isset($scGenID) && !empty($scGenID) && $current_task != ADMIN_TASK_SELECT_TEMPLATE_SUBCATEGORY && $_SESSION['Role'] == 1)
		{
		?>
			<li>
				<a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&sel=<?php echo $selectedCategory;?>&tsk=select_template_subcategory&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>">Set Subcategory Template</a>
			</li>
		<?php
		}
	?>
	
	<?php if ($current_task == ADMIN_TASK_ADVERTS) { ?>
		<?php
			if ($sub_task != ADMIN_TASK_ADVERTS_SUB_UPLOAD)
			{
		?>
			<li>
				<a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=adverts&sa=upload_adverts">Upload Adverts</a>
			</li>
		<?php
			}
		?>
		
		<?php
			if ($sub_task != ADMIN_TASK_ADVERTS_SUB_VIEW)
			{
		?>
			<li>
				<a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=adverts&sa=view_adverts">View Adverts</a>
			</li>
		<?php
			}
		?>
			<?php if ($current_task != ADMIN_TASK_ADVERTS_SUB_REGISTER_CLIENT) { ?><li><a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=adverts&sa=register_clients">Register Ads Client</a></li><?php } ?> <!-- for administrators only -->
			
	<?php } ?>

<?php if ($current_task != ADMIN_TASK_MAINTENANCE && $_SESSION['Role'] == 1) { ?>
			<!--<li>
				<a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=maintenance">Maintenance</a>
			</li>-->
<?php } ?>
</ul>