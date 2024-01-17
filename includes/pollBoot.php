<?php
/*
	$prepend = "";
	
	if (PAGE_NAME != "home")
		$prepend = "../";
	
	require_once($prepend ."modules/mysqli.php");
	require_once($prepend . "config/db.php");
*/
	$sql = sprintf("SELECT PollGenID, Question, YesCount, NoCount FROM polls WHERE CloseDate >= DATE(NOW())");
	$pollResult = $dbConn->query($sql);
	
	if ($pollResult && $pollResult->num_rows > 0)
	{
		//make a random selection if we find more than 1 poll question.
		$pollIndex = 0;
		if ($pollResult->num_rows > 1)
			$pollIndex = rand(0, $pollResult->num_rows - 1);
		
		$pollResult->data_seek($pollIndex);
		
		$displayPollRow = $pollResult->fetch_array();
		
?>

<script type="text/javascript">
	//include pollResult script
	$().ready( function () {
		$.getScript("<?php echo $prefix;?>scripts/viewPollResult.js");
	});
</script>

<div id="pollingBoot" style="margin-bottom:0px">
	<div style="border-bottom:1px solid #CCCCCC; height:28px" id="bootHeading">
		<div style="font-size:14px; font-weight:bold; padding-top:5px; margin-left:10px" id="bootHeadingText">Weekly Poll</div>
	</div>
	<div id="pollQuestionHolder" style="margin-top:12px;">
		<div id="pollQuestionBox" style="width:288px; max-height:45px; height:auto; margin-left:12px">
			<span class="midNewsExText"><?php echo $displayPollRow['Question']; ?></span>
		</div>
		<div id="selectionCtrlsHolder">
			<form action="<?php echo $prefix; ?>processing/vote.php" method="post">
				<input type="hidden" name="pollGenId" id="pollGenId" value="<?php echo $displayPollRow['PollGenID'];?>" />
				<input type="radio" name="choice" id="yesChoice" value="yes" style="margin-left:0px; padding-left:0px"/><label style="color:#666666; font-size:12px; margin-right:22px;">Yes</label>
				<input type="radio" name="choice" id="noChoice" value="no" /><label style="color:#666666; font-size:12px;margin-right:22px;">No</label>
				<input type="submit" class="clickable" value="" style="border:0px;background-image:url(<?php echo $prefix;?>images/voteBtn.jpg); width:53px; height:20px;margin-right:22px;" />
				<a href="viewResults.php" id="viewPollResults">View Results</a>
			</form>
		</div>
	</div>
</div>

<div id="pollResultHolder" style="height:29px; background-color:#FFFFFF; margin-bottom:10px">
	<div id="yesVotesResult" style="height:100%; width:50%; background-color:#27BFE8; color:#FFFFFF; font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif; float:left; font-size:12px;">
		<div class="valueHolder" style="margin-top:5px">
			<?php
						//calculate yes votes
						$totalVotes = $displayPollRow['YesCount'] + $displayPollRow['NoCount'];
						if ($totalVotes != 0)
						{
							$yes = $displayPollRow['YesCount'];
							$yesPercentage = $yes / $totalVotes * 100;
							echo (ceil($yesPercentage)) . "% Vote Yes";
						}
						else
							echo "No Votes";
			?>
		</div>
	</div>
	
	<div id="noVotesResult" style="height:100%; width:50%; background-color:#E00000; color:#FFFFFF; font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif; float:right; font-size:12px">
		<div class="valueHolder" style="margin-top:5px">
			<?php
						//calculate no votes
						$totalVotes = $displayPollRow['YesCount'] + $displayPollRow['NoCount'];
						if ($totalVotes != 0)
						{
							$no = $displayPollRow['NoCount'];
							$noPercentage = $no / $totalVotes * 100;
							echo (ceil($noPercentage)) . "% Vote No";
						}
						else
							echo "No Votes";
			?>
		</div>
	</div>
</div>
<div style="margin-bottom:10px"></div>
<?php
	}
?>