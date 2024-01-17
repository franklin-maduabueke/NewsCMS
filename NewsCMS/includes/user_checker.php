<?php
	//check for user authentication registration.
	//@return : true if user is authenticated or false if not.
	function userSessionGood()
	{
		session_start();
		
		if (!isset($_SESSION['authentication']))
		{
			session_unset();
			session_destroy();
			return false;
		}
		else
		{
			/*
			//check user role to agree with this users authentication code.
			$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
			if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
			{
				$sql = sprintf("SELECT UserTID FROM cmsusers WHERE UserGenID='%s' AND Role=%d", $_SESSION['authentication'], $_GET['role']);
				$result = $dbConn->query($sql);
				if ($result && $result->num_rows > 0)
					return TRUE; //authentic user.
			}
			
			return FALSE; //can't authentic user.
			*/
			return TRUE;
		}
	}
?>