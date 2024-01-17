<?php define('PAGE_NAME', 'rdIaZrJq'); ?>
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
 
	$prefix = "../";
	
	require_once($prefix . "config/db.php");
	require_once($prefix . "modules/mysqli.php");
	require_once($prefix . "includes/commons.php");
	require_once($prefix . "modules/lookConfigure.php");
	require_once($prefix . "modules/adsLauncher.php");
	
	$result = NULL;
	
	//the id of the category this template will use for getting news.
	$categoryId = PAGE_NAME;
	$categoryName = NULL;
	
	$topStorysCollection = array(); //collection of topstory articles that have been displayed.
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$sql = sprintf("SELECT CategoryName, CatGenID, WebFolderName, WebLooknFeelName, WebCatTabIndex FROM category WHERE CatGenID='%s'", $categoryId);
		$result = $dbConn->query($sql);
		
		if ($result && $result->num_rows > 0)
		{
			//get folder name for page.
			$categoryRow = $result->fetch_array();
			$categoryName =  $categoryRow['CategoryName'];
			
			$sql = sprintf("SELECT WebFolderName, WebLooknFeelName FROM category WHERE CatGenID='%s'", PAGE_NAME);
			$pageFolderResult = $dbConn->query($sql);
			if ($pageFolderResult && $pageFolderResult->num_rows > 0)
			{
				$pageFolderRow = $pageFolderResult->fetch_array();
				$pageFolderName = $pageFolderRow['WebFolderName'];
				$looknfeelName = $pageFolderRow['WebLooknFeelName'];
			}
		}
	}
	else
	{
		header("Location: " . $prefix . "unavailable.php"); //redirect to this page when db is offline.
		exit();
	}
	
	$onTheNewsTabSel = NULL;
	if (!isset($_GET['otn']) || $_GET['otn'] == ON_THE_NEWS_LAST_24_HOURS)
	{
		$onTheNewsTabSel = ON_THE_NEWS_LAST_24_HOURS;
	}
	else
	{
		if (isset($_GET['otn']) && $_GET['otn'] == ON_THE_NEWS_MOST_VIEWED)
			$onTheNewsTabSel = ON_THE_NEWS_MOST_VIEWED;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>www.nigerianewsnetwork.com<?php echo " : $categoryName"; ?></title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/global.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/businessLook.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/scrollingCtrl.css">
<script type="text/javascript">
	<?php 
		$jsPrefix = $prefix;
	?>
	var prefix = <?php echo "'$jsPrefix'";?>;
</script>
<script type="text/javascript" src="<?php echo $prefix; ?>scripts/jQuery.js"></script>
<script type="text/javascript" src="<?php echo $prefix; ?>scripts/scrollingCtrl.js"></script>

<body>
<div id="pageContainter" align="center">
<?php
	require_once($prefix . "includes/header.php");
?>
	<?php require_once($prefix . "includes/headerBasePanel.php"); ?>
	<div id="pageContentHolder">
		<div id="pageContentMainBlock">
			<?php require_once($prefix . "includes/sportsLookHeaderBasePanel.php"); ?>
			<?php require_once($prefix . "includes/scrollingNewsControl.php"); ?>
			<?php
				//query for top story.
				$sql = sprintf("SELECT Heading, TextContent, ArticleGenID, SCGenID FROM articles WHERE TopStory=1 AND SCGenID IN(SELECT SCGenID FROM subcategory WHERE CatGenID='%s') AND PublishDate<>'0000-00-00'", PAGE_NAME);
				
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
					<div id="topStoriesContent">
						<div id="topStoriesBigScreenHolder">
							<div id="topStoriesBigScreen">
								<a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo PAGE_NAME;?>&sc=<?php echo $topstoryArticleRow['SCGenID'];?>&article=<?php echo $topstoryArticleRow['ArticleGenID'];?>"><img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $topstoryPhotoRow['ArtPhotoID'];?>" /></a>
							</div>
					  </div>
						<div id="topStoriesThumbnailHolder">
							<div class="topStoriesHeading"><?php echo $topstoryArticleRow['Heading']; ?></div>
							<div class="topStoriesStoryEx"><?php echo substr($topstoryArticleRow['TextContent'], 0, 146) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo PAGE_NAME;?>&sc=<?php echo $topstoryArticleRow['SCGenID'];?>&article=<?php echo $topstoryArticleRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
						</div>
						<div style="clear:both"></div>
					</div>
				</div>
				
				<div class="longStripHolder" style="width:100%; height:auto">
					<?php
						$readArticle = $topstoryArticleRow['ArticleGenID'];
						$topstoryResult->data_seek(0);
						$readCount = 0;
						for (; ($topstoryArticleRow = $topstoryResult->fetch_array()) != FALSE; )
						{
							if ($readArticle == $topstoryArticleRow['ArticleGenID'])
								continue;
							
							if ($readCount == 2)
								break;
							
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
							}
						
							$topstoryPhotoRow = $topstoryPhotoResult->fetch_array();
					?>
					<div class="lSThumbAndTextHolder">
						<div class="lSThumbnailHolder">
							<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $topstoryPhotoRow['ArtPhotoID'];?>" />
						</div>
						
						<div class="lsTextHeading"><?php echo $topstoryArticleRow['Heading']; ?></div>
						<div class="topStoriesStoryEx"><?php echo substr($topstoryArticleRow['TextContent'], 0, 130) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo PAGE_NAME;?>&sc=<?php echo $topstoryArticleRow['SCGenID'];?>&article=<?php echo $topstoryArticleRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
					</div>
					<?php
							$topStorysCollection[count($topStorysCollection)] = "'" . $topstoryArticleRow['ArticleGenID'] . "'"; //adding to collection
							$readCount++;
						}
					?>
					<div style="clear:both;"></div>
					
					<?php
						//draw next 2 if we have more
						$readArticle = $topstoryArticleRow['ArticleGenID'];
						$readCount = 0;
						for (; ($topstoryArticleRow = $topstoryResult->fetch_array()) != FALSE; )
						{
							if ($readArticle == $topstoryArticleRow['ArticleGenID'])
								continue;
							
							if ($readCount == 2)
								break;
							
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
							}
						
							$topstoryPhotoRow = $topstoryPhotoResult->fetch_array();
					?>
					<div class="lSThumbAndTextHolder">
						<div class="lSThumbnailHolder">
							<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $topstoryPhotoRow['ArtPhotoID'];?>" />
						</div>
						
						<div class="lsTextHeading"><?php echo $topstoryArticleRow['Heading']; ?></div>
						<div class="topStoriesStoryEx"><?php echo substr($topstoryArticleRow['TextContent'], 0, 130) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo PAGE_NAME;?>&sc=<?php echo $topstoryArticleRow['SCGenID'];?>&article=<?php echo $topstoryArticleRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
					</div>
					<?php
							$topStorysCollection[count($topStorysCollection)] = "'" . $topstoryArticleRow['ArticleGenID'] . "'"; //adding to collection
							$readCount++;
						}
					?>
				</div>
				<div style="clear:both;"></div>
				
				<!-- Below top stories mid sections-->
				<div style="height:auto; width:100%;">
				<div id="midLeftColumn">
				<?php
					//get collection of displayed articles to string.
					$listOfDisplayedArticles = implode(",", $topStorysCollection);
					
					//script to get other articles from this category.
					$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE ArticleGenID NOT IN(%s) AND SCGenID IN(SELECT SCGenID FROM subcategory WHERE CatGenID='%s') AND PublishDate<>'0000-00-00' ORDER BY PublishDate DESC", $listOfDisplayedArticles, $category_id);
					

					$otherArticlesResult = $dbConn->query($sql);
					?>
					<?php
							if ($otherArticlesResult && $otherArticlesResult->num_rows > 0)
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
					<div id="midLNews2" class="midLNewsSections">
						<label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label>
						
						<div class="midNewsSectionsImageHolder">
							<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
						</div>
						
						<p/>
						<span class="midNewsExText">
							<?php echo substr($otherArticlesRow['TextContent'], 0, 247) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $otherArticlesRow['CatGenID'];?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
						</span>
					</div>
					<?php
							}
					?>
					
					<?php
							if ($otherArticlesResult && $otherArticlesResult->num_rows > 0)
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
							<?php echo substr($otherArticlesRow['TextContent'], 0, 120) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $otherArticlesRow['CatGenID'];?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
						</span>
						
						<!-- related stories -->
						<?php
								$sql = sprintf("SELECT ar.ARTID , art.AID, art.Heading, art.ArticleGenID, art.Heading, art.Author, art.PublishDate, art.SCGenID, sc.CatGenID FROM articlerelated AS ar JOIN articles AS art ON art.ArticleGenID=ar.RelatedArticleGenID AND ar.ParentArticleGenID='%s' JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID ORDER BY art.PublishDate DESC, art.Heading ASC LIMIT 3", $otherArticlesRow['ArticleGenID']);
								$osRelatedResult = $dbConn->query($sql);
								if ($osRelatedResult && $osRelatedResult->num_rows > 0)
								{
							?>
						<div class="moreLinksHolder">
							<ul class="moreLinks">
								<?php
										for (; ($osRelatedRow = $osRelatedResult->fetch_array()) != FALSE;)
										{
									?>
										<li><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $osRelatedRow['CatGenID'];?>&sc=<?php echo $osRelatedRow['SCGenID'];?>&article=<?php echo $osRelatedRow['ArticleGenID'];?>"><?php echo substr($osRelatedRow['Heading'], 0, 40);?></a></li>
									<?php
										}
									?>
							</ul>
						</div>
						<?php
								}
						?>
					</div>
					<?php
							}
					?>
					
				  <?php include("../includes/connectToUsCtrl.php");?>
				</div>
				
				<div id="midRightColumn">
				<?php
						//script for this section in sportsLook.php template
						if ($dbConn)
						{
							//get section.
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
											$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE ArticleGenID<>'%s' AND art.SCGenID='%s' AND PublishDate<>'0000-00-00' ORDER BY PublishDate DESC", $topstoryArticleRow['ArticleGenID'], $details['subcategoryLink']);
											$sectionResult = $dbConn->query($sql);
											if ($sectionResult && $sectionResult->num_rows > 0)
											{
					?>
				  <div id="midRNewsBottomSection"> <!-- section 1 -->
						<div class="sectionHeading" id="midBottomHeading">
							<div style="font-size:17px; padding-left:5px; margin-left:0px; margin-top:5px; background-color:#F0F0F0; height:32px; width:422px; margin-bottom:5px;"><div style="padding-top:8px;"><?php echo $details['heading']; ?></div></div>
						</div>
						<!-- section tumbnails and news -->
						<?php
								$sectionRow = $sectionResult->fetch_array();
								if ($sectionRow)
								{	
						?>
						<div class="moreSportsNewsContainer" id="mSNC1">
							<?php
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
							<div class="moreSnipetHolder moreSnipetHolderL">
								<div class="mSImageHolder"><img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" /></div>
								<div class="mSImageHeading">
									<?php echo $sectionRow['Heading']; ?>
								</div>
								<div class="mSEx">
									<label>Football:</label> <?php echo substr($sectionRow['TextContent'], 0, 98) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID'];?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
								</div>
							</div>
							
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
							<div class="moreSnipetHolder moreSnipetHolderR">
								<div class="mSImageHolder"><img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" /></div>
								<div class="mSImageHeading">
									<?php echo $sectionRow['Heading']; ?>
								</div>
								<div class="mSEx">
									<label>Football:</label> <?php echo substr($sectionRow['TextContent'], 0, 98) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID'];?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
								</div>
							</div>
							<?php
								}
							?>
							
							<div style="clear:both; height:3px"></div>
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
						<div class="moreSportsNewsContainer" id="mSNC2" style="border:0px">
						<?php
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
							<div class="moreSnipetHolder moreSnipetHolderL">
								<div class="mSImageHolder"><img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" /></div>
								<div class="mSImageHeading">
									<?php echo $sectionRow['Heading']; ?>
								</div>
								<div class="mSEx">
									<label>Football:</label> <?php echo substr($sectionRow['TextContent'], 0, 98) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID'];?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
								</div>
							</div>
							
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
							<div class="moreSnipetHolder moreSnipetHolderR">
								<div class="mSImageHolder"><img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" /></div>
								<div class="mSImageHeading">
									<?php echo $sectionRow['Heading']; ?>
								</div>
								<div class="mSEx">
									<label>Football:</label> <?php echo substr($sectionRow['TextContent'], 0, 98) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID'];?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
								</div>
							</div>
							<?php
								}
							?>
							
							<div style="clear:both; height:3px"></div>
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
						<div class="moreSportsNewsContainer" id="mSNC3" style="border-bottom:0px; border-top:1px solid #D1D1D1; height:160px">
						<?php
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
							<div class="moreSnipetHolder moreSnipetHolderL">
								<div class="mSImageHeading">
									<?php echo $sectionRow['Heading']; ?>
								</div>
								<div class="mSEx">
									<label>Football:</label> <?php echo substr($sectionRow['TextContent'], 0, 98) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID'];?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
								</div>
								
								<!-- related stories -->
						<?php
								$sql = sprintf("SELECT ar.ARTID , art.AID, art.Heading, art.ArticleGenID, art.Heading, art.Author, art.PublishDate, art.SCGenID, sc.CatGenID FROM articlerelated AS ar JOIN articles AS art ON art.ArticleGenID=ar.RelatedArticleGenID AND ar.ParentArticleGenID='%s' JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID ORDER BY art.PublishDate DESC, art.Heading ASC LIMIT 3", $sectionRow['ArticleGenID']);
								$osRelatedResult = $dbConn->query($sql);
								if ($osRelatedResult && $osRelatedResult->num_rows > 0)
								{
							?>
						<div class="moreLinksHolder">
							<ul class="moreLinks">
								<?php
										for (; ($osRelatedRow = $osRelatedResult->fetch_array()) != FALSE;)
										{
									?>
										<li><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $osRelatedRow['CatGenID'];?>&sc=<?php echo $osRelatedRow['SCGenID'];?>&article=<?php echo $osRelatedRow['ArticleGenID'];?>"><?php echo substr($osRelatedRow['Heading'], 0, 40);?></a></li>
									<?php
										}
									?>
							</ul>
						</div>
						<?php
								}
						?>
							</div>
							
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
							<div class="moreSnipetHolder moreSnipetHolderR">
								<div class="mSImageHeading">
									<?php echo $sectionRow['Heading']; ?>
								</div>
								<div class="mSEx">
									<label>Football:</label> <?php echo substr($sectionRow['TextContent'], 0, 98) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID'];?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
								</div>
								
								<!-- related stories -->
						<?php
								$sql = sprintf("SELECT ar.ARTID , art.AID, art.Heading, art.ArticleGenID, art.Heading, art.Author, art.PublishDate, art.SCGenID, sc.CatGenID FROM articlerelated AS ar JOIN articles AS art ON art.ArticleGenID=ar.RelatedArticleGenID AND ar.ParentArticleGenID='%s' JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID ORDER BY art.PublishDate DESC, art.Heading ASC LIMIT 3", $sectionRow['ArticleGenID']);
								$osRelatedResult = $dbConn->query($sql);
								if ($osRelatedResult && $osRelatedResult->num_rows > 0)
								{
							?>
						<div class="moreLinksHolder">
							<ul class="moreLinks">
								<?php
										for (; ($osRelatedRow = $osRelatedResult->fetch_array()) != FALSE;)
										{
									?>
										<li><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $osRelatedRow['CatGenID'];?>&sc=<?php echo $osRelatedRow['SCGenID'];?>&article=<?php echo $osRelatedRow['ArticleGenID'];?>"><?php echo substr($osRelatedRow['Heading'], 0, 40);?></a></li>
									<?php
										}
									?>
							</ul>
						</div>
						<?php
								}
						?>
							</div>
							<?php
							}
							?>
						</div>
						<?php
							}
						?>
						
				  </div>
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
				<?php include($prefix . "includes/adsSquare.php"); ?>
				
				<!--on the news section -->
				
				<div id="onTheNewsSection">
										<div class="sectionHeading" id="RMidHeading" style="height:20px">
						<div style="margin-left:0px; margin-top:5px; margin-left:5px; font-size:14px;">Last 24 Hours On <?php echo ucfirst($categoryName);?></div>
					</div>
					<!--
					<div id="ontheNewsTabHolder">
						<ul id="tabs">
							<li class="onTheNewsInactiveTab"><?php if ($onTheNewsTabSel != ON_THE_NEWS_MOST_VIEWED){ ?><a href="#"><?php } ?>Most Viewed<?php if ($onTheNewsTabSel != ON_THE_NEWS_MOST_VIEWED){ ?></a><?php } ?></li>
							<li class="onTheNewsActiveTab"><?php if ($onTheNewsTabSel != ON_THE_NEWS_LAST_24_HOURS){ ?><a href="#"><?php } ?>Last 24 Hours<?php if ($onTheNewsTabSel != ON_THE_NEWS_LAST_24_HOURS){ ?></a><?php } ?></li>
						</ul>
					</div>-->
					<?php
						//get last 24 hours articles for this category.
						if ($dbConn)
						{
							$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE art.SCGenID IN(SELECT SCGenID FROM subcategory WHERE CatGenID='%s') AND PublishDate<>'0000-00-00' AND (PublishDate + INTERVAL (3600 * 24) SECOND) >= NOW() ORDER BY ViewCount DESC, PublishDate DESC, AID DESC", PAGE_NAME);
							
							$l24HoursArticlesResult = $dbConn->query($sql);
							if ($l24HoursArticlesResult && $l24HoursArticlesResult->num_rows > 0)
							{
					?>
						<?php
								for ($i = 0; $i < 3; $i++)
								{
									if ($l24HoursRow = $l24HoursArticlesResult->fetch_array())
									{
										if ($l24HoursRow)
										{
											$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $l24HoursRow['ArticleGenID']);
											$l24HoursArticlesPhotoResult = $dbConn->query($sql);
											if ($l24HoursArticlesPhotoResult && $l24HoursArticlesPhotoResult->num_rows > 0)
											{
												//do a random select for a photo if we have more than 1.
												if ($l24HoursArticlesPhotoResult->num_rows > 1)
												{
													$selectPhoto = rand(0, $l24HoursArticlesPhotoResult->num_rows - 1);
													$l24HoursArticlesPhotoResult->data_seek($selectPhoto);
												}
						
												$l24HoursArticlesPhotoRow = $l24HoursArticlesPhotoResult->fetch_array();
											}
							?>
					<div class="otnSnipetsHolder">
						<div class="otnThumbnail">
							<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $l24HoursArticlesPhotoRow['ArtPhotoID'];?>" />
						</div>
						<div class="otnThumbEx">
							<?php echo trim(substr($l24HoursRow['TextContent'], 0, 70)) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $l24HoursRow['CatGenID'];?>&sc=<?php echo $l24HoursRow['SCGenID'];?>&article=<?php echo $l24HoursRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
						</div>
					</div>
							<?php
										}
									}
									else
										break;
									
								}
							?>
						
							<?php
								for ($i = 0; $i < 7; $i++)
								{
									if ($l24HoursRow = $l24HoursArticlesResult->fetch_array())
									{
										if ($l24HoursRow)
										{
											/*
											$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $l24HoursRow['ArticleGenID']);
											$l24HoursArticlesPhotoResult = $dbConn->query($sql);
											if ($l24HoursArticlesPhotoResult && $l24HoursArticlesPhotoResult->num_rows > 0)
											{
												//do a random select for a photo if we have more than 1.
												if ($l24HoursArticlesPhotoResult->num_rows > 1)
												{
													$selectPhoto = rand(0, $l24HoursArticlesPhotoResult->num_rows - 1);
													$l24HoursArticlesPhotoResult->data_seek($selectPhoto);
												}
						
												$l24HoursArticlesPhotoRow = $l24HoursArticlesPhotoResult->fetch_array();
											}
											*/
									
							?>
					<div class="otnSnipetsHolder otnNoThumbEx" style="margin-top:0px; width:100%">
						<div class="otnNoThumbEx">
							<?php echo substr($l24HoursRow['TextContent'], 0, 70) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $l24HoursRow['CatGenID'];?>&sc=<?php echo $l24HoursRow['SCGenID'];?>&article=<?php echo $l24HoursRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
						</div>
					</div>
							<?php
										}
									}
									else
										break;
								}
							?>
					<?php
							}
						}
					?>
				</div>
				
				<!--business around the world section  is section 2 -->
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
			
			<?php
				require_once($prefix . "includes/bottomSection.php");
			?>
		</div>
	</div>

<?php
	require_once($prefix . "includes/footer.php");
?>
</body>
</html>
