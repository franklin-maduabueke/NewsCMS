<?php
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");
	require_once("../includes/logNotifier.php");
	
	$msg = "Username or Password was incorrect.";
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		echo "Connection Good!";
		
		$username = $dbConn->real_escape_string($username);
		$password = $dbConn->real_escape_string($password);
		$sql = sprintf("SELECT CONCAT(Firstname, ' ', Surname) AS `Name`, Role, UserGenID, UserTID FROM cmsusers WHERE Username='%s' AND Password=AES_ENCRYPT('%s','massive')", $username, $password);
		
		$result = $dbConn->query($sql);
		
		echo $dbConn->error;
		
		if ($result && $result->num_rows > 0)
		{
			echo "Session set!";
			die();
			
			$row = $result->fetch_array();
			
			session_start();
			$_SESSION["authentication"] = $row['UserGenID'];
			$_SESSION["Role"] = $row['Role'];
			$_SESSION['TheUser'] = $row['Name'];
			$_SESSION['xx'] = $row['UserTID'];
			
			$logger = new LogNotifier($dbConn);
			if ($logger)
				$logger->logNotification($_SESSION['TheUser'] . 'logged into the CMS', $row['UserTID']);
			
			header("Location: ../forms/room.php?user=".$_SESSION['authentication']."&role=".$_SESSION['Role']."&tsk=" . GENERAL_TASK_SELECT_CATEGORY);
			exit();
		}
	}

	$msg = urlencode($msg);
	header("Location: ../index.php?msg=$msg");
	exit();
?>