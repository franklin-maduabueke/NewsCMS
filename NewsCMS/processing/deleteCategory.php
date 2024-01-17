<?php
	//script to delete a directory.
	function  deleteDirectory($pathName)
	{
		if (file_exists($pathName) && is_dir($pathName))
		{
			$dirContent = scandir($pathName);
			for ($i = 0; $i < count($dirContent); $i++)
			{
				if (is_file($pathName . "/" . $dirContent[$i] ))
				{
					
				}
			}
		}
	}
?>
<?php
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/user_checker.php");
	require_once("../includes/commons.php");
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	$categoryGenId = $_GET['sel'];
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		//delete articles.
		//delete subcategory
		//delete category.
		$sql = sprintf("SELECT WebFolderName FROM category WHERE CatGenID='%s'", $categoryGenId);
		$catFolderResult = $dbConn->query($sql);
		$catFolderRow = $catFolderResult->fetch_array();
		
		$sql = sprintf("SELECT SCGenID FROM subcategory WHERE CatGenID='%s'", $categoryGenId);
		$result = $dbConn->query($sql);
		
		if ($result && $result->num_rows > 0)
		{
			for (; ($row = $result->fetch_array()) != FALSE; )
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
								$dbConn->query(sprintf("DELETE FROM articlevideo WHERE VideoContentID='%s'", $vRow['VideoContentID']));
								//delete file from folder of flash videos.
								@unlink("../../flashVideo/" . $vRow['VideoContentID'] . ".flv");
							}
						}
						
						$sql = sprintf("DELETE FROM articles WHERE ArticleGenID='%s'", $aRow['ArticleGenID']);
						$dbConn->query($sql);
					}
				}
				
				//delete Subcategory
				$sql = sprintf("DELETE FROM subcategory WHERE SCGenID='%s'", $row['SCGenID']);
				$dbConn->query($sql);
			}
		}
		
		
		//delete the folder for the category.
		//scan directory and remove containing files
		$directorContent = scandir("../../" . $catFolderRow['WebFolderName']);
		if (count($directorContent) > 0)
		{
			//delete the contents in the folder.
			foreach ($directorContent as $fileName)
			{
				if (is_dir("../../".$catFolderRow['WebFolderName'] . "/$fileName") && strpos($fileName, ".") === FALSE) //we check for back referenced inidcators from scandir
				{
					//scan the directory and delete its files
					$subFolder = scandir("../../".$catFolderRow['WebFolderName'] . "/$fileName");
					
					foreach($subFolder as $fileInSub)
					{
						if (!is_dir("../../". $catFolderRow['WebFolderName'] . "/$fileName/$fileInSub") && strpos($fileName, ".") === FALSE)
						{
							@unlink("../../". $catFolderRow['WebFolderName'] . "/$fileName/$fileInSub");
						}
					}
					
					@rmdir("../../".$catFolderRow['WebFolderName'] . "/$fileName");
				}
				else
				{	
					if (is_file("../../" . $catFolderRow['WebFolderName'] . "/$fileName") && strpos($fileName, ".") === FALSE)
						@unlink("../../" . $catFolderRow['WebFolderName'] . "/$fileName");
				}
			}
		}

		//delete category folder.
		if (is_dir("../../" . $catFolderRow['WebFolderName']) && strpos($catFolderRow['WebFolderName'], ".") === FALSE)
		{
			@rmdir("../../" . $catFolderRow['WebFolderName']);
			
			//delete the category.
			$sql = sprintf("DELETE FROM category WHERE CatGenID='%s'", $categoryGenId);
			$dbConn->query($sql);
		}
	}
	
	//back to select category
	header("Location: ../forms/room.php?user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . GENERAL_TASK_SELECT_CATEGORY);
	exit();
?>