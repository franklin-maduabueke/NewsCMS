<?php
	//script to redirect the click of a category link to the actual folder of that category.
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");
	
	$categoryId = $_GET['sel'];
	$scGenId = $_GET['sc'];
	
	if (isset($categoryId))
	{
		$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			if (isset($scGenId) && !empty($scGenId))
			{
				$sql = sprintf("SELECT sc.WebFolderName AS scFolderName, cat.WebFolderName AS catFolderName FROM subcategory AS sc JOIN category AS cat ON sc.CatGenID=cat.CatGenID WHERE sc.SCGenID='%s'", $scGenId);
				
				$scFolderNameResult = $dbConn->query($sql);
				
				if ($scFolderNameResult && $scFolderNameResult->num_rows > 0)
				{
					$scFolderNameRow = $scFolderNameResult->fetch_array();
					
					if (!empty($scFolderNameRow['scFolderName']) && !empty($scFolderNameRow['catFolderName']))
						if (file_exists("../". $scFolderNameRow['catFolderName'] . "/" . $scFolderNameRow['scFolderName'] ."/index.php"))
						{
							header("Location: ../" . $scFolderNameRow['catFolderName'] . "/" . $scFolderNameRow['scFolderName'] ."/index.php?sel=" . $categoryId . "&sc=" . $scGenId);
							exit();
						}
				}
			}
			else
			{
				$sql = sprintf("SELECT WebFolderName FROM category WHERE CatGenID='%s'", $categoryId);
			
				$catFolderNameResult = $dbConn->query($sql);
				if ($catFolderNameResult && $catFolderNameResult->num_rows > 0)
				{
					$catFolderNameRow = $catFolderNameResult->fetch_array();
					//check if folder exists and file in it.
					if (file_exists("../".$catFolderNameRow['WebFolderName'] . "/index.php"))
					{
						header("Location: ../" .$catFolderNameRow['WebFolderName']);
						exit();
					}
				}
			}
		}
	}
	

	header("Location: ../index.php");
		
	exit();
?>