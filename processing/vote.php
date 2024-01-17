<?php
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");
	
	$pollId = $_POST['pollGenId'];
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		if ($_POST['choice'] == "yes")
		{
			$sql = sprintf("SELECT YesCount FROM polls WHERE PollGenID='%s'", $pollId);
			$result = $dbConn->query($sql);
			
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$yesCount = $row['YesCount'];
				$yesCount++;
				$sql = sprintf("UPDATE polls SET YesCount=%d WHERE PollGenID='%s'", $yesCount, $pollId);
				$dbConn->query($sql);
			}
		}
		else
		{
			if ($_POST['choice'] == "no")
			{
				$sql = sprintf("SELECT NoCount FROM polls WHERE PollGenID='%s'", $pollId);
				$result = $dbConn->query($sql);
			
				if ($result && $result->num_rows > 0)
				{
					$row = $result->fetch_array();
					$noCount = $row['NoCount'];
					$noCount++;
					$sql = sprintf("UPDATE polls SET NoCount=%d WHERE PollGenID='%s'", $noCount, $pollId);
					$dbConn->query($sql);
				}
			}
		}
	}
	
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit();
?>