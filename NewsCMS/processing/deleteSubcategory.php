<?php
	//script to delete subcategory.
	require_once("../modules/mysqli.php");
	require_once("../includes/user_checker.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");

	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	$sc = $_GET['sc'];
	$tsk = $_GET['tsk'];
	$cat = $_GET['sel'];
	
	if ($_SESSION['Role'] == USER_ADMIN)
	{
		$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			//fetch all article for this subcategory for deletion from articleRelated table
			$sql = sprintf("SELECT ArticleGenID FROM articles WHERE SCGenID='%s'",  $row['SCGenID']);
			$articleResult = $dbConn->query($sql);
				
			if ($articleResult && $articleResult->num_rows > 0)
			{
				//delete article from articleRelated.
				for (; ($aRow = $articleResult->fetch_array()) != FALSE; )
				{
					$sql = sprintf("DELETE FROM articlerelated WHERE ParentArticleGenID='%s'", $aRow['ArticleGenID']);
					$dbConn->query($sql);
					
					//delete flash video files for article.
					$sql = sprintf("SELECT VideoContentID FROM articlevideo WHERE ArticleGenID='%s'", $aRow['ArtilceGenID']);
					$videoResult = $dbConn->query($sql);
					if ($videoResult && $videoResult->num_rows > 0)
					{
						for (; ($vRow == $videoResult->fetch_array()) != FALSE; )
						{
							$dbConn->query( sprintf("DELETE FROM articlevideo WHERE VideoContentID='%s'", $vRow['VideoContentID']) );
							//delete file from folder of flash videos.
							@unlink("../../flashVideo/" . $vRow['VideoContentID'] . ".flv");
						}
					}
						
					$sql = sprintf("DELETE FROM articles WHERE ArticleGenID='%s'", $aRow['ArticleGenID']);
					$dbConn->query($sql);
				}
			}
			//get subcategory folder name and category folder name to delete subfolder
			$sql = sprintf("SELECT sc.WebFolderName AS SCFolder, cat.WebFolderName AS CatFolder FROM subcategory AS sc JOIN category AS cat ON sc.CatGenID=cat.CatGenID WHERE sc.SCGenID='%s'", $sc);
			
			$result = $dbConn->query($sql);
							
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$catFolder = $row['CatFolder'];
				$scFolder = $row['SCFolder'];

				if (file_exists("../../$catFolder/$scFolder") && is_dir("../../$catFolder/$scFolder"))
				{
					$folderContent = scandir("../../$catFolder/$scFolder");
					
					foreach ($folderContent as $entity)
					{
						if (file_exists("../../$catFolder/$scFolder/$entity") && strpos($entity, ".") === FALSE) //we check for back referenced inidcators from scandir
						{
							if (!is_dir("../../$catFolder/$scFolder/$entity"))
							{
								@unlink("../../$catFolder/$scFolder/$entity");
							}
						}
					}
									
					@rmdir("../../$catFolder/$scFolder");
				}
			}
			
			//delete subcategory.
			$dbConn->query(sprintf("DELETE FROM subcategory WHERE SCGenID='%s'", $sc));
		}
	}
	
	header("Location: ../forms/room.php?sel=".$cat."&user=".$_SESSION['authentication']."&role=".$_SESSION['Role']."&tsk=" . GENERAL_TASK_SUB_CATEGORY_LISTING . "&tabName=". $_GET['tabName']);
	exit();
?>