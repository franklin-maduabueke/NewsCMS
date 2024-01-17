<?php
	//function to clean up category name to be used as folder name for pages of that category.
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
	
	require_once("../modules/mysqli.php");
	require_once("../includes/user_checker.php");
	require_once("../config/db.php");
	require_once("../includes/logNotifier.php");
	require_once("../includes/commons.php");
	
	//check user session.
	if (!userSessionGood())
	{
		header("Location: ../processing/logout.php");
		exit();
	}
	
	$catName = trim($_POST['categoryName']);
	$tabName = $catName;
	$catFolderName = createFolderName($catName);
	//remove path specifiers.
	$catName = str_replace("/","",$catName);
	$catName = str_replace("\\","", $catName);
	$catName = ucwords(strtolower($catName));
	
	$msg = "Error: Unable to create category [ $catName ]";
	
	//see if the category name was set.
	$dbConn =  new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	$reservedFolder = array("config"=>"resereved name config exists", "flashVideo"=>"reserved name flashVideo exists", "images"=>"reserved name images exists", "includes"=>"reserved name includes exists", "modules"=>"reserved name modules exists", "newscms"=>"reserved name newscms exists", "processing"=>"reserved name processing exits", "scripts"=>"reserved name scripts exits", "stylesheet"=>"reserved name stylesheet exits", "templates"=>"reserved name templates exits", "temps"=>"reserved name temps exists", "xmls"=>"reserved name xmls exits");
	
	if (!empty($catName) && !array_key_exists(strtolower($catName), $reservedFolder) && $dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$catName = $dbConn->real_escape_string($catName);
		
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
		
			$sql = sprintf("SELECT * FROM category WHERE CatGenID='%s'", $marker);
			$result = $dbConn->query($sql);
			
			if ($result && $result->num_rows == 0)
			{
				$markedSuccess = true;
				break;
			}
		}
		
		if ($markedSuccess)
		{
			$sql = "SELECT * FROM category WHERE CategoryName='$catName'";
			$result = $dbConn->query($sql);
			
			if ($result && $result->num_rows == 0)
			{
				$maxCountResult = $dbConn->query("SELECT MAX(WebCatTabIndex) FROM category");
				$maxCountRow = $maxCountResult->fetch_array();
				$maxCount = $maxCountRow['WebCatTabIndex'];
				$maxCount++;
				
				$sql = sprintf("INSERT INTO category (CatGenID, CategoryName, WebFolderName, WebCatTabIndex) VALUES('%s', '%s', '%s', '%s')", $marker, $catName, $catFolderName, $maxCount);
				$dbConn->query($sql);
				if ($dbConn->affected_rows > 0)
				{
					$msg = "Category has beeen created";
					
					$logger = new LogNotifier($dbConn);
					if ($logger)
						$logger->logNotification("New category was created by " . $_SESSION['TheUser'], $_SESSION['xx']);
					
					if (@mkdir("../../$catFolderName") == FALSE)
					{
						if ($logger)
							$logger->logNotification("Unable to create folder for category [ $tabName ] ", $_SESSION['xx']);
							
						$msg = "Error: Unable to create folder for category [ $tabName ]";
					}
					
					
					header("Location: ../forms/room.php?sel=". $marker . "&user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . GENERAL_TASK_CREATE_SUB_CATEGORY ."&tabName=".$tabName . "&msg=" . urlencode($msg));
					exit();
				}
			}
		}
	}
	
	header("Location: ../forms/room.php?user=" . $_SESSION['authentication'] . "&role=" . $_SESSION['Role'] . "&tsk=" . GENERAL_TASK_SELECT_CATEGORY . "&msg=" . urlencode($msg));
	exit();
?>