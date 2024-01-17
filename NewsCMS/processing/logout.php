<?php
	require_once("../config/db.php");
	require_once("../includes/logNotifier.php");
	
	//check for user authentication registration.
	session_start();
	
	if ( !empty($_SESSION['authentication']) )
	{
		$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			$logger = new LogNotifier($dbConn);
			
			if ($logger)
				$logger->logNotification($_SESSION['TheUser'] . " logged out of the system", $_SESSION['xx']);
		}
		$dbConn->close();
		
		unset($_SESSION['authentication']);
		session_unset();
		session_destroy();
		
		header("Location: ../index.php");
		exit();
	}
	
	session_destroy();
	header("Location: ../index.php");
	exit();
?>