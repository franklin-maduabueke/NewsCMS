<?php
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/user_checker.php");
	require_once("../includes/commons.php");
	include_once("../includes/logNotifier.php");
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	//processing recover.
	$msg = "Recovery was not successful.";
	
	$backupFile = $_POST['backupFile'];
	
	if (isset($backupFile) && !empty($backupFile))
	{
		$visitorBackupPath = $_SERVER['DOCUMENT_ROOT']."/havilah_vis/backups/visitors/$backupFile";
		$logBackupPath = $_SERVER['DOCUMENT_ROOT']."/havilah_vis/backups/sysLogs/$backupFile";
	
		$recoverVisitorSql = "LOAD DATA INFILE '$visitorBackupPath' REPLACE INTO TABLE VisitorList";
		$recoverLogSql = "LOAD DATA INFILE '$logBackupPath' REPLACE INTO TABLE LogNotification";
	
		$dbConn = new mysqli(DB_SERVER, DB_USER);
	
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			if ( file_exists($visitorBackupPath) && file_exists($logBackupPath) )
			{
				if ($dbConn->query($recoverVisitorSql) && $dbConn->query($recoverLogSql))
				{
					$msg = "Recovery was done successfully";
					
					$logger = new LogNotifier($dbConn);
					if ($logger)
						$logger->logNotification($_SESSION['theUser'] . " recovered the database with backup file name \"$backupFile\"",$_SESSION['UserID']);
				}
			}
		}
	}
	

	$dbConn->close();
	$msg = urlencode($msg);
	header("Location: ../forms/room.php");
	exit();
?>