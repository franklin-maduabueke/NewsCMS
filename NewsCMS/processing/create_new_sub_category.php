<?php
	require_once("../modules/mysqli.php");
	require_once("../includes/user_checker.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");
	require_once("../includes/logNotifier.php");
	
	//function to clean up subcategory name to be used as folder name for pages of that subcategory.
	function  createFolderName($catName)
	{
		$catName = trim($catName);
		$catName = strtolower($catName);
		$catName = str_replace("&", "and", $catName);
		$catName = str_replace(" ", "_", $catName);
		$catName = str_replace("\t", "_", $catName);
		$catName = str_replace("-", "_", $catName);

		return $catName;
	}
	
	//check user session.
	if (!userSessionGood())
	{
		header("Location: ../processing/logout.php");
		exit();
	}
	
	$categoryId = $_POST['categoryId'];
	$subcatName = trim($_POST['subcategoryName']);
	$tabName = NULL;
	
	$subcatFolderName = createFolderName($subcatName);
	$subcatName = ucwords(strtolower($subcatName));
	
	//see if the category name was set.
	$dbConn =  new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME) && !empty($subcatName))
	{
		$subcatName = $dbConn->real_escape_string($subcatName);
		
		//script to create new user for the cms.
		//generate photo name for this.
		$markerLength = 8; //number of letters each photo will carry on its name.
					
		$alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_";
		$rangeLength = strlen($alphabets);
		$marker = '';
			
		$markedSuccess = false;
		for ($j = 0; $j < 20; $j++) //try generating a usable name for the user or quit on 20 trys.
		{
			$marker = '';
			for ($count = 0; $count < $markerLength; $count++)
			{
				$rand = rand(0, $rangeLength - 1);
				$marker .= substr($alphabets, $rand, 1);
			}
		
			$sql = sprintf("SELECT * FROM subcategory WHERE SCGenID='%s'", $marker);
			$result = $dbConn->query($sql);
			
			if ($result && $result->num_rows == 0)
			{
				$markedSuccess = true;
				break;
			}
		}
		
		if ($markedSuccess)
		{
			$sql = "SELECT * FROM subcategory WHERE SubCatName='$subcatName' AND CatGenID='$categoryId'";
			$result = $dbConn->query($sql);
			
			if ($result && $result->num_rows == 0)
			{
				$sql = sprintf("INSERT INTO subcategory (SCGenID, SubCatName, CatGenID) VALUES('%s', '%s', '%s')", $marker, $subcatName, $categoryId);
				$dbConn->query($sql);
				if ($dbConn->affected_rows > 0)
				{
					$logger = new LogNotifier($dbConn);
					if ($logger)
						$logger->logNotification("New subcategory was created by " . $_SESSION['TheUser'], $_SESSION['xx']);
					
					$sql = sprintf("SELECT CategoryName, WebFolderName FROM category WHERE CatGenID='%s'", $categoryId);
					$catNameResult = $dbConn->query($sql);
					$catNameRow = $catNameResult->fetch_array();
					$tabName = $catNameRow['CategoryName'];
					
					if (@mkdir("../../".$catNameRow['WebFolderName']."/$subcatFolderName") == FALSE)
					{
						if ($logger)
							$logger->logNotification("Unable to create folder for subcategory [ $subcatFolderName ] ", $_SESSION['xx']);
							
						$msg = "Error: Unable to create folder for subcategory [ $subcatFolderName ]";
					}
					else
					{
						//insert folder name.
						$sql = sprintf("UPDATE subcategory SET WebFolderName='%s' WHERE SCGenID='%s'", $subcatFolderName, $marker);
						$dbConn->query($sql);
						$msg = "Subcategory has beeen created";
					}
					
					header("Location: ../forms/room.php?sel=" . $categoryId . "&user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . GENERAL_TASK_SUB_CATEGORY_LISTING . "&tabName=".$tabName . "&msg=" . urlencode($msg));
					exit();
				}
			}
		}
	}
	
	header("Location: ../forms/room.php?sel=" . $categoryId . "&user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . GENERAL_TASK_SUB_CATEGORY_LISTING . "&tabName=".$tabName);
	exit();
?>