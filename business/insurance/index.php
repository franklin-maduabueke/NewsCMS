<?php define('PAGE_NAME', 'Ib5P2DsM'); ?>
<?php
	define("SUBCAT", "subcategory");
	
	$prefix = "";
	if (defined("SUBCAT"))
		$prefix = "../../";
		
	require_once($prefix . "config/db.php");
	require_once($prefix . "modules/mysqli.php");
	require_once($prefix . "includes/commons.php");
	require_once($prefix . "modules/lookConfigure.php");
	require_once($prefix . "modules/adsLauncher.php");
		
	$result = NULL;
	
	//the id of the category this template will use for getting news.
	$categoryId = $_GET['sel'];
	$categoryName = NULL;
	$subCatName = NULL;
	
	//folder name.
	$pageFolderName = NULL;
	$looknfeelName = NULL;
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$sql = sprintf("SELECT sc.SubCatName, sc.CatGenID, cat.CategoryName FROM Subcategory AS sc JOIN Category AS cat ON sc.CatGenID=cat.CatGenID WHERE sc.SCGenID='%s'", $_GET['sc']);
		
		$result = $dbConn->query($sql);
		
		if ($result && $result->num_rows > 0)
		{
			$scRow = $result->fetch_array();
			$categoryName = $scRow['CategoryName'];
			$subCatName = $scRow['SubCatName'];
			
			$sql = sprintf("SELECT WebFolderName, WebLooknFeelName FROM Subcategory WHERE SCGenID='%s'", PAGE_NAME);
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
		if (PAGE_NAME == "home")
			header("Location: unavailable.php"); //redirect to this page when db is offline.
		else
			header("Location: ". $prefix . "unavailable.php");
			
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
<title>www.nnn.com : <?php echo $categoryName . " > " . $subCatName; ?></title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/global.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/newsSubLook.css">
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
				$sql = sprintf("SELECT Heading, TextContent, ArticleGenID, SCGenID FROM Articles WHERE TopStory=1 AND PublishDate<>'0000-00-00' AND SCGenID='%s' ORDER BY PublishDate DESC, AID DESC", PAGE_NAME);
				
				$topstoryResult = $dbConn->query($sql);
				$topstoryArticleRow = NULL;
				$topstoryPhotoRow = NULL;
				
				if ($topstoryResult && $topstoryResult->num_rows > 0)
				{
					//randomly select a top story to view.
					$selectArticle = rand(0, $topstoryResult->num_rows - 1);
					$topstoryResult->data_seek($selectArticle);
					$topstoryArticleRow = $topstoryResult->fetch_array();
					
					$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $topstoryArticleRow['ArticleGenID']);
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
				}
			?>
			<div id="pageLeftColumn">
				<div id="topStoriesSection">
				  <div id="topStoriesContent">
						<div id="topStoriesBigScreenHolder">
							<div id="topStoriesBigScreen">
								<a class="linkImage" href="readArticle.php?sel=<?php echo $categoryId;?>&sc=<?php echo $topstoryArticleRow['SCGenID'];?>&article=<?php echo $topstoryArticleRow['ArticleGenID'];?>"><img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $topstoryPhotoRow['ArtPhotoID'];?>" /></a>
							</div>
					  </div>
						<div id="topStoriesThumbnailHolder">
							<div class="topStoriesHeading"><?php echo $topstoryArticleRow['Heading']; ?></div>
							<div class="topStoriesStoryEx"><?php echo substr($topstoryArticleRow['TextContent'], 0, 200) . "...";?></div>
							
							<div style="margin-top:20px; text-align:left">
								<img src="<?php echo $prefix; ?>images/commentIcon.jpg" style="width:auto; height:auto" />
								<a href="readArticle.php?sel=<?php echo $categoryId;?>&sc=<?php echo $topstoryArticleRow['SCGenID'];?>&article=<?php echo $topstoryArticleRow['ArticleGenID'];?>" style="color:#E10000; font-size:13px; margin-left:5px; text-decoration:none">
								<?php 
									$articleCommentCountResult = $dbConn->query( sprintf("SELECT COUNT(CommentID) AS CommentCount FROM Comments WHERE ArticleGenID='%s'", $topstoryArticleRow['ArticleGenID']) );
									$articleCommentCount = $articleCommentCountResult->fetch_array();
									echo $articleCommentCount['CommentCount'];
								?> comments
								</a>
							</div>
						</div>
						<div style="clear:both; height:1px;"></div>
				  </div>
				</div>
				
				
				<?php
					//script to get other articles from this category.
					$sql = sprintf("SELECT Heading, TextContent, ArticleGenID, SCGenID FROM Articles WHERE ArticleGenID<>'%s' AND SCGenID='%s' AND PublishDate<>'0000-00-00' ORDER BY PublishDate DESC, AID DESC", $topstoryArticleRow['ArticleGenID'], PAGE_NAME);
					
					$otherArticlesResult = $dbConn->query($sql);
					?>
					<?php
							$otherArticlesRow = $otherArticlesResult->fetch_array();
							if ($otherArticlesRow)
							{
								
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
								}
				?>
				<div class="longStripHolder">
					<div class="lSThumbnailHolder">
						<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
					</div>
					
					<div class="lSTextHolder">
						<div class="lsTextHeading"><?php echo substr($otherArticlesRow['Heading'], 0, 40) . "..."; ?></div>
						
						<div class="lsTextNewsEx"><?php echo substr($otherArticlesRow['TextContent'], 0, 268) . "...";?><a href="readArticle.php?sel=<?php echo $categoryId;?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
					</div>
				</div>
				<?php
					}
				?>
				
				<?php
							$otherArticlesRow = $otherArticlesResult->fetch_array();
							if ($otherArticlesRow)
							{
								
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
								}
				?>
				<div class="longStripHolder">
					<div class="lSThumbnailHolder">
						<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
					</div>
					
					<div class="lSTextHolder">
						<div class="lsTextHeading"><?php echo substr($otherArticlesRow['Heading'], 0, 40) . "..."; ?></div>
						<div class="lsTextNewsEx"><?php echo substr($otherArticlesRow['TextContent'], 0, 268) . "...";?><a href="readArticle.php?sel=<?php echo $categoryId;?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>

					</div>
				</div>
				<?php
					}
				?>
				
				<!-- Below top stories mid sections-->
				<div id="midLeftColumn">
				<?php
					//script to get other articles from this category.
					/*
					$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM Articles AS art JOIN Subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE ArticleGenID<>'%s' AND PublishDate<>'0000-00-00' AND art.SCGenID='%s' ORDER BY PublishDate DESC", $topstoryArticleRow['ArticleGenID'], PAGE_NAME);
					
					$otherArticlesResult = $dbConn->query($sql);
					*/
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
<a href="<?php if (PAGE_NAME != "home") echo "readArticle.php?"; else echo $prefix . "processing/redirectRead.php?"?><?php echo "sel=".$otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
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
					<div id="midLNews2" class="midLNewsSections" style="border:0px">
						<label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label>
						<p/>
						<span class="midNewsExText">
							<?php echo substr($otherArticlesRow['TextContent'], 0, 247) . "...";?>
<a href="<?php if (PAGE_NAME != "home") echo "readArticle.php?"; else echo $prefix . "processing/redirectRead.php?"?><?php echo "sel=".$otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
						</span>
					</div>
					<?php
						}
					?>
					
					<?php include($prefix."includes/connectToUsCtrl.php");?>
				</div>
				
				<div id="midRightColumn">
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
								}
								*/
					?>
				  <div id="midRNewsNoImage1" class="midRNews" style="border:0px">
						<div style="text-align:left; margin-top:10px; width:360px"><label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label></div>
						<div id="midRNewsNoImage1Tex" style="margin-top:8px; margin-bottom:18px">
							<span class="midNewsExText"><?php echo substr($otherArticlesRow['TextContent'], 0, 402) . "...";?>
<a href="<?php if (PAGE_NAME != "home") echo "readArticle.php?"; else echo $prefix . "processing/redirectRead.php?"?><?php echo "sel=".$otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a></span>
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
					<div id="midRNewsNoImage2" class="midRNews" style="height:130px;">
						<div style="text-align:left; margin-top:10px; width:360px"><label class="midNewsSectionsHeading"><?php echo $otherArticlesRow['Heading']; ?></label></div>
						<div id="midRNewsNoImage2Tex" style="margin-top:8px;">
							<span class="midNewsExText"><?php echo substr($otherArticlesRow['TextContent'], 0, 247) . "...";?>
<a href="<?php if (PAGE_NAME != "home") echo "readArticle.php?"; else echo $prefix. "processing/redirectRead.php?"?><?php echo "sel=".$otherArticlesRow['CatGenID']."&sc=".$otherArticlesRow['SCGenID']."&article=".$otherArticlesRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
							</span>						
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
								$pageFolderName = "xmls/template";
							
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
											//get artilce from group or from section.
											$articleGroup = $lookConf->getLooknFeelSectionGroup($section);
											
											if ($articleGroup)
											{
												$sql = sprintf("SELECT grp.GroupGenID, art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID FROM GroupArticle AS grp JOIN Articles AS art ON grp.ArticleGenID=art.ArticleGenID WHERE grp.GroupGenID='%s' AND art.PublishDate<>'0000-00-00' ORDER BY PublishDate DESC", trim($articleGroup->textContent));
											}
											else
											{
												$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM Articles AS art JOIN Subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE art.ArticleGenID<>'%s' AND art.SCGenID='%s' AND art.PublishDate<>'0000-00-00' ORDER BY PublishDate DESC", $topstoryArticleRow['ArticleGenID'], $details['subcategoryLink']);
											}
											
											$sectionResult = $dbConn->query($sql);
											
											if ($sectionResult && $sectionResult->num_rows > 0)
											{
												
					?>
					<div id="midRNewsBottomSection">
						<div class="sectionHeading" id="midBottomHeading">
							<div style="font-size:17px; padding-left:5px; margin-left:0px; margin-top:5px; background-color:#F0F0F0; height:32px; width:422px; margin-bottom:5px"><?php echo $details['heading']; ?></div>
						</div>
						<!-- section tumbnails and news -->
						<?php
							$readCount = 0;
							for (; ($sectionRow = $sectionResult->fetch_array()) != FALSE; )
							{
								if ($readCount == 3)
									break;

								if ($sectionRow)
								{
									$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $sectionRow['ArticleGenID']);
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
								<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
							
							<div class="midBottomTextHolder">
								<div style="text-align:left; width:243px;margin-bottom:5px"><label class="midNewsSectionsHeading"><?php echo $sectionRow['Heading'];?></label></div>
								<span class="midNewsExText"><?php echo substr($sectionRow['TextContent'], 0, 90) . "...";?>
<a href="<?php echo $prefix; ?>processing/redirectToRead.php?sel=<?php echo $sectionRow['CatGenID']."&sc=".$sectionRow['SCGenID']."&article=".$sectionRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a></span>
							</div>
						</div>
						<?php
								}
								$readCount++;
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
							$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM Articles AS art JOIN Subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE art.SCGenID IN(SELECT SCGenID FROM Subcategory WHERE CatGenID='%s') AND PublishDate<>'0000-00-00' AND (PublishDate + INTERVAL (3600 * 24) SECOND) >= NOW() ORDER BY ViewCount DESC, PublishDate DESC, AID DESC", $categoryId);
							
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
							?>
					<div class="otnSnipetsHolder">
						<div class="otnThumbnail">
							<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $l24HoursArticlesPhotoRow['ArtPhotoID'];?>" />
						</div>
						<div class="otnThumbEx">
							<?php echo trim(substr($l24HoursRow['TextContent'], 0, 70)) . "...";?><a href="readArticle.php?sel=<?php echo $l24HoursRow['CatGenID'];?>&sc=<?php echo $l24HoursRow['SCGenID'];?>&article=<?php echo $l24HoursRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
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
							<?php echo substr($l24HoursRow['TextContent'], 0, 70) . "...";?><a href="readArticle.php?sel=<?php echo $l24HoursRow['CatGenID'];?>&sc=<?php echo $l24HoursRow['SCGenID'];?>&article=<?php echo $l24HoursRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
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
				
				<!--business around the world section -->
				<div id="businessAroundTheWorldSection">
				  <?php include($prefix."includes/pollBoot.php"); ?>
				</div>
			</div>
			<?php require_once($prefix . "includes/bottomSection.php");?>
	  </div>
	</div>

<?php
	require_once($prefix . "includes/footer.php");
?>
</body>
</html>