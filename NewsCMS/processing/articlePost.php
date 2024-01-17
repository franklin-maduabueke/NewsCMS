<?php
	function  cleanUp($text)
	{
		$text = str_replace("<i>", "<span style='font-style:italic'>",$text);
		$text = str_replace("</i>", "</span>",$text);
		$text = str_replace("</div>", "",$text);
		$text = str_replace("\"", "&quot;",$text);
		$text = str_replace("\'", "&lsquo;",$text);
		$text = str_replace("`", "&lsquo;",$text);
		$text = str_replace("&nbsp;", " ",$text);
		$text = str_replace("\t;", " ",$text);
		$text = str_replace("</p>", "",$text);
		
		//now check for paragraph tags that have no text behind them.
		for ($i = 0; $i < strlen($text); $i++)
		{
			$fParaPos = strpos($text, "<p>");
			if (!($fParaPos === false))
			{
				$checkBehind = trim(substr($text, 0, $fParaPos + 4));
				if (strlen($checkBehind) == 3)
					$text = trim(substr($text, $fParaPos + 4));
			}
		}
		
		//remove initial div.
		for ($i = 0; $i < strlen($text); $i++)
		{
			$fParaPos = strpos($text, "<div>");
			if (!($fParaPos === false))
			{
				$checkBehind = trim(substr($text, 0, $fParaPos + strlen("<div>") + 1));

				if (strlen($checkBehind) == strlen("<div>"))
				{
					$text = trim(substr($text, $fParaPos + strlen("<div>") + 1));
				}
					
			}
		}
		
		$text = trim($text);
		$text = str_replace("<p>", "<p/>",$text);
		$text = str_replace("<div>", "<p/>",$text);

		return $text;
	}
	
	function  generateID($markerLength)
	{
		//script to create new user for the cms.
		//generate photo name for this.
		//number of letters each photo will carry on its name.
		if ($markerLength <= 0)
			return FALSE;
		
		$alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_";
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
	require_once("../includes/user_checker.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");

	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	//process article publish and uploads
	$userId = $_POST['userId'];
	$categoryId = $_POST['categoryId'];
	$subcategoryId = $_POST['subcategoryId'];
	$articleActionButton = $_POST['articleActionButton'];
	$author = trim($_POST['articleAuthor']);
	$heading = trim($_POST['articleHeading']);
	$publishDate = $_POST['publishDate'];
	$articleWriteUp = trim($_POST['articleEditor']);
	$topStory = isset($_POST['makeTopStory']) ? 1 : 0;
	
	$pday = $_POST['pday'];
	$pmonth = $_POST['pmonth'];
	$pyear = $_POST['pyear'];
	
	$action = $_POST['articleActionButton'];
	
	//clean up
	$articleWriteUp = cleanUp($articleWriteUp);
	$heading = cleanUp($heading);
	$author = cleanUp($author);
	$articleGenId = NULL;
	
	$ini = parse_ini_file("../config/app.ini", true); //get ini info
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$author = $dbConn->real_escape_string($author);
		$heading = $dbConn->real_escape_string($heading);
		$articleWriteUp = $dbConn->real_escape_string($articleWriteUp);
		
		//generate an artile Id and commit.
		for ($j = 0; $j < 20; $j++) //try generating a usable name for the user or quit on 20 trys.
		{
			$marker = generateID(10);
			if ($marker != FALSE)
			{
				$sql = sprintf("SELECT * FROM articles WHERE ArticleGenID='%s'", $marker);
				$result = $dbConn->query($sql);
				
				if ($result && $result->num_rows == 0)
				{
					$theDateTime = "NULL";
					
					if ($action == "publish")
						$theDateTime = $pyear . "-" . $pmonth . "-" . $pday . " " . date("H:i:s");
					
					//commit article id and break.
					$sql = sprintf("INSERT INTO articles (ArticleGenID, Heading, Author, PublishDate, TextContent, TopStory, SCGenID) VALUES('%s', '%s', '%s', '%s', '%s', %d, '%s')", $marker, trim($heading), trim($author), $theDateTime, trim($articleWriteUp), $topStory, $subcategoryId);
					
					$dbConn->query($sql);
					$articleGenId = $marker;
					//make article breaking news.
					
					if ($ini)
					{
						$bnExpireTime = $ini['SITE_MISC']['BreakingNewsExpires'];
						
						if (isset($bnExpireTime) && is_numeric($bnExpireTime))
						{
							$sql = sprintf("INSERT INTO breakingnews (ArticleGenID, ExpireDate) VALUES('%s', %d)", $marker, time() + $bnExpireTime);
							$dbConn->query($sql);
						}
					}
					
					//put article under group.
					if ($_POST['groupSelection'] != "0")
					{
						$sql = sprintf("INSERT INTO grouparticle (GroupGenID, ArticleGenID) VALUES('%s','%s')", trim($_POST['groupSelection']), $marker);
						$dbConn->query($sql);
					}
					
					break;
				}
				else
					continue;
			}
		}
		
		//check attached files count.
		$photoCount = count($_FILES['photoObject']['name']);
		$flashCount = count($_FILES['flashObject']['name']);
		$videoCount = count($_FILES['videoObject']['name']);
		
		$photoUploadError = array();
		$flashUploadError = array();
		$videoUploadError = array();
		
		//attach article assets.
		if ($photoCount > 0)
		{
			//try to generate unique id for each photo or quit try for the photo after 20 trys.
			for ($i = 0; $i < $photoCount; $i++)
			{
				for ($j = 0; $j < 20; $j++) //try generating a usable name for the user or quit on 20 trys.
				{
					$marker = generateID(20);
					//test the marker.
					if ($marker != FALSE)
					{
						$sql = sprintf("SELECT * FROM articlephoto WHERE ArtPhotoID='%s'", $marker);
						$result = $dbConn->query($sql);
						
						if ($result && $result->num_rows == 0)
						{
							//check file size
							//check file content
							if (!$ini || !array_key_exists('UPLOAD_LIMITS', $ini) || !array_key_exists('photoUploadLimit', $ini['UPLOAD_LIMITS']))
								break;
							
							if ($_FILES['photoObject']['size'][$i] > trim($ini['UPLOAD_LIMITS']['photoUploadLimit']))
								break;
								
							//save photo file.
							$imageString = file_get_contents($_FILES['photoObject']['tmp_name'][$i]);
							if ($imageString)
							{
								$imageString = $dbConn->real_escape_string($imageString);
								
								//check image type sent for upload.
								$imageMime = array("image/jpeg"=>"jpg", "image/pjpg"=>"jpg", "image/png"=>"png", "image/bmp"=>"bmp", "image/gif"=>"gif");
								$imageType = $_FILES['photoObject']['type'][$i];
								
								if (array_key_exists($imageType, $imageMime))
								{
									//add the photo to database.
									$sql = sprintf('INSERT INTO articlephoto (ArtPhotoID, ArticleGenID, Photo, ImageFormat) VALUES("%s", "%s", "%s", "%s")', $marker, $articleGenId, $imageString, $imageMime[$imageType]);
									
									$dbConn->query($sql);
									
									if ($dbConn->sqlstate == "00000")
									{
										break; //process next photo file.
									}
									else
									{
										$photoUploadError [$_FILES['photoObject']['name'][$i]] = "Database commit error";
										break; //process next photo file.
									}
								}
								else
								{
									$photoUploadError [$_FILES['photoObject']['name'][$i]] = "Image is not an acceptable format. Use jpg, png,or bmp image files";
									break; //process next photo file.
								}
							}
							else
								break;
						}
						else
							continue;
					}
				}
			}
		}
		
		if ($flashCount > 0)
		{
			//try to generate unique id for each photo or quit try for the photo after 20 trys.
			for ($i = 0; $i < $flashCount; $i++)
			{
				for ($j = 0; $j < 20; $j++) //try generating a usable name for the user or quit on 20 trys.
				{
					$marker = generateID(20);
					//test the marker.
					if ($marker != FALSE)
					{
						$sql = sprintf("SELECT * FROM articleflash WHERE FlashContentID='%s'", $marker);
						$result = $dbConn->query($sql);
						
						if ($result && $result->num_rows == 0)
						{
							//check file size
							//check file content
							if (!$ini || !array_key_exists('UPLOAD_LIMITS', $ini) || !array_key_exists('flashUploadLimit', $ini['UPLOAD_LIMITS']))
								break;
							
							if ($_FILES['flashObject']['size'][$i] > trim($ini['UPLOAD_LIMITS']['flashUploadLimit']))
								break;
								
							//save photo file.
							$flashString = file_get_contents($_FILES['flashObject']['tmp_name'][$i]);
							if ($flashString)
							{
								$flashString = $dbConn->real_escape_string($flashString);
								
								//check image type sent for upload.
								$flashMime = array("application/x-shockwave-flash"=>"swf");
								$flashType = $_FILES['flashObject']['type'][$i];
								
								if (array_key_exists($flashType, $flashMime))
								{
									//add the photo to database.
									$sql = sprintf('INSERT INTO articleflash (FlashContentID, ArticleGenID, Flash) VALUES("%s", "%s", "%s")', $marker, $articleGenId, $flashString);
									
									$dbConn->query($sql);
									
									if ($dbConn->sqlstate == "00000")
									{
										break; //process next photo file.
									}
									else
									{
										$flashUploadError [$_FILES['flashObject']['name'][$i]] = "Database commit error";
										break; //process next photo file.
									}
								}
								else
								{
									$flashUploadError [$_FILES['flashObject']['name'][$i]] = "Flash is not an acceptable format. Use .swf files";
									break; //process next photo file.
								}
							}
							else
								break;
						}
						else
							continue;
					}
				}
			}
		}
		
		if ($videoCount > 0) //video must be fla since we are using flash to play video.
		{
			//try to generate unique id for each photo or quit try for the photo after 20 trys.
			for ($i = 0; $i < $videoCount; $i++)
			{
				for ($j = 0; $j < 20; $j++) //try generating a usable name for the user or quit on 20 trys.
				{
					$marker = generateID(20);
					//test the marker.
					if ($marker != FALSE)
					{
						$sql = sprintf("SELECT * FROM articlevideo WHERE VideoContentID='%s'", $marker);
						$result = $dbConn->query($sql);
						
						if ($result && $result->num_rows == 0)
						{
							//check file size
							//check file content
							if (!$ini || !array_key_exists('UPLOAD_LIMITS', $ini) || !array_key_exists('videoUploadLimit', $ini['UPLOAD_LIMITS']))
								break;
							
							if ($_FILES['videoObject']['size'][$i] > trim($ini['UPLOAD_LIMITS']['videoUploadLimit']))
								break;
								
							//save photo file.
							$videoString = file_get_contents($_FILES['videoObject']['tmp_name'][$i]);
							if ($videoString)
							{
								//$videoString = $dbConn->real_escape_string($videoString);
								
								//check image type sent for upload.
								$videoMime = array("application/octet-stream"=>"flv");
								$videoType = $_FILES['videoObject']['type'][$i];

								if (array_key_exists($videoType, $videoMime))
								{
									//add the photo to database.
									$sql = sprintf('INSERT INTO articlevideo (VideoContentID, ArticleGenID) VALUES("%s", "%s")', $marker, $articleGenId); //currently i havent found a way of saving video files as blob just as i did with photos....check on that for efficient storage.
									
									$dbConn->query($sql);
									
									if ($dbConn->sqlstate == "00000")
									{
										file_put_contents("../../flashVideo/$marker.flv", $videoString);
										
										break; //process next photo file.
									}
									else
									{
										$videoUploadError [$_FILES['videoObject']['name'][$i]] = "Database commit error";
										break; //process next photo file.
									}
								}
								else
								{
									$videoUploadError [$_FILES['videoObject']['name'][$i]] = "Video is not an acceptable format. Use .flv files";
									break; //process next photo file.
								}
							}
							else
								break;
						}
						else
							continue;
					}
				}
			}
		}
	}
	
	//get tab name.
	$sql = "SELECT CategoryName FROM category WHERE CatGenID='$categoryId'";
	$result = $dbConn->query($sql);
	$tabName = NULL;
	
	if ($result && $result->num_rows > 0)
	{
		$row = $result->fetch_array();
		$tabName = $row['CategoryName'];
	}
	

	header("Location: ../forms/room.php?sel=".$categoryId."&user=".$_SESSION['authentication']."&role=".$_SESSION['Role']."&tsk=" . GENERAL_TASK_PUBLISHED_ARTICLES . "&sc=".$subcategoryId."&tabName=". urlencode($tabName));
	exit();
?>