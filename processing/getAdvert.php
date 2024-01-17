<?php
	require_once("config/db.php");
	require_once($prefix . "modules/mysqli.php");
	require_once($prefix . "includes/commons.php");
	require_once($prefix . "modules/lookConfigure.php");
	require_once($prefix . "modules/adsLauncher.php");
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		try
		{
			$adLauncher = new AdsLauncher($dbConn);
			switch ($_GET['at'])
			{
			case AD_IMAGE:
				$advert = $adLauncher->getImageAdvert($_GET['dim']);
				if ($advert)
				{
					$imageMime = array("jpg"=>"image/jpeg", "jpg"=>"image/pjpg", "png"=>"image/png", "bmp"=>"image/bmp", "gif"=>"image/gif");
					
					header('Content-type: ' . $imageMime[$advert['FileFormat']]);
					$advert['image_data'];
					flush();
					$dbConn->close();
					exit();
				}
			break;
			case AD_FLASH:
				$advert = $adLauncher->getFlashAdvert($_GET['dim']);
				if ($advert)
				{
					header('Content-type: application/x-shockwave-flash');
					echo $advert['flash_data'];
					flush();
					$dbConn->close();
					exit();
				}
			break;
			}
		}
		catch (Exception $ex)
		{
			exit();
		}
	}
?>