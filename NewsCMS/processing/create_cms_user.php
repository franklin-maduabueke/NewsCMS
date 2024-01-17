<?php
	//called to setup the cms.
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/user_checker.php");
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$surname = $_POST['surname'];
	$fname = $_POST['firstname'];
	$oname = $_POST['othername'];
	$role = $_POST['role'];
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		//check username and password
		$username = $dbConn->real_escape_string($username);
		$password = $dbConn->real_escape_string($password);
		$surname = $dbConn->real_escape_string($surname);
		$fname = $dbConn->real_escape_string($fname);
		$oname = $dbConn->real_escape_string($oname);
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
				$msg = urlencode("Another user exists with that username. Please use something else.");
				header("Location: ../setup/register.php?msg=$msg");
				exit();
			}
			else
			{
				//register the user.
				$sql = sprintf("INSERT INTO cmsusers VALUES('%s', '%s', '%s', %d, '%s', '%s','%s')", $marker, $username, $password, 1, $surname, $fname, $oname);
				$dbConn->query($sql);
				
				if ($dbConn->affected_rows == 0)
				{
					$msg = urlencode("Unable to setup the system. Contact database administrator.");
					header("Location: ../setup/register.php");
					exit();
				}
				else
				{
					echo "Registed in DB" . $dbConn->error;
					$logger = new LogNotifier($dbConn);
					if ($logger)
						$logger->logNotification("The system was setup by $surname $fname $oname", $dbConn->insert_id);
						
					header("Location: ../index.php");
					exit();
				}
			}
		}
	}
?>