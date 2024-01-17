<?php
	require_once("../modules/mysqli.php");
	require_once("../includes/user_checker.php");
	require_once("../config/db.php");

	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	$groupId = $_GET['gid'];
	if (isset($groupId))
	{
		$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			$sql = sprintf("DELETE FROM subcategorygroup WHERE GroupGenID='%s'", $groupId);
			$dbConn->query($sql);
			$sql = sprintf("DELETE FROM grouparticle WHERE GroupGenID='%s'", $groupId);
			$dbConn->query($sql);
		}
	}

	header("Location: ../forms/room.php?user=" . $_SESSION['authenication'] . "&role=" . $_SESSION['Role'] . "&sel=" . $_GET['sel'] . "&sc=" . $_GET['sc'] . "&tsk=" . $_GET['tsk'] . "&tabName=" . $_GET['tabName']);
	exit();
?>