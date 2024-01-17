<?php
	//listing for published articles in this category.
	$pageSize = 10; //reset to a higer value like 14.
	$countResult = $dbConn->query("SELECT COUNT(PollTID) FROM polls");
	
	$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
	if ($countResult && $countResult->num_rows > 0)
	{
		$countRow = $countResult->fetch_array();
		$count = $countRow[0];
		$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
		$offset = $pageSize * ($current_page - 1);
	
		$sql = "SELECT PollGenID, Question, YesCount, NoCount, OpenDate, CloseDate FROM polls ORDER BY OpenDate DESC, PollTID LIMIT $pageSize OFFSET $offset";
		$wPollResult = $dbConn->query($sql);
		 
		if ($wPollResult && $wPollResult->num_rows > 0)
		{
?>
	<table id="publishedListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:600px">
					Poll Question
				</td>
				<td style="padding-left:0px; text-align:center; width:138px">
					Yes Votes
				</td>
				<td style="padding-left:0px; text-align:center; width:138px">
					No Votes
				</td>
				<td style="width:150px">
					Date &amp; Time
				</td>
				<td style="border-right:1px solid #FEE956; padding-left:0px; text-align:center;">
					Actions
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td style="text-align:left">
					<?php if ($current_page > 1) { ?><a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=poll_boot&sa=list_poll&page_no=<?php echo $current_page - 1; ?>">&laquo; Previous</a><?php } ?>
				</td>
				<td colspan="3" style="text-align:center">
					<label style="color:#0033FF">Page: <?php echo $current_page; ?> / <?php echo $totalPages; ?></label>
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">
					<?php if ($current_page < $totalPages) { ?><a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=poll_boot&sa=list_poll&page_no=<?php echo $current_page + 1; ?>">Next &raquo;</a><?php } ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			for (; ($wPollRow = $wPollResult->fetch_array()) != FALSE; )
			{
		?>
			<tr>
				<td style="border-left:0px; padding-left:15px; padding-right:5px">
					<?php echo $wPollRow['Question']; ?>
				</td>
				<td style="text-align:center">
					<?php
						//calculate yes votes
						$totalVotes = $wPollRow['YesCount'] + $wPollRow['NoCount'];
						if ($totalVotes != 0)
						{
							$yes = $wPollRow['YesCount'];
							$yesPercentage = $yes / $totalVotes * 100;
							echo (ceil($yesPercentage)) . "%";
						}
						else
							echo "No Votes";
					?>
				</td>
				<td style="text-align:center">
					<?php
						//calculate no votes
						$totalVotes = $wPollRow['YesCount'] + $wPollRow['NoCount'];
						if ($totalVotes != 0)
						{
							$no = $wPollRow['NoCount'];
							$noPercentage = $no / $totalVotes * 100;
							echo (ceil($noPercentage)) . "%";
						}
						else
							echo "No Votes";
					?>
				</td>
				<td>
					<?php echo date("j F Y", strtotime($wPollRow['OpenDate'])); ?>
				</td>
				<td style="text-align:center">
					<label><a href="../processing/deletePoll.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=poll_boot&poll_id=<?php echo $wPollRow['PollGenID'];?>">Delete</a></label><label>|</label><label><a href="room.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=<?php echo ADMIN_TASK_POLL_BOOT;?>&sa=<?php echo ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT;?>&poll_id=<?php echo $wPollRow['PollGenID'];?>">Edit</a></label>
				</td>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>
<?php
		}
		else
		{
		?>
		<div class="noticeBoard">
			<div>No Polls posted.</div>
		</div>
	<?php
		}
	}
	else
	{
	?>
	<div class="noticeBoard">
		<div>No Polls posted.</div>
	</div>
	<?php
	}
?>