<?php
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");
	require_once("../includes/user_checker.php");
	require_once("../includes/logNotifier.php");
	
	define("FLASH", 1);
	define("IMAGE", 2);
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	if ($_SESSION['Role'] != USER_ADMIN)
	{
		header("Location: logout.php");
		exit();
	}
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$advertResult = $dbConn->query(sprintf("SELECT AdvertType FROM adverts WHERE AdsGenID='%s'", $_GET['ad']));
		if ($advertResult && $advertResult->num_rows > 0)
		{
			$advertRow = $advertResult->fetch_array();
			
			$sql = sprintf("DELETE FROM adverts WHERE AdsGenID='%s'", $_GET['ad']);
			$dbConn->query($sql);
			switch ($advertRow['AdvertType'])
			{
			case IMAGE:
				$sql = sprintf("DELETE FROM imageads WHERE AdsGenID='%s'", $_GET['ad']);
				$dbConn->query($sql);
			break;
			case FLASH:
				$sql = sprintf("DELETE FROM flashads WHERE AdsGenID='%s'", $_GET['ad']);
				$dbConn->query($sql);
			break;
			}
		}
		
		$dbConn->close();
	}
	
	header("Location: ../forms/room.php?user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . ADMIN_TASK_ADVERTS . "&sa=" . ADMIN_TASK_ADVERTS_SUB_VIEW);
	exit();
?>