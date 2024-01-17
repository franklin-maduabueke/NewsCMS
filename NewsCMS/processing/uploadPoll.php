<?php
	//script to upload poll question.
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");
	require_once("../includes/user_checker.php");
	require_once("../includes/logNotifier.php");
	
	function  generateID($markerLength)
	{
		//script to create new user for the cms.
		//generate photo name for this.
		//number of letters each photo will carry on its name.
		if ($markerLength <= 0)
			return FALSE;
		
		$alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_";
		$rangeLength = strlen($alphabets);
		$marker = '';
		
		$marker = '';
		for ($count = 0; $count < $markerLength; $count++)
		{
			$rand = rand(0, $rangeLength - 1);
			$marker .= substr($alphabets, $rand, 1);
		}
		return $marker;
	}
	
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	$msg = "Poll was not uploaded successfully. Make sure you set the fields";
	
	$pollQuestion = trim($_POST['pollQuestionBox']);
	
	$oday = $_POST['oday'];
	$omonth = $_POST['omonth'];
	$oyear = $_POST['oyear'];
	
	$cday = $_POST['cday'];
	$cmonth = $_POST['cmonth'];
	$cyear = $_POST['cyear'];
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		if (isset($pollQuestion) && !empty($pollQuestion))
		{
			$pollQuestion = $dbConn->real_escape_string($pollQuestion);
			//remove all question marks and put single question mark.
			$pollQuestion = str_replace("?", "", $pollQuestion);
			$pollQuestion .= "?";
			
			//generate an artile Id and commit.
			for ($j = 0; $j < 20; $j++) //try generating a usable name for the user or quit on 20 trys.
			{
				$marker = generateID(10);
				if ($marker != FALSE)
				{
					$sql = sprintf("SELECT * FROM polls WHERE PollGenID='%s'", $marker);
					$result = $dbConn->query($sql);
				
					if ($result && $result->num_rows == 0)
					{
						//commit article id and break.
						if ($_POST['subAction'] == "on")
							//doing edits
							$sql = sprintf("UPDATE polls SET OpenDate='%s', CloseDate='%s', Question='%s' WHERE PollGenID='%s'", $oyear."-".$omonth."-".$oday, $cyear."-".$cmonth."-".$cday, $pollQuestion, $_POST['pollGenID']);
						else
							//posting new poll
							$sql = sprintf("INSERT INTO polls (PollGenID, OpenDate, CloseDate, Question) VALUES('%s', '%s', '%s', '%s')", $marker,$oyear."-".$omonth."-".$oday, $cyear."-".$cmonth."-".$cday, $pollQuestion);
					
						//echo $sql;
						$dbConn->query($sql);
						if ($dbConn->sqlstate == "00000")
							$msg = "Last poll was uploaded successfully";
							
						break;
					}
					else
						continue;
				}
			}
		}
	}
	
	header("Location: ../forms/room.php?user=".$_SESSION['authentication']."&role=".$_SESSION['Role']. "&tsk=" . ADMIN_TASK_POLL_BOOT . "&sa=" . ADMIN_TASK_POLL_BOOT_POST . "&msg=" . urlencode($msg));
	exit();
?>