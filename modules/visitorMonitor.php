<?php
	//insert this visitor to the database if new visitor
	function  visitorMonitor(MySQLi $dbConn)
	{
		$visitorResult = $dbConn->query(sprintf("SELECT VisitDate FROM visitormonitor WHERE IP_Address='%s'", $_SERVER['REMOTE_ADDR']));
		if ($visitorResult && $visitorResult->num_rows == 0)
		{
			$sql = sprintf("INSERT INTO visitormonitor (IP_Address, VisitDate) VALUES('%s', '%s')", $_SERVER['REMOTE_ADDR'], date("Y-m-d,"));
			$dbConn->query($sql);
		}
		else
		{
			//record the time the visitor came in for the day...
			if ($visitorResult)
			{
				$visitorRow = $visitorResult->fetch_array();
			
			/*
			//check if visitor is coming from a different site domain.
			if ( strpos($_SERVER['HTTP_REFERER'], $_SERVER['DOCUMENT_ROOT']) === FALSE )
			{
				//this is a visitor from another site...set as hit by applying date if the date
				//is not today.
				
			}
			*/
			
			//check if this visitor has been here today.
				$ip = $_SERVER['REMOTE_ADDR'];
				$today = date("Y-m-d,");
			
				$sql = "SELECT * FROM visitormonitor WHERE IP_Address='$ip' AND VisitDate LIKE '%$today%'";
				$queryResult = $dbConn->query($sql);
			
				if ($queryResult && $queryResult->num_rows == 0)
				{
					//first visit for today.
					$sql = sprintf("UPDATE visitormonitor SET VisitDate=CONCAT(VisitDate, '%s') WHERE IP_Address='$ip'", $today);	
					$dbConn->query($sql);
				}
			}
		}
	}
?>