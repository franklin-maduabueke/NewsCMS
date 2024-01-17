<?php
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");
	require_once("../includes/user_checker.php");
	require_once("../includes/logNotifier.php");
	
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
		$categoryCount = $_POST['categoryCount'];
		if ($categoryCount > 0)
		{
			for ($i = 1; $i <= $categoryCount; $i++)
			{
				$catGenID = $_POST['cat' . $i . 'GenID'];
				$sql = sprintf("UPDATE category SET WebCatTabIndex=%d WHERE CatGenID='%s'", $_POST['cat' . $i . 'Control'], $catGenID);
				$dbConn->query($sql);
			}
		}
	}
	
	header("Location: ../forms/room.php?user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . GENERAL_TASK_SELECT_CATEGORY);
	exit();
?>