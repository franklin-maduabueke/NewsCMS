<?php
	define("FLASH", 1);
	define("IMAGE", 2);
	
	define("IMAGE_SQUARE", "1");
	define("IMAGE_BANNER_HORIZONTAL", "2");
	define("IMAGE_BANNER_VERTICAL", "3");
	
	define("FLASH_SQUARE", "1");
	define("FLASH_BANNER_HORIZONTAL", "2");
	define("FLASH_BANNER_VERTICAL", "3");
	
	
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
	
	//script to upload adverts.
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");
	require_once("../includes/user_checker.php");

	if (!userSessionGood())
	{
		header("Location: ../processing/logout.php");
		exit();
	}
	
	$msg = "Last advert upload was not successful";
	$day = $_POST['eday'];
	$month = $_POST['emonth'];
	$year = $_POST['eyear'];
	
	$expireDate = strtotime("$year-$month-$day");

	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	$flashUploadError = array();
	$imageUploadError = array();
	
	$ini = parse_ini_file("../config/app.ini", TRUE);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		//check if client exists
		switch ($_POST['advertType'])
		{
		case "image":
			$sql = sprintf("SELECT * FROM advertclient WHERE ClientGenID='%s'", trim($_POST['imageAdvertClientPIN']));
		break;
		case "flash":
			$sql = sprintf("SELECT * FROM advertclient WHERE ClientGenID='%s'", trim($_POST['flashAdvertClientPIN']));
		break;
		}
		
		$clientResult = $dbConn->query($sql);
			
		if ($clientResult && $clientResult->num_rows > 0)
		{
			switch ($_POST['advertType'])
			{
			case "image":
				//processing image fields.
				$goldAdvert = $_POST['goldAdvert'];
				if (isset($goldAdvert) && $goldAdvert == "on")
					$goldAdvert = 1;
				else
					$goldAdvert = 0;
					
				if (isset($_FILES['imageUpload']))
				{
					//check file type sent for upload.
					$imageMime = array("image/jpeg"=>"jpg", "image/pjpg"=>"jpg", "image/png"=>"png", "image/bmp"=>"bmp", "image/gif"=>"gif");
					$imageType = $_FILES['imageUpload']['type'];
								
					if (array_key_exists($imageType, $imageMime))
					{
						//add the photo to database.
						//generate marker
						for ($j = 0; $j < 20; $j++) //try generating a usable id for the advert or quit on 20 trys.
						{
							$adsMarker = generateID(10, FALSE);
							//test the marker.
							if ($adsMarker != FALSE)
							{
								$sql = sprintf("SELECT * FROM adverts WHERE AdsGenID='%s'", $adsMarker);
								$result = $dbConn->query($sql);
						
								if ($result && $result->num_rows == 0)
								{
									//save photo file.
									
									$imageString = file_get_contents($_FILES['imageUpload']['tmp_name']);
									$unescapedImageString = $imageString;
									
									if ($imageString)
									{
										$imageString = $dbConn->real_escape_string($imageString);
								
										//check image type sent for upload.
										//add the photo to database.
										$advertType = "";
										switch($_POST['imageAdvertType'])
										{
										case "1":
											$advertType = IMAGE_SQUARE;
										break;
										case "2":
											$advertType = IMAGE_BANNER_HORIZONTAL;
										break;
										case "3":
											$advertType = IMAGE_BANNER_VERTICAL;
										break;	
										}

										for ($j = 0; $j < 20; $j++) //try generating a usable id for the advert or quit on 20 trys.
										{
											$imageAdsMarker = generateID(20, FALSE);
										
											$adsGenResult = $dbConn->query(sprintf("SELECT * FROM imageads WHERE ImageAdGenID='%s'", $imageAdsMarker));
										
											
											if ($adsGenResult && $adsGenResult->num_rows == 0)
											{
												//add advert resource
												$sql = sprintf('INSERT INTO imageads (ImageAdGenID, ImageAdLink, ImageAdType, AdImage, FileFormat, AdsGenID) VALUES("%s", "%s", "%s", "%s", "%s", "%s")', $imageAdsMarker, $_POST['imageAdvertLink'], (int)$advertType, $imageString, $imageMime[$imageType], $adsMarker);
												
												$dbConn->query($sql);
												
												if ($dbConn->sqlstate == "00000")
												{
													//********************************************teporary fix to add adverts to temps folder
														if ($ini)
														{
															$site_path = $ini['PATHS']['site_path'];
															
															@file_put_contents($site_path . "temps/" . $adsMarker. "" . $imageAdsMarker . "." .$imageMime[$imageType], $unescapedImageString);
														}
													//********************************************remove later
													
													//add to adverts.
													$sql = sprintf("INSERT INTO adverts (ClientGenID, Duration, AdvertType, AdsGenID, GoldAdvert) VALUES('%s', %d, '%s', '%s', %d)", trim($_POST['imageAdvertClientPIN']), $expireDate, IMAGE, $adsMarker, $goldAdvert);
													$dbConn->query($sql);
													
													$msg = "Last advert uploaded succesfully";
													$success = TRUE;
													break;
												}
												else
												{
													$imageUploadError [$_FILES['imageUpload']['name']] = "Database commit error";
													$success = TRUE;
													break;
												}
											}
										}
									}
									else
									{
										$imageUploadError [$_FILES['imageUpload']['name']] = "Flash is not an acceptable format. Use .swf files";
										break;
									}
								}
							}
							if ($success)
							 break;
						}
					}
				}	
			break;
			case "flash":
				//processing image fields.
				if (isset($_FILES['flashUpload']))
				{
					//check file type sent for upload.
					$flashMime = array("application/x-shockwave-flash"=>"swf");
					$flashType = $_FILES['flashUpload']['type'];
								
					if (array_key_exists($flashType, $flashMime))
					{
						//add the photo to database.
						//generate marker
						for ($j = 0; $j < 20; $j++) //try generating a usable id for the advert or quit on 20 trys.
						{
							$adsMarker = generateID(10, FALSE);
							//test the marker.
							if ($adsMarker != FALSE)
							{
								$sql = sprintf("SELECT * FROM adverts WHERE AdsGenID='%s'", $adsMarker);
								$result = $dbConn->query($sql);
						
								if ($result && $result->num_rows == 0)
								{
									//save photo file.
									$flashString = file_get_contents($_FILES['flashUpload']['tmp_name']);
									$unescapedFlashString = $flashString;
									
									if ($flashString)
									{
										$flashString = $dbConn->real_escape_string($flashString);
								
										//check image type sent for upload.
										//add the photo to database.
										$advertType = "";
										
										switch($_POST['flashAdvertType'])
										{
										case "1":
											$advertType = FLASH_SQUARE;
										break;
										case "2":
											$advertType = FLASH_BANNER_HORIZONTAL;
										break;
										case "3":
											$advertType = FLASH_BANNER_VERTICAL;
										break;	
										}
										
										for ($j = 0; $j < 20; $j++) //try generating a usable id for the advert or quit on 20 trys.
										{
											$flashAdsMarker = generateID(20, FALSE);
										
											$adsGenResult = $dbConn->query(sprintf("SELECT * FROM flashads WHERE FlashAdGenID='%s'", $flashAdsMarker));
										
											if ($adsGenResult && $adsGenResult->num_rows == 0)
											{
												//add advert resource
												$sql = sprintf('INSERT INTO flashads (FlashAdGenID, FlashAdType, Flash, AdsGenID) VALUES("%s", "%s", "%s", "%s")', $flashAdsMarker, (int)$advertType, $flashString, $adsMarker);
												
												$dbConn->query($sql);
												
												if ($dbConn->sqlstate == "00000")
												{
													//********************************************teporary fix to add adverts to temps folder
														if ($ini)
														{
															$site_path = $ini['PATHS']['site_path'];
															
															@file_put_contents($site_path . "temps/" .$adsMarker. "" . $flashAdsMarker . ".swf", $unescapedFlashString);
														}
													//********************************************remove later
													
													//add to adverts.
													$sql = sprintf("INSERT INTO adverts (ClientGenID, Duration, AdvertType, AdsGenID) VALUES('%s', %d, '%s', '%s')", trim($_POST['flashAdvertClientPIN']), $expireDate, FLASH, $adsMarker);
													
													$dbConn->query($sql);
													$msg = "Last advert uploaded succesfully";
													$success = TRUE;
													break;
												}
												else
												{
													$flashUploadError [$_FILES['flashUpload']['name']] = "Database commit error";
													$success = TRUE;
													break;
												}
											}
										}
									}
									else
									{
										$flashUploadError [$_FILES['flashUpload']['name']] = "Flash is not an acceptable format. Use .swf files";
										break;
									}
								}
							}
							if ($success)
							 break;
						}
					}
				}
			break;
			}
		}
	}
	
	header("Location: ../forms/room.php?user=".$_SESSION['authentication']."&role=".$_SESSION['Role']."&tsk=" . ADMIN_TASK_ADVERTS . "&sa=" . ADMIN_TASK_ADVERTS_SUB_UPLOAD . "&msg=".urlencode($msg));
	exit();
?>