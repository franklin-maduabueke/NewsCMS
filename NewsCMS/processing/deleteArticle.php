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
	
	$userId = $_SESSION['authentication'];
	$categoryId = $_GET['sel'];
	$subcategoryId = $_GET['sc'];
	$articleId = $_GET['article'];
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		//delete the article.
		$sql = "DELETE FROM breakingnews WHERE ArticleGenID='$articleId'";
		$dbConn->query($sql);
		
		$sql = sprintf("DELETE FROM articlerelated WHERE ParentArticleGenID='%s'", $articleId);
		$dbConn->query($sql);
		
		$sql = sprintf("DELETE FROM articles WHERE ArticleGenID='%s' AND SCGenID='%s'", $articleId, $subcategoryId);
		$dbConn->query($sql);
		
		if ($dbConn->sqlstate == "00000")
		{
			//log notification and set message to delete operation successful
			$sql = sprintf("DELETE FROM articlephoto WHERE ArticleGenID='%s'", $articleId);
			$dbConn->query($sql);
			
			$sql = sprintf("DELETE FROM articleflash WHERE ArticleGenID='%s'", $articleId);
			$dbConn->query($sql);
			
			$sql = sprintf("SELECT VideoContentID FROM articlevideo WHERE ArticleGenID='%s'", $articleId);
			$videoIDResult = $dbConn->query($sql);
			if ($videoIDResult && $videoIDResult->num_rows > 0)
				for (; ($row = $videoIDResult->fetch_array()) != FALSE; )
					@unlink("../../flashVideo/" . $row['VideoContentID'] . ".flv");
					
			//delete article group
			$sql = sprintf("DELETE FROM grouparticle WHERE ArticleGenID='%s'", $articleId);
			$dbConn->query($sql);
		}
	}
	
	//get tab name.
	$sql = "SELECT CategoryName FROM category WHERE CatGenID='$categoryId'";
	$result = $dbConn->query($sql);
	$tabName = NULL;
	
	if ($result && $result->num_rows > 0)
	{
		$row = $result->fetch_array();
		$tabName = $row['CategoryName'];
	}
	
	
	header("Location: ../forms/room.php?sel=".$categoryId."&user=".$_SESSION['authentication']."&role=".$_SESSION['Role']."&tsk=" . GENERAL_TASK_PUBLISHED_ARTICLES . "&sc=" . $subcategoryId . "&tabName=". urlencode($tabName));
	exit();
?>