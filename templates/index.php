<?php define('PAGE_NAME', 'home'); ?>
<?php
/*********************************************************************!
 * Your Site's CMS ver 1.0
 * http://www.havilahcreation.com
 *
 * Copyright 2011, Havilah Creations Int Ltd
 * No part of this work should be used without
 * written premission from havilah creation int ltd
 * Author: Maduabueke Franklin Nnamdi
 * Date: Mon May 5, 2011 6:20 AM
 **********************************************************************/
 
	$prefix = ""; //for relative paths...none needed here.
	
	require_once($prefix . "config/db.php");
	require_once($prefix . "modules/mysqli.php");
	require_once($prefix . "includes/commons.php");
	require_once($prefix . "modules/lookConfigure.php");
	require_once($prefix . "modules/adsLauncher.php");
	
	$result = NULL;
	//the id of the category this template will use for getting news.
	$categoryId = PAGE_NAME;
	$categoryName = NULL;
	//each section will collect its result from a mysqli result variable having its id
	$s1Result = NULL;
	//folder name.
	$pageFolderName = NULL;
	$looknfeelName = NULL;
	
	$topStorysCollection = array(); //collection of topstory articles that have been displayed.
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		/*
		$sql = sprintf("SELECT CategoryName, CatGenID FROM Category WHERE CatGenID='%s'", 'rdIaZrJq');
		$result = $dbConn->query($sql);
		
		if ($result && $result->num_rows > 0)
		{
			//get folder name for page.
			$categoryRow = $result->fetch_array();
			$categoryName =  $categoryRow['CategoryName'];
			
			$sql = sprintf("SELECT WebFolderName, WebLooknFeelName FROM Category WHERE CatGenID='%s'", 'rdIaZrJq');
			$pageFolderResult = $dbConn->query($sql);
			if ($pageFolderResult && $pageFolderResult->num_rows > 0)
			{
				$pageFolderRow = $pageFolderResult->fetch_array();
				$pageFolderName = $pageFolderRow['WebFolderName'];
				$looknfeelName = $pageFolderRow['WebLooknFeelName'];
			}
		}
		*/
	}
	else
	{
		if (PAGE_NAME == "home")
			header("Location: unavailable.php"); //redirect to this page when db is offline.
		else
			header("Location: ../unavailable.php");
			
		exit();
	}
	
	$onTheNewsTabSel = ON_THE_NEWS_LAST_24_HOURS;
	
	switch ($_GET['otn'])
	{
	case ON_THE_NEWS_LAST_24_HOURS:
		$onTheNewsTabSel = ON_THE_NEWS_LAST_24_HOURS;
	break;
	case ON_THE_NEWS_MOST_VIEWED:
		$onTheNewsTabSel = ON_THE_NEWS_MOST_VIEWED;
	break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>www.nigerianewsnetwork.com</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/global.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/index.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/scrollingCtrl.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/adsSquare.css"  />
<script type="text/javascript">
	<?php 
		$jsPrefix = $prefix;
	?>
	var prefix = <?php echo "'$jsPrefix'";?>;
</script>
<script type="text/javascript" src="<?php echo $prefix;?>scripts/jQuery.js"></script>
<script type="text/javascript" src="<?php echo $prefix;?>scripts/scrollingCtrl.js"></script>

<body>
<div id="pageContainter" align="center">
<?php
	require_once($prefix . "includes/header.php");
?>
	<?php require_once($prefix . "includes/headerBasePanel.php") ?>
	<div id="pageContentHolder">
		<div id="pageContentMainBlock">
		<div style="height:1px" id="spacing"></div>
		<?php require_once($prefix . "includes/scrollingNewsControl.php"); ?>
			<?php
				//query for top story.
				$sql = "SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, art.PublishDate ,sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE TopStory=1 AND PublishDate<>'0000-00-00' ORDER BY PublishDate DESC, AID LIMIT 4";
				
				$topstoryResult = $dbConn->query($sql);
				$topstoryArticleRow = NULL;
				$topstoryPhotoRow = NULL;
				
				if ($topstoryResult && $topstoryResult->num_rows > 0)
				{
					//randomly select a top story to view.
					$selectArticle = rand(0, $topstoryResult->num_rows - 1);
					$topstoryResult->data_seek($selectArticle);
					$topstoryArticleRow = $topstoryResult->fetch_array();
					
					$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $topstoryArticleRow['ArticleGenID']);
					$topstoryPhotoResult = $dbConn->query($sql);
					if ($topstoryPhotoResult && $topstoryPhotoResult->num_rows > 0)
					{
						//do a random select for a photo if we have more than 1.
						if ($topstoryPhotoResult->num_rows > 1)
						{
							$selectPhoto = rand(0, $topstoryPhotoResult->num_rows - 1);
							$topstoryPhotoResult->data_seek($selectPhoto);
						}
						
						$topstoryPhotoRow = $topstoryPhotoResult->fetch_array();
					}
					
					$topStorysCollection[0] = "'" . $topstoryArticleRow['ArticleGenID'] . "'"; //adding to collection
				}
			?>
			<div id="pageLeftColumn">
				<div id="topStoriesSection">
					<div class="sectionHeading" id="topStoriesHeading">
						<div style="margin-left:12px;">Top Stories</div>
					</div>
					<div id="topStoriesContent">
						<div id="topStoriesBigScreenHolder">
							<div id="topStoriesBigScreen">
								<a class="linkImage" href="<?php echo $prefix; ?>processing/redirectToRead.php?sel=<?php echo $topstoryArticleRow['CatGenID']."&sc=".$topstoryArticleRow['SCGenID']."&article=".$topstoryArticleRow['ArticleGenID']?>"><img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $topstoryPhotoRow['ArtPhotoID'];?>" /></a>
							</div>
							<div id="topStoriesBigScreenText" style="margin-bottom:10px;">
								<span class="midNewsExText"><?php echo substr($topstoryArticleRow['TextContent'], 0, 100) . "...";?></span>
								<br/>
								<img src="<?php echo $prefix; ?>images/commentIcon.jpg" style="margin-top:3px; margin-right:0px; width:auto; height:auto;" />
								<?php
									$sql = sprintf("SELECT COUNT(CommentID) AS CommentCount FROM comments WHERE ArticleGenID='%s'", $topstoryArticleRow['ArticleGenID']);
									$bCommentResult = $dbConn->query($sql);
									$commentCount = 0;
									if ($bCommentResult && $bCommentResult->num_rows > 0)
									{
										$bCommentRow = $bCommentResult->fetch_array();
										$commentCount = $bCommentRow['CommentCount'];
									}
								?>
								<a class="commentLink" href="<?php echo $prefix; ?>processing/redirectToRead.php?sel=<?php echo $topstoryArticleRow['CatGenID']."&sc=".$topstoryArticleRow['SCGenID']."&article=".$topstoryArticleRow['ArticleGenID']?>" style="font-size:12px; color:#0099CC; text-decoration:none"><?php echo $commentCount;?> Comments</a>
								<?php
								?>
							</div>
						</div>
						
						<div id="topStoriesThumbnailHolder">
							<?php
								$bigScreenArticle = $topstoryArticleRow['ArticleGenID'];
								$topstoryResult->data_seek(0);
								
								$drawCount = 0;
								for (; $drawCount < 2;)
								{
									if ($topstoryArticleRow = $topstoryResult->fetch_array())
									{
										if ($topstoryArticleRow && $topstoryArticleRow['ArticleGenID'] != $bigScreenArticle)
										{
											$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $topstoryArticleRow['ArticleGenID']);
											$topstoryPhotoResult = $dbConn->query($sql);
											if ($topstoryPhotoResult && $topstoryPhotoResult->num_rows > 0)
											{
												//do a random select for a photo if we have more than 1.
												if ($topstoryPhotoResult->num_rows > 1)
												{
													$selectPhoto = rand(0, $topstoryPhotoResult->num_rows - 1);
													$topstoryPhotoResult->data_seek($selectPhoto);
												}
						
												$topstoryPhotoRow = $topstoryPhotoResult->fetch_array();
											}
							?>
							<div class="topStoriesThunbnailAndTextHolder">
								<div id="topStoriesThumb<?php echo $drawCount + 1; ?>" class="thumbnails">
									<a class="linkImage" href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $topstoryArticleRow['CatGenID']."&sc=".$topstoryArticleRow['SCGenID']."&article=".$topstoryArticleRow['ArticleGenID']?>"><img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $topstoryPhotoRow['ArtPhotoID'];?>" class="thumbnailImg"  /></a>
								</div>
								<div class="topStoriesThumnailTextHolder">
										<label class="topStoriesThumbnailHeading" style="color:#00005A;font-weight:bold">
											<?php echo $topstoryArticleRow['Heading']; ?>
										</label>
										<br/>
										<div class="topStoriesStoryEx">
											<label class="date"><?php echo date("j M Y", strtotime($topstoryArticleRow['PublishDate'])); ?>:</label> <?php echo substr($topstoryArticleRow['TextContent'], 0, 77) . "...";?>
										</div>
								</div>
								<div style="clear:both; height:2px"></div>
							</div>
							<?php
											$topStorysCollection[count($topStorysCollection)] = "'" . $topstoryArticleRow['ArticleGenID'] . "'"; //adding to collection
											
											$drawCount++;
										}
									}
									else
										break;
								}
							?>
							
							
							<?php
								if ($topstoryArticleRow = $topstoryResult->fetch_array())
								{
									$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $topstoryArticleRow['ArticleGenID']);
									$topstoryPhotoResult = $dbConn->query($sql);
									if ($topstoryPhotoResult && $topstoryPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($topstoryPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $topstoryPhotoResult->num_rows - 1);
											$topstoryPhotoResult->data_seek($selectPhoto);
										}
						
										$topstoryPhotoRow = $topstoryPhotoResult->fetch_array();
									}
							?>
							<div class="topStoriesThunbnailAndTextHolder" style="font-size:12px; color:#666666; text-align:left">
									<label class="topStoriesThumbnailHeading" style="color:#00005A;font-weight:bold"><?php echo $topstoryArticleRow['Heading']; ?></label>
									<br/>
									<label class="date"><?php echo date("j M Y", strtotime($topstoryArticleRow['PublishDate'])); ?>:</label> <?php echo substr($topstoryArticleRow['TextContent'], 0, 77) . "...";?><a href="<?php echo $prefix; ?>processing/redirectToRead.php?sel=<?php echo $topstoryArticleRow['CatGenID']."&sc=".$topstoryArticleRow['SCGenID']."&article=".$topstoryArticleRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
							</div>
							<?php
									$topStorysCollection[count($topStorysCollection)] = "'" . $topstoryArticleRow['ArticleGenID'] . "'"; //adding to collection
								}
							?>
							
						</div>
					</div>
				</div>
				
				
				<!-- Below top stories mid sections-->
				<div id="midLeftColumn">
					<?php
					//script to get other articles from this category.
					//get collection of displayed articles to string.
					$listOfDisplayedArticles = implode(",", $topStorysCollection);
					
					$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE ArticleGenID NOT IN(%s) AND PublishDate<>'0000-00-00' ORDER BY PublishDate DESC", $listOfDisplayedArticles);
					
					$otherArticlesResult = $dbConn->query($sql);
					?>
					<?php
							$otherArticlesRow = $otherArticlesResult->fetch_array();
							if ($otherArticlesRow)
							{
								/*
								$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $otherArticlesRow['ArticleGenID']);
								$otherArticlesPhotoResult = $dbConn->query($sql);
								if ($otherArticlesPhotoResult && $otherArticlesPhotoResult->num_rows > 0)
								{
									//do a random select for a photo if we have more than 1.
									if ($otherArticlesPhotoResult->num_rows > 1)
									{
										$selectPhoto = rand(0, $otherArticlesPhotoResult->num_rows - 1);
										$otherArticlesPhotoResult->data_seek($selectPhoto);
									}
						
									$otherArticlesPhotoRow = $otherArticlesPhotoResult->fetch_array();
								}*/
					?>
					<div id="midLNews1" class="midLNewsSections">
						<label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label>
						<p/>
						<span class="midNewsExText">
							<?php echo substr($otherArticlesRow['TextContent'], 0, 247) . "...";?>
<a href="<?php echo $prefix; ?>processing/redirectToRead.php?sel=<?php echo $otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
						</span>
					</div>
					<?php
							}
					?>
					
					<?php
							$otherArticlesRow = $otherArticlesResult->fetch_array();
							if ($otherArticlesRow)
							{
								/*
								$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $otherArticlesRow['ArticleGenID']);
								$otherArticlesPhotoResult = $dbConn->query($sql);
								if ($otherArticlesPhotoResult && $otherArticlesPhotoResult->num_rows > 0)
								{
									//do a random select for a photo if we have more than 1.
									if ($otherArticlesPhotoResult->num_rows > 1)
									{
										$selectPhoto = rand(0, $otherArticlesPhotoResult->num_rows - 1);
										$otherArticlesPhotoResult->data_seek($selectPhoto);
									}
						
									$otherArticlesPhotoRow = $otherArticlesPhotoResult->fetch_array();
								}*/
					?>
					<div id="midLNews2" class="midLNewsSections">
						<label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label>
						<p/>
						<span class="midNewsExText">
							<?php echo substr($otherArticlesRow['TextContent'], 0, 247) . "...";?><a href="<?php $prefix; ?>processing/redirectToRead.php?sel=<?php echo $otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
						</span>
					</div>
					<?php
							}
					?>
					
					<?php
							$otherArticlesRow = $otherArticlesResult->fetch_array();
							if ($otherArticlesRow)
							{
								/*
								$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $otherArticlesRow['ArticleGenID']);
								$otherArticlesPhotoResult = $dbConn->query($sql);
								if ($otherArticlesPhotoResult && $otherArticlesPhotoResult->num_rows > 0)
								{
									//do a random select for a photo if we have more than 1.
									if ($otherArticlesPhotoResult->num_rows > 1)
									{
										$selectPhoto = rand(0, $otherArticlesPhotoResult->num_rows - 1);
										$otherArticlesPhotoResult->data_seek($selectPhoto);
									}
						
									$otherArticlesPhotoRow = $otherArticlesPhotoResult->fetch_array();
								}*/
					?>
					<div id="midLNews3" class="midLNewsSections" style="border:0px">
						<label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label>
						<p/>
						<span class="midNewsExText">
							<?php echo substr($otherArticlesRow['TextContent'], 0, 247) . "...";?><a href="<?php $prefix; ?>processing/redirectToRead.php?sel=<?php echo $otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
						</span>
					</div>
					<?php
							}
					?>
					
					
					<?php include($prefix . "includes/connectToUsCtrl.php"); ?>
				</div>
				
				<div id="midRightColumn">
					
					<?php
							$otherArticlesRow = $otherArticlesResult->fetch_array();
							if ($otherArticlesRow)
							{
								$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $otherArticlesRow['ArticleGenID']);
								$otherArticlesPhotoResult = $dbConn->query($sql);
								if ($otherArticlesPhotoResult && $otherArticlesPhotoResult->num_rows > 0)
								{
									//do a random select for a photo if we have more than 1.
									if ($otherArticlesPhotoResult->num_rows > 1)
									{
										$selectPhoto = rand(0, $otherArticlesPhotoResult->num_rows - 1);
										$otherArticlesPhotoResult->data_seek($selectPhoto);
									}
						
									$otherArticlesPhotoRow = $otherArticlesPhotoResult->fetch_array();
								}
					?>
					<div id="midRNewsWithImage" class="midRNews" style="margin-top:27px; border:0px; height:160px;">
						<div style="text-align:left; width:360px"><label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label></div>
							<div id="midRNWithImageScreen" style="border:0px">
								<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
							<div id="midRNWithImageExHolder">
								<span class="midNewsExText">
								<?php echo substr($otherArticlesRow['TextContent'], 0, 268) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
								</span>
							</div>
						<div>
						</div>
					</div>
					<?php
							}
					?>
					
					<?php
							$otherArticlesRow = $otherArticlesResult->fetch_array();
							if ($otherArticlesRow)
							{
								/*
								$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $otherArticlesRow['ArticleGenID']);
								$otherArticlesPhotoResult = $dbConn->query($sql);
								if ($otherArticlesPhotoResult && $otherArticlesPhotoResult->num_rows > 0)
								{
									//do a random select for a photo if we have more than 1.
									if ($otherArticlesPhotoResult->num_rows > 1)
									{
										$selectPhoto = rand(0, $otherArticlesPhotoResult->num_rows - 1);
										$otherArticlesPhotoResult->data_seek($selectPhoto);
									}
						
									$otherArticlesPhotoRow = $otherArticlesPhotoResult->fetch_array();
								}*/
					?>
					<div id="midRNewsNoImage1" class="midRNews">
						<div style="text-align:left; margin-top:20px; width:360px"><label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label></div>
						<div id="midRNewsNoImage1Tex" style="margin-top:15px; margin-bottom:15px">
							<span class="midNewsExText"><?php echo substr($otherArticlesRow['TextContent'], 0, 402) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a></span>
						</div>
					</div>
					<?php
							}
					?>
					
					<?php
							$otherArticlesRow = $otherArticlesResult->fetch_array();
							if ($otherArticlesRow)
							{
								/*
								$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $otherArticlesRow['ArticleGenID']);
								$otherArticlesPhotoResult = $dbConn->query($sql);
								if ($otherArticlesPhotoResult && $otherArticlesPhotoResult->num_rows > 0)
								{
									//do a random select for a photo if we have more than 1.
									if ($otherArticlesPhotoResult->num_rows > 1)
									{
										$selectPhoto = rand(0, $otherArticlesPhotoResult->num_rows - 1);
										$otherArticlesPhotoResult->data_seek($selectPhoto);
									}
						
									$otherArticlesPhotoRow = $otherArticlesPhotoResult->fetch_array();
								}*/
					?>
					<div id="midRNewsNoImage2" class="midRNews">
						<div style="text-align:left; margin-top:20px; width:360px"><label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label></div>
						<div id="midRNewsNoImage2Tex" style="margin-top:15px; margin-bottom:15px">
							<span class="midNewsExText"><?php echo substr($otherArticlesRow['TextContent'], 0, 168) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a></span>
						</div>
					</div>
					<?php
							}
					?>
					
					<!-- section 1 for this template -->
					<?php
						//script for this section in sportsLook.php template
						if ($dbConn)
						{
							//get section.
							if (PAGE_NAME == "home")
							{
								$pageFolderName = "index"; 
								$looknfeelName = "indexLook";
							}

							$lookConf = new LookConfigure($pageFolderName . ".xml");
							if ($lookConf && $lookConf->isXMLFileLoaded())
							{
								$looknfeel = $lookConf->getLooknFeelWithID($looknfeelName);
								
								if ($looknfeel)
								{
									
									$section = $lookConf->getLooknFeelSectionWithId("s1", $looknfeel);
									if ($section)
									{
										//get section details
										$details = $lookConf->getLooknFeelSectionDetails($section);
										if ($details)
										{
											//get artilce.
											$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE art.ArticleGenID<>'%s' AND art.SCGenID='%s' AND art.PublishDate<>'0000-00-00' ORDER BY PublishDate DESC", $topstoryArticleRow['ArticleGenID'], $details['subcategoryLink']);
											$sectionResult = $dbConn->query($sql);
											if ($sectionResult && $sectionResult->num_rows > 0)
											{
					?>
					<div id="midRNewsBottomSection">
						<div class="sectionHeading" id="midBottomHeading">
							<div style="margin-left:0px; margin-top:5px;"><?php echo $details['heading']; ?></div>
						</div>
						<!-- section tumbnails and news -->
							<?php
								$sectionRow = $sectionResult->fetch_array();
								if ($sectionRow)
								{
									$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $sectionRow['ArticleGenID']);
									$sectionArticlesPhotoResult = $dbConn->query($sql);
									if ($sectionArticlesPhotoResult && $sectionArticlesPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($sectionArticlesPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $sectionArticlesPhotoResult->num_rows - 1);
											$sectionArticlesPhotoResult->data_seek($selectPhoto);
										}
						
										$sectionArticlesPhotoRow = $sectionArticlesPhotoResult->fetch_array();
									}
							?>
						<div class="midBottomThumbnailAndTextHolder" id="midBottomThumdbnailAndTextHolder1">
							<div class="midBottomThumbnailHolder">
								<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
							
							<div class="midBottomTextHolder">
								<div style="text-align:left; width:243px;margin-bottom:5px"><label class="midNewsSectionsHeading"><?php echo $sectionRow['Heading']; ?></label></div>
								<span class="midNewsExText"><?php echo substr($sectionRow['TextContent'], 0, 88) . "...";?><br/><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID']."&sc=".$sectionRow['SCGenID']."&article=".$sectionRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a></span>
							</div>
						</div>
							<?php
								}
							?>
							
							<?php
								$sectionRow = $sectionResult->fetch_array();
								if ($sectionRow)
								{
									$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $sectionRow['ArticleGenID']);
									$sectionArticlesPhotoResult = $dbConn->query($sql);
									if ($sectionArticlesPhotoResult && $sectionArticlesPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($sectionArticlesPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $sectionArticlesPhotoResult->num_rows - 1);
											$sectionArticlesPhotoResult->data_seek($selectPhoto);
										}
						
										$sectionArticlesPhotoRow = $sectionArticlesPhotoResult->fetch_array();
									}
							?>
						<div class="midBottomThumbnailAndTextHolder" id="midBottomThumbnailAndTextHolder2">
							<div class="midBottomThumbnailHolder">
								<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
							
							<div class="midBottomTextHolder">
								<div style="text-align:left; width:243px;margin-bottom:5px"><label class="midNewsSectionsHeading"><?php echo $sectionRow['Heading']; ?></label></div>
								<span class="midNewsExText"><?php echo substr($sectionRow['TextContent'], 0, 88) . "...";?><br/><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID']."&sc=".$sectionRow['SCGenID']."&article=".$sectionRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a></span>
							</div>
						</div>
							<?php
								}
							?>
							
							
							<?php
								$sectionRow = $sectionResult->fetch_array();
								if ($sectionRow)
								{
									$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $sectionRow['ArticleGenID']);
									$sectionArticlesPhotoResult = $dbConn->query($sql);
									if ($sectionArticlesPhotoResult && $sectionArticlesPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($sectionArticlesPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $sectionArticlesPhotoResult->num_rows - 1);
											$sectionArticlesPhotoResult->data_seek($selectPhoto);
										}
						
										$sectionArticlesPhotoRow = $sectionArticlesPhotoResult->fetch_array();
									}
							?>
						<div class="midBottomThumbnailAndTextHolder" id="midBottomThumbnailAndTextHolder3">
							<div class="midBottomThumbnailHolder">
								<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
							
							<div class="midBottomTextHolder">
								<div style="text-align:left; width:243px;margin-bottom:5px"><label class="midNewsSectionsHeading"><?php echo $sectionRow['Heading']; ?></label></div>
								<span class="midNewsExText"><?php echo substr($sectionRow['TextContent'], 0, 88) . "...";?><br/><a href="<?php echo $prefix; ?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID']."&sc=".$sectionRow['SCGenID']."&article=".$sectionRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a></span>
							</div>
						</div>
							<?php
								}
							?>
							
						<?php
											}
										}
									}
								}
							}
						}
					?>
					</div>
				</div>
			</div>
		
			<div id="pageRightColumn">
				<!-- include adverts gettter with php -->
				<?php include($prefix . "includes/adsSquare.php"); ?>
				
				<!--on the news section -->
				<div id="onTheNewsSection" class="">
					<div class="sectionHeading" id="RMidHeading" style="height:20px">
						<div style="margin-left:0px; margin-top:5px;">On The News</div>
					</div>
					<div id="ontheNewsTabHolder">
						<ul id="tabs">
							<li class="<?php if ($onTheNewsTabSel != ON_THE_NEWS_MOST_VIEWED) echo "onTheNewsInactiveTab"; else echo "onTheNewsActiveTab"; ?>"><?php if ($onTheNewsTabSel != ON_THE_NEWS_MOST_VIEWED){ ?><a href="index.php?otn=mv"><?php } ?>Most Viewed<?php if ($onTheNewsTabSel != ON_THE_NEWS_MOST_VIEWED){ ?></a><?php } ?></li>
							<li class="<?php if ($onTheNewsTabSel != ON_THE_NEWS_LAST_24_HOURS) echo "onTheNewsInactiveTab"; else echo "onTheNewsActiveTab"; ?>"><?php if ($onTheNewsTabSel != ON_THE_NEWS_LAST_24_HOURS){ ?><a href="index.php?otn=l24h"><?php } ?>Last 24 Hours<?php if ($onTheNewsTabSel != ON_THE_NEWS_LAST_24_HOURS){ ?></a><?php } ?></li>
						</ul>
					</div>
					<?php
						//get in the last 24hrs or most viewed articles in general
						$sql = "";
						if ($onTheNewsTabSel == ON_THE_NEWS_LAST_24_HOURS)
							$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE PublishDate<>'0000-00-00' AND (PublishDate + INTERVAL(3600 * 24) SECOND >= NOW()) ORDER BY PublishDate DESC LIMIT 3");
						else
							$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE PublishDate<>'0000-00-00' AND ViewCount > 0 ORDER BY ViewCount DESC, PublishDate DESC LIMIT 3");
							
						$onTheNewsResult = $dbConn->query($sql);
						if ($onTheNewsResult && $onTheNewsResult->num_rows > 0)
						{
							
					?>
							<?php
								$otnsectionRow = $onTheNewsResult->fetch_array();
								if ($otnsectionRow)
								{
									$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $otnsectionRow['ArticleGenID']);
									$otnsectionArticlesPhotoResult = $dbConn->query($sql);
									if ($otnsectionArticlesPhotoResult && $otnsectionArticlesPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($otnsectionArticlesPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $otnsectionArticlesPhotoResult->num_rows - 1);
											$otnsectionArticlesPhotoResult->data_seek($selectPhoto);
										}
						
										$otnsectionArticlesPhotoRow = $otnsectionArticlesPhotoResult->fetch_array();
									}
							?>
					<div class="otnSnipetsHolder">
						<div class="otnThumbnail">
							<img src="<?php $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $otnsectionArticlesPhotoRow['ArtPhotoID'];?>" />
						</div>
						<div class="otnThumbEx">
							<?php echo substr($otnsectionRow['TextContent'], 0, 122) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $otnsectionRow['CatGenID']."&sc=".$otnsectionRow['SCGenID']."&article=".$otnsectionRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
						</div>
					</div>
							<?php
								}
							?>
							
							<?php
								$otnsectionRow = $onTheNewsResult->fetch_array();
								if ($otnsectionRow)
								{
									/*
									$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $otnsectionRow['ArticleGenID']);
									$otnsectionArticlesPhotoResult = $dbConn->query($sql);
									if ($otnsectionArticlesPhotoResult && $otnsectionArticlesPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($otnsectionArticlesPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $otnsectionArticlesPhotoResult->num_rows - 1);
											$otnsectionArticlesPhotoResult->data_seek($selectPhoto);
										}
						
										$otnsectionArticlesPhotoRow = $otnsectionArticlesPhotoResult->fetch_array();
									}*/
							?>
					<div class="otnSnipetsHolder">
						<div class="otnNoThumbEx">
								<?php echo substr($otnsectionRow['TextContent'], 0, 181) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $otnsectionRow['CatGenID']."&sc=".$otnsectionRow['SCGenID']."&article=".$otnsectionRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
						</div>
					</div>
							<?php
								}
							?>
					
					
							<?php
								$otnsectionRow = $onTheNewsResult->fetch_array();
								if ($otnsectionRow)
								{
									$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $otnsectionRow['ArticleGenID']);
									$otnsectionArticlesPhotoResult = $dbConn->query($sql);
									if ($otnsectionArticlesPhotoResult && $otnsectionArticlesPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($otnsectionArticlesPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $otnsectionArticlesPhotoResult->num_rows - 1);
											$otnsectionArticlesPhotoResult->data_seek($selectPhoto);
										}
						
										$otnsectionArticlesPhotoRow = $otnsectionArticlesPhotoResult->fetch_array();
									}
							?>
					<div class="otnSnipetsHolder" style="border:0px;">
						<div class="otnThumbnail">
							<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $otnsectionArticlesPhotoRow['ArtPhotoID'];?>" />
						</div>
						<div class="otnThumbEx">
							<?php echo substr($otnsectionRow['TextContent'], 0, 120) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $otnsectionRow['CatGenID']."&sc=".$otnsectionRow['SCGenID']."&article=".$otnsectionRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
						</div>
					</div>
							<?php
								}
							?>
							
					<?php
						}
					?>
				</div>
				
				<!--section 2 of index template -->
				
				<?php
					if ($dbConn)
					{
						//get the most popular for all subcategorys.
						$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.SubCatName, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON art.SCGenID=sc.SCGenID WHERE art.SCGenID IN(SELECT SCGenID FROM subcategory WHERE CatGenID='%s') AND PublishDate<>'0000-00-00' AND ViewCount > 0 ORDER BY ViewCount DESC, PublishDate DESC", PAGE_NAME);
						
						$mostPopularResult = $dbConn->query($sql);
						if ($mostPopularResult && $mostPopularResult->num_rows > 0)
						{
							
				?>
				<div id="businessAroundTheWorldSection">
					<div class="sectionHeading" id="RBottomHeading" style="height:20px">
							<div style="margin-left:0px; margin-top:5px;">Most Popular Articles</div>
					</div>
					<?php
								//render only 5 most popular.
							for ($i = 0; $i < 5; $i++)
							{
								if ($mostPopularRow = $mostPopularResult->fetch_array())
								{
					?>
					<div class="lilSnipetsHolder">
						<div class="lilSnipets">
							<span class="midNewsExText"><label class="snippetHeading"><?php echo $mostPopularRow['SubCatName']; ?>:</label> <?php echo substr($mostPopularRow['Heading'],0, 90); ?>... <a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $mostPopularRow['CatGenID']."&sc=".$mostPopularRow['SCGenID']."&article=".$mostPopularRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a></span>
						</div>
					</div>
					<?php
								}
							}
					?>
				</div>
				<?php
						}
					}
				?>
				
				<?php include($prefix . "includes/pollBoot.php"); ?>
			</div>
			<div style="clear:both; height:1px;"></div>
			<!-- pull out this section when done an include via php -->
			<?php
				require_once($prefix . "includes/bottomSection.php");
			?>
		</div>
	</div>

<?php
	require_once($prefix . "includes/footer.php");
?>
</div>
</body>
</html>