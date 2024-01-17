<?php
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");
	require_once("../includes/user_checker.php");
	require_once("../includes/logNotifier.php");
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	if ($_SESSION['Role'] != USER_ADMIN)
	{
		header("Location: logout.php");
		exit();
	}
	
	$rmsg = "Error: Unable to add new user";
	
	$role = trim($_POST['role']);
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	
	$surname = trim($_POST['surname']);
	$firstname = trim($_POST['firstname']);
	$othername = trim($_POST['othername']);
	
	$error = FALSE;
	
	switch ($role)
	{
	case "admin":
		$role = 1;
	break;
	case "editor":
		$role = 0;
	break;
	default:
		unset($username, $password, $surname); //unset to exit registrator.
	}
	
	if (empty($username) || empty($password) || empty($surname) || empty($firstname) || !isset($username, $password, $surname, $firstname))
	{
		$rmsg = "Please register all details";
		$error = TRUE;
	}
	elseif (strlen($password) < 5 || strlen($password) < 5)
	{
		$rmsg = "username and password fields must have a minimum of 5 characters";
		$error = TRUE;
	}
	else
	{
		$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			//check username and password
			$username = $dbConn->real_escape_string($username);
			$password = $dbConn->real_escape_string($password);
			$surname = $dbConn->real_escape_string($surname);
			$fname = $dbConn->real_escape_string($firstname);
			$oname = $dbConn->real_escape_string($othername);
			//script to create new user for the cms.
			//generate photo name for this.
			$markerLength = 32; //number of letters each photo will carry on its name.
					
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
		
				$sql = sprintf("SELECT * FROM cmsusers WHERE UserGenID='%s'", $marker);
				$result = $dbConn->query($sql);
			
				if ($result && $result->num_rows == 0)
				{
					$markedSuccess = true;
					break;
				}
			}
		
			if (markedSuccess)
			{
				//check if another user has username.
				$sql = sprintf("SELECT * FROM cmsusers WHERE Username='%s' AND Password='%s'", $username, $password);
				$result = $dbConn->query($sql);
				if ($result && $result->num_rows > 0)
				{
					$rmsg = urlencode("Another user exists with that username. Please use something else.");
					$error = TRUE;
				}
				else
				{
					//register the user.
					$sql = sprintf("INSERT INTO cmsusers (UserGenID, Username, Password, Role, Surname, Firstname, Othername) VALUES('%s', '%s', AES_ENCRYPT('%s','massive'), %d, '%s', '%s','%s')", $marker, $username, $password, $role, $surname, $firstname, $othername);
					$dbConn->query($sql);
				
					if ($dbConn->affected_rows == 0)
					{
						$rmsg = "Unable to add new user to the system. Contact database administrator.";
						$error = TRUE;
					}
					else
					{
						$logger = new LogNotifier($dbConn);
						
						if ($logger)
							$logger->logNotification(sprintf("New user with role ( %s ) and name ( %s ) was added by %s", ($role ==  1) ? "admin" : "editor", "$surname $firstname $othername", $_SESSION['TheUser'] ), $dbConn->insert_id);
							
							
						$rmsg = "User has been added";
					}
				}
			}
			else
				$error = TRUE;
		}
	}
	
	$savedItems = ($error) ? "&un=$username&fn=$firstname&sn=$surname&on=$othername" : "";
	
	header("Location: ../forms/room.php?user=" . $_SESSION['authentication']. "&role=" . $_SESSION['Role'] . "&t=active_category&tsk=" . ADMIN_TASK_ADD_USER . "&tabName=" . urlencode("Add New User") . "&rmsg=" . urlencode($rmsg) .  $savedItems);
	exit();
?>