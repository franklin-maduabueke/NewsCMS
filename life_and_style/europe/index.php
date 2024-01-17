<?php define('PAGE_NAME', 'bPmTU0wy'); ?>
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
<title>www.nnn.com<?php echo " : $categoryName"; ?></title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/global.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/life_and_styleSubcatLook.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/scrollingCtrl.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/adsSquare.css" />

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
						<div id="topStoriesBigScreenHeading"><?php echo $topstoryArticleRow['Heading']; ?></div>
						
						<div id="topStoriesBigScreenHolder">
							<div id="topStoriesBigScreen">
								<a class="linkImage" href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $topstoryArticleRow['SCGenID'];?>&article=<?php echo $topstoryArticleRow['ArticleGenID'];?>"><img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $topstoryPhotoRow['ArtPhotoID'];?>" /></a>
							</div>
					   </div>
					   <div id="topStoriesThumbnailHolder">
							<div class="topStoriesStoryEx"><?php echo substr($topstoryArticleRow['TextContent'], 0, 200) . "...";?></div>
							
							<!-- use this related stories -->
							<?php
								$sql = sprintf("SELECT ar.ARTID , art.AID, art.Heading, art.ArticleGenID, art.Heading, art.Author, art.PublishDate, art.SCGenID, sc.CatGenID FROM ArticleRelated AS ar JOIN Articles AS art ON art.ArticleGenID=ar.RelatedArticleGenID AND ar.ParentArticleGenID='%s' JOIN Subcategory AS sc ON sc.SCGenID=art.SCGenID ORDER BY art.PublishDate DESC, art.Heading ASC LIMIT 3", $topstoryArticleRow['ArticleGenID']);
								$tsRelatedResult = $dbConn->query($sql);
								if ($tsRelatedResult && $tsRelatedResult->num_rows > 0)
								{
							?>
							<div id="topStoriesMoreLinkHolder">
								<ul>
									<?php
										for (; ($tsRelatedRow = $tsRelatedResult->fetch_array()) != FALSE;)
										{
									?>
										<li><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $tsRelatedRow['CatGenID'];?>&sc=<?php echo $tsRelatedRow['SCGenID'];?>&article=<?php echo $tsRelatedRow['ArticleGenID'];?>"><?php echo substr($tsRelatedRow['Heading'], 0, 20);?></a></li>
									<?php
										}
									?>
								</ul>
							</div>
							<?php
								}
							?>
					   </div>
						
					   <div style="clear:both; height:11px"></div>
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
					<?php
						if ($otherArticlesRow)
						{
					?>
					<div class="lsSnipetHolder">
						<div class="lSThumbnailHolder">
							<div class="imageSqueeze">
								<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
						</div>
					
						<div class="lSTextHolder">
							<div class="lsTextHeading"><?php echo substr($otherArticlesRow['Heading'], 0, 40) . "..."; ?></div>
						
							<div class="lsTextNewsEx"><?php echo substr($otherArticlesRow['TextContent'], 0, 110) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
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
					<div class="lsSnipetHolder">
						<div class="lSThumbnailHolder">
							<div class="imageSqueeze">
								<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
						</div>
					
						<div class="lSTextHolder">
							<div class="lsTextHeading"><?php echo substr($otherArticlesRow['Heading'], 0, 40) . "..."; ?></div>
						
							<div class="lsTextNewsEx"><?php echo substr($otherArticlesRow['TextContent'], 0, 110) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
						</div>
					</div>
					<?php
						}
					?>
					
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
					<?php
						if ($otherArticlesRow)
						{
					?>
				<div class="longStripHolder">
					<div class="lsSnipetHolder">
						<div class="lSThumbnailHolder">
							<div class="imageSqueeze">
								<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
						</div>
					
						<div class="lSTextHolder">
							<div class="lsTextHeading"><?php echo substr($otherArticlesRow['Heading'], 0, 40) . "..."; ?></div>
						
							<div class="lsTextNewsEx"><?php echo substr($otherArticlesRow['TextContent'], 0, 110) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
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
					<div class="lsSnipetHolder">
						<div class="lSThumbnailHolder">
							<div class="imageSqueeze">
								<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
						</div>
					
						<div class="lSTextHolder">
							<div class="lsTextHeading"><?php echo substr($otherArticlesRow['Heading'], 0, 40) . "..."; ?></div>
						
							<div class="lsTextNewsEx"><?php echo substr($otherArticlesRow['TextContent'], 0, 110) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
						</div>
					</div>
					<?php
						}
					?>
					
					
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
					<?php
						if ($otherArticlesRow)
						{
					?>
				<div class="longStripHolder" style="height:150px">
					<div class="lsSnipetHolder">
						<div class="lSThumbnailHolder">
							<div class="imageSqueeze">
								<img src="<?php if (PAGE_NAME == "home") echo "processing/fetch_article_photo.php?photoId="; else echo "../processing/fetch_article_photo.php?photoId=";?><?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
						</div>
					
						<div class="lSTextHolder">
							<div class="lsTextHeading"><?php echo substr($otherArticlesRow['Heading'], 0, 40) . "..."; ?></div>
						
							<div class="lsTextNewsEx"><?php echo substr($otherArticlesRow['TextContent'], 0, 110) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
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
					<div class="lsSnipetHolder">
						<div class="lSThumbnailHolder">
							<div class="imageSqueeze">
								<img src="<?php if (PAGE_NAME == "home") echo "processing/fetch_article_photo.php?photoId="; else echo "../processing/fetch_article_photo.php?photoId=";?><?php echo $otherArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
						</div>
					
						<div class="lSTextHolder">
							<div class="lsTextHeading"><?php echo substr($otherArticlesRow['Heading'], 0, 40) . "..."; ?></div>
						
							<div class="lsTextNewsEx"><?php echo substr($otherArticlesRow['TextContent'], 0, 110) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $otherArticlesRow['SCGenID'];?>&article=<?php echo $otherArticlesRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a></div>
						</div>
					</div>
					<?php
						}
					?>
					
					
				</div>
				<?php
							}
				?>
				
				
				
				<!-- sections for this template-->
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
											$sql = sprintf("SELECT Heading, TextContent, ArticleGenID, SCGenID FROM Articles WHERE ArticleGenID<>'%s' AND SCGenID='%s' AND PublishDate<>'0000-00-00' ORDER BY PublishDate DESC, AID DESC", $topstoryArticleRow['ArticleGenID'], $details['subcategoryLink']);
											$sectionResult = $dbConn->query($sql);
											if ($sectionResult && $sectionResult->num_rows > 0)
											{
												$sectionRow = $sectionResult->fetch_array();
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
				<div id="midLeftColumn">
					<!-- life and style content -->
					<div class="midContentHolder">
						<div class="midContentHeadingHolder">
							<div class="midContentHeading"><?php echo $details['heading']; ?></div>
						</div>
						<div class="midContentImageAndTextHolder">
							<div class="midContentImageHeading"><?php echo substr($sectionRow['Heading'], 0, 33); ?></div>
							
							<div class="midContentImageHolder">
								<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
						</div>
						
						<div class="midContentEx">
							<?php echo substr($sectionRow['TextContent'], 0, 121) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
						</div>
						
						<?php
								$sectionRow = $sectionResult->fetch_array();
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
						<div class="midContentImageHeading"><?php echo $sectionRow['Heading']; ?></div>
						<div class="midContentEx" style="border:none">
							<?php echo substr($sectionRow['TextContent'], 0, 121) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
						</div>
						<?php
								}
						?>
					</div>
				</div>
						<?php
												}
											}
										}
									}
								}
							}
						}
					?>
				
				
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
									$section = $lookConf->getLooknFeelSectionWithId("s2", $looknfeel);
									if ($section)
									{
										//get section details
										$details = $lookConf->getLooknFeelSectionDetails($section);
										if ($details)
										{
											//get artilce.
											$sql = sprintf("SELECT Heading, TextContent, ArticleGenID, SCGenID FROM Articles WHERE ArticleGenID<>'%s' AND SCGenID='%s' AND PublishDate<>'0000-00-00' ORDER BY PublishDate DESC, AID DESC", $topstoryArticleRow['ArticleGenID'], $details['subcategoryLink']);
											$sectionResult = $dbConn->query($sql);
											if ($sectionResult && $sectionResult->num_rows > 0)
											{
												$sectionRow = $sectionResult->fetch_array();
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
				<div id="midRightColumn">
					<div class="midContentHolder" style="margin-left:20px">
						<div class="midContentHeadingHolder">
							<div class="midContentHeading"><?php echo $details['heading']; ?></div>
						</div>
						<div class="midContentImageAndTextHolder">
							<div class="midContentImageHeading"><?php echo substr($sectionRow['Heading'], 0, 33); ?></div>
							
							<div class="midContentImageHolder">
								<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" />
							</div>
						</div>
						
						<div class="midContentEx">
							<?php echo substr($sectionRow['TextContent'], 0, 100) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
						</div>
						
						<!-- attachment -->
						<?php
								$sectionRow = $sectionResult->fetch_array();
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
						<div class="midContentImageHeading"><?php echo $sectionRow['Heading']; ?></div>
						<div class="midContentEx" style="border:none">
							<?php echo substr($sectionRow['TextContent'], 0, 121) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a>
						</div>
						<?php
								}
						?>
					</div>
				</div>
				<?php
												}
											}
										}
									}
								}
							}
						}
				?>
					
				<div style="clear:both; height:1px; border-bottom:1px solid #D1D1D1; margin-right:3px;"></div>
				
				<!-- range of subcategorys -->
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
									$section = $lookConf->getLooknFeelSectionWithId("s7", $looknfeel);
									if ($section)
									{
										//get section details
										$details = $lookConf->getLooknFeelSectionDetails($section);
										if ($details)
										{
					?>
				<div id="pageLeftColumnBottomHolder">
					<div id="pageLeftColumnBottomHeading"><?php echo $details['heading']; ?></div>
					
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
									$totalSection = $lookConf->countLooknFeelSections($looknfeel);
									for ($i = 3; $i < $totalSection; $i++) //minus 3 cause we have rendered 1,2,7...left with 3, 4, 5, 6
									{
										$section = $lookConf->getLooknFeelSectionWithId("s" . $i, $looknfeel);
										if ($section)
										{
											//get section details
											$details = $lookConf->getLooknFeelSectionDetails($section);
											if ($details)
											{
												//get artilce.
												$sql = sprintf("SELECT Heading, TextContent, ArticleGenID, SCGenID FROM Articles WHERE ArticleGenID<>'%s' AND SCGenID='%s' AND PublishDate<>'0000-00-00' ORDER BY PublishDate DESC, AID DESC", $topstoryArticleRow['ArticleGenID'], $details['subcategoryLink']);
												$sectionResult = $dbConn->query($sql);
												if ($sectionResult && $sectionResult->num_rows > 0)
												{
													$sectionRow = $sectionResult->fetch_array();
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
					<div class="pLColSubCatContentHolder">
						<div class="pLColSubCatHeading"><?php echo $details['heading']; ?></div>
						
						<div class="plColSubCatImageHolder">
							<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $sectionArticlesPhotoRow['ArtPhotoID'];?>" />
						</div>
						
						<div class="plColSubCatArticleHeading">
							<?php echo $sectionRow['Heading']; ?>
						</div>
						
						<div class="plColSubCatArticleEx">
							<?php echo substr($sectionRow['TextContent'], 0, 118) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $sectionRow['SCGenID'];?>&article=<?php echo $sectionRow['ArticleGenID'];?>" class="readMoreLink">more &raquo;</a> 
						</div>
						
						<div class="plColSubCatArticleRelated"><a href="#">Barnes & Noble sued by Microsoft</a></div>
					</div>
					<?php
													}
												}
											}
										}
									}
								}
							}
						}
					?>
					
					<div style="clear:both; height:1px;"></div>
				</div>
				<?php
										}
									}
								}
							}
						}
				?>
				
			</div>
		
			<div id="pageRightColumn">
				<!-- include adverts getter with php -->
				<?php include($prefix . "includes/adsSquare.php");?>
				
				<!--on the news section -->
				<div id="onTheNewsSection">
				<!--
					<div class="sectionHeading" id="RMidHeading" style="height:20px">
						<div style="margin-left:0px; margin-top:5px; margin-left:5px">On <?php /*echo ucfirst($categoryName);*/ ?> Last 24 Hours</div>
					</div>
				-->
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
							$sql = sprintf("SELECT Heading, TextContent, ArticleGenID, SCGenID FROM Articles WHERE SCGenID IN(SELECT SCGenID FROM Subcategory WHERE CatGenID='%s') AND PublishDate<>'0000-00-00' AND (PublishDate + INTERVAL (3600 * 24) SECOND) >= NOW() ORDER BY ViewCount DESC, PublishDate DESC, AID DESC", PAGE_NAME);
							
							$l24HoursArticlesResult = $dbConn->query($sql);
					?>
						<?php
								for ($i = 0; $i < 6; $i++)
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
							
					<div class="otnSnipetsHolder" <?php if ($i == 5) echo 'style="border:0px"'; ?>>
						
						<div class="otnThumbnail">
							<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $l24HoursArticlesPhotoRow['ArtPhotoID'];?>" />
						</div>
						
						<div class="otnThumbnaildHeadAndExHolder">
							<div class="otnThumbnailHeading"><?php echo substr($l24HoursRow['Heading'], 0, 45);?></div>
							<div class="otnThumbEx">
								<?php echo trim(substr($l24HoursRow['TextContent'], 0, 70)) . "...";?><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $l24HoursRow['SCGenID'];?>&article=<?php echo $l24HoursRow['ArticleGenID'];?>" class="linkMore">more &raquo;</a>
							</div>
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
					?>
				</div>
				
				<!--business around the world section  is section 2 -->
					<?php include($prefix . "includes/pollBoot.php"); ?>
					<!-- include adverts gettter with php -->
					<?php include($prefix . "includes/adsSquare.php");?>
					
					<div style="text-align:left">
					<?php include($prefix . "includes/connectToUsCtrl.php");?>
					</div>
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
