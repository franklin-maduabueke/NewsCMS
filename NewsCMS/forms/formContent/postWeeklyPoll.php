<?php
	if ($sub_task == ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT)
	{
		$poll_id = $_GET['poll_id'];
		//make sure we are connected.
		if ($dbConn->ping())
		{
			$pollResult = $dbConn->query("SELECT PollGenID, OpenDate, CloseDate, Question FROM polls WHERE PollGenID='$poll_id'");
			if ($pollResult && $pollResult->num_rows > 0)
			{
				$pollRow = $pollResult->fetch_array();
			}
		}
	}
?>
<form  action="../processing/uploadPoll.php" method="post" id="postPollForm">
	<input type="hidden" name="subAction" value="<?php if ($sub_task == ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT) echo "on"; else echo "off";?>" />
	<input type="hidden" name="pollGenID" value="<?php if ($sub_task == ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT) echo $poll_id;?>" />
	<table id="publishedListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:348px">
					Poll Question
				</td>
				<td style="padding-left:0px; text-align:center; width:249px">
					Opening Date
				</td>
				<td style="padding-left:0px; text-align:center; width:249px">
					Closing Date
				</td>
				<td style="border-right:1px solid #FEE956; padding-left:0px; text-align:center;">
					Actions
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td style="text-align:left">&nbsp;
					
				</td>
				<td colspan="2" style="text-align:center">&nbsp;
					
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">&nbsp;
					
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td style="border-left:0px;">
					<input type="text" name="pollQuestionBox" style="width:300px; border:1px solid #CCCCCC" maxlength="100" value="<?php echo $pollRow['Question'];?>" />
				</td>
				<td>
					<select name="oday" id="oday">
										<?php
											if ($sub_task == ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT)
												$openDay = date("j", strtotime($pollRow['OpenDate']));
											else
												$openDay = date("j");
											
											for ($i = 1; $i <= 31; $i++)
											{
												$selected = '';
												if ($openDay == $i)
													$selected = "selected='selected'";
													
												echo "<option value='$i' $selected>$i</option>";
											}
										?>
									</select>
									<select name="omonth" id="omonth">
									  <?php
									  		$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
											if ($sub_task == ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT)
												$openMonth = date("F", strtotime($pollRow['OpenDate']));
											else
												$openMonth = date("F");
												
											for ($i = 0; $i < 12; $i++)
											{
												$selected = '';
												
												if ( $openMonth == $months[$i] )
												{
													$selected = "selected='selected'";
												}
												$incre = $i + 1;
												echo "<option value='$incre' $selected>" . $months[$i] . "</option>";
											}
									?>
									</select>
									<select name="oyear" id="oyear">
										<?php
											$nowYear = date("Y");
											echo "<option value='$nowYear' selected='selected'>$nowYear</option>";
											$nowYear++;
											echo "<option value='$nowYear'>$nowYear</option>";
										?>
									</select>
				</td>
				<td>
					<select name="cday" id="cday">
										<?php
											if ($sub_task == ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT)
												$closeDay = date("j", strtotime($pollRow['CloseDate']));
											else
												$closeDay = date("j");
											
											for ($i = 1; $i <= 31; $i++)
											{
												$selected = '';
												if ($closeDay == $i)
													$selected = "selected='selected'";
													
												echo "<option value='$i' $selected>$i</option>";
											}
										?>
									</select>
									<select name="cmonth" id="cmonth">
									  <?php
									  		$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
											if ($sub_task == ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT)
												$closeMonth = date("F", strtotime($pollRow['CloseDate']));
											else
												$closeMonth = date("F");
											
											for ($i = 0; $i < 12; $i++)
											{
												$selected = '';
												
												if ( $closeMonth == $months[$i] )
												{
													$selected = "selected='selected'";
												}
												$incre = $i + 1;
												echo "<option value='$incre' $selected>" . $months[$i] . "</option>";
											}
									?>
									</select>
									<select name="cyear" id="cyear">
										<?php
											$nowYear = date("Y");
											echo "<option value='$nowYear' selected='selected'>$nowYear</option>";
											$nowYear++;
											echo "<option value='$nowYear'>$nowYear</option>";
										?>
									</select>
				</td>
				<td style="text-align:center">
					<input type="submit" class="clickable" name="submitButton" value="Upload" style="color:#FFFFFF; width:96px; height:31px; background-color:#ADD658; border:1px solid #81AA2B" />
				</td>
			</tr>
		</tbody>
	</table>
</form>