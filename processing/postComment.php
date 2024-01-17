<?php
	//script to handle posting of comments.
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/commons.php");
	
	function  generateID($markerLength)
	{
		//script to create new user for the cms.
		//generate photo name for this.
		//number of letters each photo will carry on its name.
		if ($markerLength <= 0)
			return FALSE;
		
		$alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
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
	
	$comment = str_replace("\'", "&rsquo;",trim($_POST['commentEditor']));
	$articleId = $_POST['articleId'];
	$apperance = NULL;
	$email = NULL;
	$rmsg = "";
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$comment= $dbConn->real_escape_string($comment);
		$email= $dbConn->real_escape_string($email);
		$apperance = $dbConn->real_escape_string($apperance);
		
		//check if email is set for the viewer...if set then test if that
		//information already appears in our database.
		if ( !isset($_COOKIE['member_nigeriannewsnetwork']) )
		{
			$apperance = trim($_POST['apperance']);
			$email = trim($_POST['email']);
			
			if (!isset($apperance, $email) || empty($apperance) || empty($email))
			{
				header("Location: " . $_SERVER['HTTP_REFERER'] . "&rmsg=" . urlencode("Please fill the required fields"));
				exit();
			}
			
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);
			if (!$email) //email not correct
			{
				header("Location: " . $_SERVER['HTTP_REFERER'] . "&rmsg=" . urlencode("Supplied email is not valid"));
				exit();
			}
			else
			{
				//check for the user in members. If doesnot exist then create cookie for this user and set memberId.
				$sql = sprintf("SELECT MemberGenID FROM members WHERE Email='%s'", $email);
				$memberResult = $dbConn->query($sql);
				
				if ($memberResult && $memberResult->num_rows == 0) //no member with email address so add this member
				{ 
					for ($j = 0; $j < 20; $j++) //try generating a usable name for the user or quit on 20 trys.
					{
						$memberMarker = generateID(20);
						if ($memberMarker != FALSE)
						{
							$sql = sprintf("SELECT * FROM members WHERE MemberGenID='%s'", $memberMarker);
							$result = $dbConn->query($sql);
				
							if ($result && $result->num_rows == 0) //no member with that id good to go
							{
								//insert comment.
								$sql = sprintf("INSERT INTO members (MemberGenID, Email, Nickname) VALUES('%s', '%s', '%s')", $memberMarker, $email, $apperance);
								$dbConn->query($sql);
								
								if ($dbConn->sqlstate == "000000")
								{
									$marked = FALSE;

									for ($j = 0; $j < 20; $j++) //try generating a usable id for comment or quit on 20 trys.
									{
										$commentMarker = generateID(20);
										if ($commentMarker != FALSE)
										{
											$sql = sprintf("SELECT * FROM comments WHERE CommentGenID='%s'", $commentMarker);
											$result = $dbConn->query($sql);
											if ($result && $result->num_rows == 0) //no comment with that id...good to go.
											{
												$marked = TRUE;
												break;
											}
										}
									}
								}
								
								if ($marked)
								{
									$sql = sprintf("INSERT INTO comments (Content, ArticleGenID, CommentGenID, PostDateTime, MemberGenID) VALUES('%s', '%s', '%s', '%s', '%s')", $comment, $articleId, $commentMarker, date("Y-m-d H:i:s"), $memberMarker);
									
									$dbConn->query($sql);
									
									//create cookie to expire in next 24hours * 7 (a week).
									setcookie("member_nigeriannewsnetwork[id]", $memberMarker, time() + 3600 * 24 * 7, "/");
									setcookie("member_nigeriannewsnetwork[nickname]", $apperance, time() + 3600 * 24 * 7, "/");
								}
								break;
							}
						}
					}
				}
				else
				{
					//memeber exists with that email address so just add comment and renew the cookie
					//cause the cookie has expired.
					$memberRow = $memberResult->fetch_array();
					$marked = FALSE;
					for ($j = 0; $j < 20; $j++) //try generating a usable name for the user or quit on 20 trys.
					{
						$marker = generateID(20);
						if ($marker != FALSE)
						{
							$sql = sprintf("SELECT * FROM comments WHERE CommentGenID='%s'", $marker);
							$result = $dbConn->query($sql);
							if ($result && $result->num_rows == 0)
							{
								$marked = TRUE;
								break;
							}
						}
					}
					
					if ($marked)
					{
						$sql = sprintf("INSERT INTO comments (Content, ArticleGenID, CommentGenID, PostDateTime, MemberGenID) VALUES('%s', '%s', '%s', '%s', '%s')", $comment, $articleId, $marker, date("Y-m-d H:i:s"), $memberRow['MemberGenID']);
						$dbConn->query($sql);
						
						//update appearance
						$sql = sprintf("UPDATE members SET Nickname='%s' WHERE MemberGenID='%s'", $apperance, $memberRow['MemberGenID']);
						$dbConn->query($sql);
						
						//renew cookie.
						setcookie("member_nigeriannewsnetwork[id]", $memberRow['MemberGenID'], time() + 3600 * 24 * 7, "/");
						setcookie("member_nigeriannewsnetwork[nickname]", $apperance, time() + 3600 * 24 * 7, "/");
					}
					
				}
			}
		}
		else
		{
			//user cookie set so add comment on this article from this user.
			$marked = FALSE;
			for ($j = 0; $j < 20; $j++) //try generating a usable name for the user or quit on 20 trys.
			{
				$marker = generateID(20);
				if ($marker != FALSE)
				{
					$sql = sprintf("SELECT * FROM comments WHERE CommentGenID='%s'", $marker);
					$result = $dbConn->query($sql);
					if ($result && $result->num_rows == 0)
					{
						$marked = TRUE;
						break;
					}
				}
			}
					
			if ($marked)
			{
				$sql = sprintf("INSERT INTO comments (Content, ArticleGenID, CommentGenID, PostDateTime, MemberGenID) VALUES('%s', '%s', '%s', '%s', '%s')", $comment, $articleId, $marker, date("Y-m-d H:i:s"), $_COOKIE['member_nigeriannewsnetwork']['id']);
						
				$dbConn->query($sql);
				//renew cookie.
				setcookie("member_nigeriannewsnetwork[id]", $_COOKIE['member_nigeriannewsnetwork']['id'], time() + 3600 * 24 * 7, "/");
				setcookie("member_nigeriannewsnetwork[nickname]", $_COOKIE['member_nigeriannewsnetwork']['nickname'], time() + 3600 * 24 * 7, "/");
			}
		}
	}
	
	
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit();
?>