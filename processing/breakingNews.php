<?php
	//script to poll for breaking news from database.
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		//delete all expired breaking news.
		$sql = sprintf("DELETE FROM BreakingNews WHERE ExpireDate<%d", time());
		$dbConn->query($sql);
		
		$sql = sprintf("SELECT bn.ArticleGenID, art.Heading, art.SCGenID, sc.CatGenID FROM breakingnews AS bn JOIN articles AS art ON bn.ArticleGenID=art.ArticleGenID JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE ExpireDate>=%d", time());
		
		$result = $dbConn->query($sql);
		
		if ($result && $result->num_rows > 0)
		{
			$newsCollection = '<BreakingNews xmlns="http://www.w3schools.com"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://www.w3schools.com template.xsd">';
			
			for (; ($row = $result->fetch_array()) != FALSE;)
			{
				$newsCollection .= "<News>";
				$newsCollection .= "<heading>" . substr($row['Heading'], 0, 122) . "</heading>";
				$newsCollection .= "<articlegenid>" . $row['ArticleGenID'] . "</articlegenid>";
				$newsCollection .= "<scgenid>" . $row['SCGenID'] . "</scgenid>";
				$newsCollection .= "<catgenid>" . $row['CatGenID'] . "</catgenid>";
				$newsCollection .= "</News>";
			}
			
			$newsCollection .="</BreakingNews>";
			echo $newsCollection;
		}
		else
			echo "0";
	}
	flush();
	exit();
?>