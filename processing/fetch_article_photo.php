<?php
	//script to get the photo from database and pass it to the browser image element
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");
	require_once("../config/db.php");
	
	$photoId = $_GET['photoId'];
	
	if (isset($photoId) && !empty($photoId))
	{
		$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			$sql = sprintf("SELECT Photo, ImageFormat FROM articlephoto WHERE ArtPhotoID='%s'", $photoId);
			$photoResult = $dbConn->query($sql);
			if ($photoResult && $photoResult->num_rows > 0)
			{
				$photoRow = $photoResult->fetch_array();
				
				$imageHnd = imagecreatefromstring($photoRow['Photo']);
				if ($imageHnd)
				{
					switch ($photoRow['ImageFormat'])
					{
					case "jpg":
						// Set the content type header - in this case image/jpeg
						header('Content-type: image/jpeg');
						imagejpeg($imageHnd);
					break;
					case "png":
						// Set the content type header - in this case image/jpeg
						header('Content-type: image/png');
						imagepng($imageHnd);
					break;
					case "bmp":
						// Set the content type header - in this case image/jpeg
						header('Content-type: image/wbmp');
						imagewbmp($imageHnd);
					break;
					case "gif":
						// Set the content type header - in this case image/jpeg
						header('Content-type: image/gif');
						imagegif($imageHnd);
					break;
					}
				}
			}
		}
	}
	flush();
	
	if ($imageHnd)
		imagedestroy($imageHnd);
	exit();
?>