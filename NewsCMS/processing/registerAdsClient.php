<?php
	function  generateID($markerLength, $onlyNum)
	{
		//script to create new user for the cms.
		//generate photo name for this.
		//number of letters each photo will carry on its name.
		if ($markerLength <= 0)
			return FALSE;
		
		$alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_";
		 
		if ($onlyNum)
			$alphabets = "0123456789";
		
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
	
	$company = trim($_POST['companyName']); //required
	$website = trim($_POST['website']);
	$email = trim($_POST['email']); //required
	$surname = trim($_POST['surname']);
	$firstname = trim($_POST['surname']);
	$othername = trim($_POST['othername']);
	
	//cleanup the referer string to place messages from this process
	$referer = $_SERVER['HTTP_REFERER'];
	$msgPos = strpos($referer, "&msg");
	if (!($msgPos === FALSE))
		$referer = substr($referer, 0, $msgPos);
	
	$msg = "Unable to register client";
	
	if (!isset($company, $email) || empty($company) || empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL))
	{	
		header("Location: " . $referer . "&msg=" . urlencode("Please provide valid entry for the required field"));
		exit();
	}
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$dbConn->real_escape_string($company);
		$dbConn->real_escape_string($website);
		$dbConn->real_escape_string($surname);
		$dbConn->real_escape_string($firstname);
		$dbConn->real_escape_string($othername);
		
		$company = ucwords($company);
		$pinSet = FALSE;
		
		//check if a client with this credentials exists
		$sql = sprintf("SELECT * FROM advertclient WHERE Email='%s'", $email);
		$clientResult = $dbConn->query($sql);
		if ($clientResult && $clientResult->num_rows == 0)
		{
			for ($i = 0; $i < 20; $i++)
			{
				$clientMarker = generateID(10, true);
				$clientResult = $dbConn->query(sprintf("SELECT * FROM advertclient WHERE ClientGenID='%s'", $clientMarker));
				if ($clientResult && $clientResult->num_rows == 0)
				{
					$sql = sprintf("INSERT INTO advertclient (ClientGenID, CompanyName, WebSite, Email, Surname, Firstname, Othername) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $clientMarker, $company, (!empty($website)) ? $website : "NULL", $email, (!empty($surname)) ? $surname : "NULL", (!empty($firstname)) ? $firstname : "NULL", (!empty($othername)) ? $othername : "NULL");
					$dbConn->query($sql);
					if ($dbConn->sqlstate == "00000")
					{
						$pinSet = TRUE;
						$msg = "Registration was successfull";
						break;
					}
				}
			}
		}
		else
			$msg = "Client with that information already exists";
	}
	
	$suffix = ($pinSet) ? "&pin=$clientMarker" : "";
	header("Location: " . $referer . "&msg=" . urlencode($msg) . $suffix);
	exit();
?>