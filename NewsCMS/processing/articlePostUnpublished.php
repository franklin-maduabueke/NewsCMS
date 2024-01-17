<?php
	require_once("../modules/mysqli.php");
	require_once("../includes/user_checker.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");

	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	//process article publish and uploads
	$categoryId = $_GET['sel'];
	$subcategoryId = $_GET['sc'];
	$tabName = $_GET['tabName'];
	$article = $_GET['article'];
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$sql = sprintf("UPDATE articles SET PublishDate='%s' WHERE ArticleGenID='%s'", date("Y-m-d"),$article);
		$dbConn->query($sql);
	}
	
	header("Location: ../forms/room.php?sel=".$categoryId."&user=".$_SESSION['authentication']."&role=".$_SESSION['Role']."&tsk=" . GENERAL_TASK_UNPUBLISHED_ARTICLES ."&sc=".$subcategoryId."&tabName=".$tabName);
	exit();
?>