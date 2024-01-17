<?php
	switch ($user)
	{
	case USER_BASIC:
?>
	<ul id="taskbar">
		<li <?php if ($current_task == GENERAL_TASK_SELECT_CATEGORY) echo 'class="activeTask"'; else echo 'class="inactiveTask clickable"';?>><div><a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=select_category">Select Category</a></div></li>
		
<?php
	if ($current_task != GENERAL_TASK_SELECT_CATEGORY || $current_task == GENERAL_TASK_WRITE_ARTICLE)
	{ 
?>
		<?php if (isset($selectedCategory) && !empty($selectedCategory)) { ?><li <?php if (isset($selectedCategory) && !empty($selectedCategory)) echo 'class="activeTask"'; else echo 'class="inactiveTask clickable"';?>><div><a <?php if (!isset($selectedCategory) || empty($selectedCategory)) echo 'href="#"';?>><?php echo $tabName; ?></a></div></li>
<?php
	}
?>
		<?php } ?>
	</ul>
<?php
	break;
	case USER_ADMIN:
	?>
	<ul id="taskbar">
		<li <?php if ($current_task == GENERAL_TASK_SELECT_CATEGORY) echo 'class="activeTask"'; else echo 'class="inactiveTask clickable"';?>><div><a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=select_category">Select Category</a></div></li>

<?php
	if ($current_task != GENERAL_TASK_SELECT_CATEGORY)
	{ 
?>
		<?php if (isset($selectedCategory) && !empty($selectedCategory)) { ?><li <?php if (isset($selectedCategory) && !empty($selectedCategory)) echo 'class="activeTask"'; else echo 'class="inactiveTask clickable"';?>><div><a <?php if (!isset($selectedCategory) || empty($selectedCategory)) echo 'href="#"';?>><?php echo $tabName; ?></a></div></li><?php } ?>
<?php
	}
?>

		<li <?php if ($current_task == ADMIN_TASK_POLL_BOOT) echo 'class="activeTask"'; else echo 'class="inactiveTask clickable"';?>><div><a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=poll_boot&sa=list_poll">Weekly Polls</a></div></li>
		
		<li <?php if ($current_task == ADMIN_TASK_ADVERTS) echo 'class="activeTask"'; else echo 'class="inactiveTask clickable"';?>><div><a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=adverts&sa=upload_adverts">Adverts</a></div></li>
		
		<li <?php if ($current_task == ADMIN_TASK_ABOUT_US) echo 'class="activeTask"'; else echo 'class="inactiveTask clickable"';?>><div><a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=about_us">About Us</a></div></li>
		
		<li <?php if ($current_task == ADMIN_TASK_HOW_TO_PLACE_ADS) echo 'class="activeTask"'; else echo 'class="inactiveTask clickable"';?>><div><a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=how_to_place_ads">How To Place Adverts</a></div></li>
	</ul>
<?php
	break;
	}
?>