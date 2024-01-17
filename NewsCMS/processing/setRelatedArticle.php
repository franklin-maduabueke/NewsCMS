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
	
	$articleId = $_GET['article'];
	$rArticleId = $_GET['r_article'];
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		//relate articles.
		$sql = sprintf("INSERT INTO articlerelated (ParentArticleGenID, RelatedArticleGenID) VALUES('%s','%s')", $articleId, $rArticleId);
		$dbConn->query($sql);
		//child must also have parent as its related article when views.
		$sql = sprintf("INSERT INTO articlerelated (ParentArticleGenID, RelatedArticleGenID) VALUES('%s','%s')", $rArticleId, $articleId);
		$dbConn->query($sql);
	}
	
	header("Location: ../forms/room.php?sel=" . $_GET['sel'] . "&user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . GENERAL_TASK_SET_RELATED_ARTICLES . "&sc=" . $_GET['sc'] . "&tabName=" . urlencode($_GET['tabName']) . "&article=" . $_GET['article']);
	exit();
?>