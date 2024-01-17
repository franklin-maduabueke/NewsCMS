<?php
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");
	require_once("../includes/user_checker.php");
	
	function  generateID($markerLength)
	{
		//script to create new user for the cms.
		//generate photo name for this.
		//number of letters each photo will carry on its name.
		if ($markerLength <= 0)
			return FALSE;
		
		$alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_";
		$rangeLength = strlen($alphabets);
		$marker = '';
		
		$marker = '';
		for ($count = 0; $count < $markerLength; $count++)
		{
			$rand = rand(0, $rangeLength - 1);
			$marker .= substr($alphabets, $rand, 1);
		}
		return $marker;
	}
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	$msg = "Unable to create subcategory group";
	
	$groupName = $_POST['subcategoryGroupName'];
	$scGenId = $_POST['subcategoryId'];
	$catGenId = $_POST['categoryId'];
	$tabName = $_POST['tabName'];
	$groupName = ucwords(strtolower($groupName));
	
	if (isset($groupName) && !empty($groupName) && isset($scGenId))
	{
		$dbConn =  new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			for ($i = 0; $i <= 20; $i++) //try 20 times
			{
				//generate marker
				$marker = generateID(10);
				$sql = sprintf("SELECT * FROM subcategorygroup WHERE GroupName='%s'", $groupName);				
				$result = $dbConn->query($sql);
				if ($result && $result->num_rows == 0)
				{
					$isMarkedResult = $dbConn->query(sprintf("SELECT * FROM subcategorygroup WHERE GroupGenID='%s'", $marker));
					
					if ($isMarkedResult && $isMarkedResult->num_rows == 0)
					{
						$dbConn->query(sprintf("INSERT INTO subcategorygroup (GroupGenID, SCGenID, GroupName) VALUES('%s', '%s', '%s')", $marker, $scGenId, $groupName));
						
						if ($dbConn->sqlstate == "00000")
						{
							header("Location: ../forms/room.php?user=".$_SESSION['authentication']."&role=".$_SESSION['Role']."&sel=".$catGenId."&sc=".$scGenId."&tsk=" . GENERAL_TASK_VIEW_SUBCATEGORY_GROUP ."&tabName=".urlencode($tabName));
							exit();
						}
						else
							break;
					}
				}
				else
				{
					$msg = "Subcategory group with that name already exists";
					break;
				}
			}
		}
	}
	
	header("Location: ../forms/room.php?user=".$_SESSION['authentication']."&role=".$_SESSION['Role']."&sel=".$catGenId."&sc=".$scGenId."&tsk=". ADMIN_TASK_CREATE_SUBCATEGORY_GROUP ."&tabName=".urlencode($tabName)."&msg=".urlencode($msg));
	exit();
?>