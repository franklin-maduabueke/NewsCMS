<?php
	//script to return the groups of a section to scSetTemplateScript.js
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$sql = sprintf("SELECT GroupName, GroupGenID FROM subcategorygroup WHERE SCGenID='%s'", $_POST['subcatGenID']);
		$result = $dbConn->query($sql);
		if ($result && $result->num_rows > 0)
		{
			$groupXML = '<?xml version="1.0" encoding="iso-8859-1"?>';
			$groupXML .= '<Groups xmlns="http://www.w3schools.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.w3schools.com groups.xsd">'; //this xsd has not be specified yet.
			for (; ($gRow = $result->fetch_array()) != FALSE; )
			{
				$groupXML .= "<group>";
				$groupXML .= "<name>". $gRow['GroupName'] ."</name>";
				$groupXML .= "<groupGenId>". $gRow['GroupGenID'] ."</groupGenId>";
				$groupXML .= "</group>";
			}
			$groupXML .= "</Groups>";
			echo $groupXML;
			flush();
			exit();
		}
		else
		{
			echo "0";
			flush();
			exit();
		}
	}
?>