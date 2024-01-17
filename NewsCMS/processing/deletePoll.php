<?php
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");
	require_once("../includes/logNotifier.php");
	require_once("../includes/user_checker.php");
	
	if (!userSessionGood())
	{
		header("Location: ../processing/logout.php");
		exit();
	}
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$sql = sprintf("DELETE FROM polls WHERE PollGenID='%s'", $_GET['poll_id']);
		$dbConn->query($sql);
	}
	
	header("Location: ../forms/room.php?user=". $_SESSION['authentication']. "&role=" . $_SESSION['Role'] . "&tsk=" . ADMIN_TASK_POLL_BOOT ."&sa=" . ADMIN_TASK_POLL_BOOT_LISTING);
	exit();
?>