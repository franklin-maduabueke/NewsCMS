<?php
	//redirect to read article on a category from the home page.
	$article = $_GET['article'];
	$sc = $_GET['sc'];
	$categoryId = $_GET['sel'];
	
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");

	
	if (isset($categoryId))
	{
		$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			$sql = sprintf("SELECT sc.WebFolderName AS scWebFolderName, cat.WebFolderName AS catWebFolderName FROM subcategory AS sc JOIN category AS cat ON sc.CatGenID=cat.CatGenID WHERE sc.CatGenID='%s' AND sc.SCGenID='%s'", $categoryId, $sc);
			
			$scFolderNameResult = $dbConn->query($sql);
			if ($scFolderNameResult && $scFolderNameResult->num_rows > 0)
			{
				$scFolderNameRow = $scFolderNameResult->fetch_array();
				//check if folder exists and file in it.
				if (file_exists("../".$scFolderNameRow['catWebFolderName'] . "/" . $scFolderNameRow['scWebFolderName']. "/readArticle.php"))
				{
					//echo "Redirecting to = " . "../".$scFolderNameRow['catWebFolderName'] . "/" . $scFolderNameRow['scWebFolderName']. "/readArticle.php";
					header("Location: ../" . $scFolderNameRow['catWebFolderName'] . "/" . $scFolderNameRow['scWebFolderName'] . "/readArticle.php?sel=$categoryId&sc=$sc&article=$article");
					exit();
				}
			}
		}
	}
	
	header("Location: ../index.php");
	exit();
?>