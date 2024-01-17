<?php

	function  generateID($markerLength)
	{
		//script to create new user for the cms.
		//generate photo name for this.
		//number of letters each photo will carry on its name.
		if ($markerLength <= 0)
			return FALSE;
		
		$alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
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
	
	//backup processing.
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/user_checker.php");
	require_once("../includes/commons.php");
	require_once("../includes/logNotifier.php");
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	$msg = "Backup was not successful";
	$backupName = $_POST['backupRARName']; //name for the produced .rar file or default if not given
	
	$ini = parse_ini_file("../config/app.ini", TRUE);
	if ($ini && array_key_exists('site_path', $ini['PATHS']))
	{
		$site_path = $ini['PATHS']['site_path'];
		
		$root = $site_path . "news_cms_backups";
	
		if (!file_exists($root))
		{
			mkdir($root);	//create the backup folder.
		}

		$markedSuccess = false;
		if (!isset($backupName))
		{
			//generate file name for this.
			$prefix = "havilah_yoursitecmsbackup_"
			for ($i = 0; $i < 20; $i++)
			{
				if ($genID = genertateID(5)) //eight characters for file name prefixed by 
				{
					$genID = $prefix . $genID;
					
					if (!file_exists($root . "/$genID.rar"))
					{
						$markedSuccess = TRUE;
						$backupName = $genID;
						break;
					}
				}
			}
		}
		else
		{
			//if users provided name has been assigned to a zip in backup folder then that will be overwriten.
			//users should accept to download once the backup process is done.
			$markedSuccess = TRUE;
		}
					
		if ($markedSuccess)
		{
			$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		
			$root = str_replace(".","",$root);
			$root = str_replace("/","",$root);
		
			$backupPath = $_SERVER['DOCUMENT_ROOT'] . "/nigeriann/$root/visitors/" . $backupName;
		
			if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
			{
				$sql = "SELECT * INTO OUTFILE '$backupPath' FROM VisitorList";
				$dbConn->query($sql);
			
				if ($dbConn->sqlstate == "00000")
					$msg = "Backup was successfull";
			}
		}
	}
	
	$msg = urlencode($msg);
	header("Location: ../forms/room.php?user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . ADMIN_TASK_MAINTENANCE);
	exit();
?>