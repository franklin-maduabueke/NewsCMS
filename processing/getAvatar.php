<?php
	//script to get the members avatar from database and pass it to the browser image element
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");
	require_once("../config/db.php");
	
	$member = $_GET['m'];
	
	if (isset($member) && !empty($member))
	{
		$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
		if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
		{
			$sql = sprintf("SELECT Avatar, ImageFormat FROM members WHERE MemberGenID='%s'", $member);
			$photoResult = $dbConn->query($sql);
			if ($photoResult && $photoResult->num_rows > 0)
			{
				$photoRow = $photoResult->fetch_array();
				
				$imageHnd = imagecreatefromstring($photoRow['Avatar']);
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
				else
				{
					$seq = $_GET['i'];
						
					if (($seq % 2) == 0)
						echo file_get_contents("../images/defaultAvatar1.jpg");
					else
						echo file_get_contents("../images/defaultAvatar2.jpg");
				}
			}
			else
			{
				$seq = $_GET['i'];
						
				if (($seq % 2) == 0)
					echo file_get_contents("../images/defaultAvatar1.jpg");
				else
					echo file_get_contents("../images/defaultAvatar2.jpg");
			}
		}
	}
	flush();
	
	if ($imageHnd)
		imagedestroy($imageHnd);
	exit();
?>