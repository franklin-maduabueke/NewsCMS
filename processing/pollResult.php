<?php
	//script called to fetch poll result
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	
	$pollId = $_POST['pollId'];
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		if (isset($pollId))
		{
			$wPollResult = $dbConn->query("SELECT PollGenID, Question, YesCount, NoCount, OpenDate, CloseDate FROM polls WHERE PollGenID='$pollId'");
			
			if ($wPollResult && $wPollResult->num_rows > 0)
			{
				$wPollRow = $wPollResult->fetch_array();
				$xml = '<?xml version="1.0" encoding="iso-8859-1"?>
							<pollResults xmlns="http://www.w3schools.com"	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.w3schools.com template.xsd">';
				
				$yesVotes = $noVotes = "No Votes";
				//calculate yes votes
				$totalVotes = $wPollRow['YesCount'] + $wPollRow['NoCount'];
				if ($totalVotes != 0)
				{
					$yes = $wPollRow['YesCount'];
					$yesPercentage = $yes / $totalVotes * 100;
					$yesVotes = (ceil($yesPercentage)) . "% Vote Yes";
				}
				//calculate no votes
				$totalVotes = $wPollRow['YesCount'] + $wPollRow['NoCount'];
				if ($totalVotes != 0)
				{
					$no = $wPollRow['NoCount'];
					$noPercentage = $no / $totalVotes * 100;
					$noVotes = (ceil($noPercentage)) . "% Vote No";
				}
				
				$xml .= sprintf("<yes>%s</yes>", $yesVotes);
				$xml .= sprintf("<no>%s</no>", $noVotes);
				$xml .= "</pollResults>";
				
				echo $xml;
			}
		}
		else
			echo "0";
	}
	else
		echo "0";
	
	flush();
	exit();
?>