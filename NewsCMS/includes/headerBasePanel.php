<div id="headerBasePanel">
	<div id="userNameHolder">
		<?php
			if (isset($_SESSION['authentication']))
			{
				$name = explode(' ', $_SESSION['TheUser']);
				echo "Welcome " . ucfirst($name[0]) . ' ' . ucfirst($name[1]);
				if ($_SESSION['Role'] == USER_ADMIN)
					echo " &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;(Administrator)";
				else
					if ($_SESSION['Role'] == USER_BASIC)
					echo " &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;(Editor)";
			}
		?>
	</div>
	<div id="itemHolder">
		<?php
			$addNewUser = urlencode("Add New User");
			if (PAGE_NAME == 'room')
			{
		?>
		<!--<a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>">Change Password</a><?php if ($_SESSION['Role'] == 1) {?><label class="divider">|</label>--><a href="room.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&t=active_category&tsk=add_new_user&tabName=<?php echo $addNewUser;?>">Add New User</a><?php } ?><label class="divider">|</label><a href="../processing/logout.php">Logout</a>
		<?php 
			}
		?>
	</div>
</div>