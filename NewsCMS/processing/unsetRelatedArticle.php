<?php
	require_once("../modules/mysqli.php");
	require_once("../includes/user_checker.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");

	if (! userSessionGood() )
	{
		header("Location: logout.php");
		exit();
	}
	
	$articleId = $_GET['article']; //parent article
	$uArticleId = $_GET['u_article']; //article to unset
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		//relate articles.
		$sql = sprintf("DELETE FROM articlerelated WHERE ParentArticleGenID='%s' AND RelatedArticleGenID='%s'", $articleId, $uArticleId);
		$dbConn->query($sql);
	}
	
	header("Location: ../forms/room.php?sel=" . $_GET['sel'] . "&user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . GENERAL_TASK_RELATED_LINKS . "&sc=" . $_GET['sc'] . "&tabName=" . $_GET['tabName'] . "&article=" . $_GET['article']);
	exit();
?>