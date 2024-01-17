<?php define('SUBCAT', 'subcategory'); ?><?php
	$prefix = "../";
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
	$subcategoryId = $_GET['sc'];
	$articleId = $_GET['article'];
	$categoryName = NULL;
	$scName = NULL;
	
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
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$sql = sprintf("SELECT CategoryName, CatGenID FROM Category WHERE CatGenID='%s'", $categoryId);
		$result = $dbConn->query($sql);
		
		if ($result && $result->num_rows > 0)
		{
			$catNameRow = $result->fetch_array();
			$categoryName = $catNameRow['CategoryName'];
		}
		
		//script to get other articles from this category.
		$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.PublishDate, art.SCGenID, sc.SubCatName FROM Articles AS art JOIN Subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE ArticleGenID='%s'", $articleId);
					
		$readArticlesResult = $dbConn->query($sql);
		$readArticlesRow = $readArticlesResult->fetch_array();
		
		if ($readArticlesRow)
		{
			//start rendering the article.
			//update view count for this article.
			$sql = sprintf("UPDATE Articles SET ViewCount=(ViewCount + 1) WHERE ArticleGenID='%s'", $articleId);
			$dbConn->query($sql);
			
			//count comments.
			$comments = 0;
			
			$commentCountResult = $dbConn->query(sprintf("SELECT COUNT(ArticleGenID) AS CommentCount FROM Comments WHERE ArticleGenID='%s'", $articleId));
			if ($commentCountResult)
			{
				$commentCountRow = $commentCountResult->fetch_array();
				$comments = $commentCountRow['CommentCount'];
			}
			
			$scName = $readArticlesRow['SubCatName'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>www.nnn.com</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/global.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/readArticle.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/scrollingCtrl.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/comments.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix;?>stylesheet/popper.css">

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
	<?php	
		require_once($prefix . "includes/headerBasePanel.php");
		require_once($prefix . "includes/newsLookHeader.php");
		require_once($prefix . "includes/newsLookHeaderBasePanel.php");
	?>
	<div id="pageContentHolder">
		<div id="pageContentMainBlock">
			<div id="pageLeftColumn" style="width:663px; border-right:1px solid #D1D1D1;">
				<div id="mainContentArea">
					<div id="newsArticleHeading">
						<?php echo $readArticlesRow['Heading']; ?>
					</div>
					<div id="publishDateHolder">
						<?php echo date("l, F j Y, H:i A", strtotime($readArticlesRow['PublishDate'])); ?>
					</div>
					<div id="commentCountHolder">
						<a style="color:#0099CC; font-size:14px; text-decoration:none;"><?php echo $comments; ?> Comments</a> 
					</div>
					<?php
						$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $readArticlesRow['ArticleGenID']);
						$readArticlesPhotoResult = $dbConn->query($sql);
						
						if ($readArticlesPhotoResult && $readArticlesPhotoResult->num_rows > 0)
						{
							//do a random select for a photo if we have more than 1.
							if ($readArticlesPhotoResult->num_rows > 1)
							{
								$selectPhoto = rand(0, $readArticlesPhotoResult->num_rows - 1);
								$readArticlesPhotoResult->data_seek($selectPhoto);
							}
						
							$readArticlesPhotoRow = $readArticlesPhotoResult->fetch_array();	
					?>
					<div id="newsImageHolder">
						<img src="<?php if (PAGE_NAME == "home") echo "processing/fetch_article_photo.php?photoId="; else echo $prefix . "processing/fetch_article_photo.php?photoId=";?><?php echo $readArticlesPhotoRow['ArtPhotoID'];?>" />
					</div>
					<?php
						}
					?>
					<a name="top"></a>
					<div id="opertaionsHolder">
						<ul>
							<li style="list-style-image:url(<?php echo $prefix;?>images/printIcon.jpg)"><a onclick="window.print()" class="clickable">Prnt</a></li>
							<li style="list-style-image:url(<?php echo $prefix;?>images/emailIcon.jpg)"><a id="emailFriend" class="clickable">Email to a Friend</a></li>
							<li style="list-style-image:url(<?php echo $prefix;?>images/shareIcon.jpg)"><a href="#">Share</a></li>
							<li style="list-style-image:url(<?php echo $prefix;?>images/commentIcon.jpg)"><a href="#makeComment">Comment</a></li>
						</ul>
					</div>
				  <div id="newsTextHolder">
				 		<?php
						 	echo $readArticlesRow['TextContent'];
						?>
						<a href="#top" style="color:#DF0000; font-size:12px; text-decoration:none">back to top &raquo;</a>
				  </div>
				</div>
				
				<div id="bannerContentArea">
					<div id="bannerContentHeaderHolder" style="text-align:left">
						<div style="color:#E10000; font-size:14px; margin-left:10px; padding-top:10px; font-weight:bold">
							RELATED NEWS
						</div>
					</div>
					<?php
						//display related articles.
						$sql = sprintf("SELECT ar.ARTID , art.AID, art.Heading, art.TextContent, art.ArticleGenID, art.Author, art.PublishDate, art.SCGenID FROM ArticleRelated AS ar JOIN Articles AS art ON art.ArticleGenID=ar.RelatedArticleGenID AND ar.ParentArticleGenID='%s'
ORDER BY art.ViewCount, art.PublishDate DESC, art.Heading ASC, art.AID LIMIT 3", $articleId);
						
						$relatedArticles = $dbConn->query($sql);
						if ($relatedArticles && $relatedArticles->num_rows > 0)
						{
							$imageDraw = FALSE; //stop checking for image when an article with image has been drawn
							for (; ($rArtRow = $relatedArticles->fetch_array()) != FALSE; )
							{
					?>
					<div id="relatedNewsWithImageHolder">
						<div class="relatedNewHeading">
							<?php echo $rArtRow['Heading']; ?>
						</div>
						<?php
								if ($rArtRow && !$imageDraw)
								{
									$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $rArtRow['ArticleGenID']);
									$rArticlesPhotoResult = $dbConn->query($sql);
									if ($rArticlesPhotoResult && $rArticlesPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($rArticlesPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $rArticlesPhotoResult->num_rows - 1);
											$rArticlesPhotoResult->data_seek($selectPhoto);
										}
						
										$rArticlesPhotoRow = $rArticlesPhotoResult->fetch_array();
										$imageDraw = TRUE;
									}
							?>
						<div id="relatedImageHolder">
							<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $rArticlesPhotoRow['ArtPhotoID'];?>" />
						</div>
							<?php

								}
							?>
						
						<div class="relatedNewsHolderEx">
							<?php echo substr($rArtRow['TextContent'], 0, 90) . "..."; ?>
<a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $categoryId;?>&sc=<?php echo $rArtRow['SCGenID'];?>&article=<?php echo $rArtRow['ArticleGenID'];?>" style="color:#DF0000; font-size:12px; text-decoration:none">full story &raquo;</a>
						</div>
					</div>
					<?php
							}
						}
					?>
					<div id="bannerAdvertHolder">
						<?php include_once($prefix . "includes/adsVertical.php");?>
					</div>
				</div>
				<div style="clear:both; height:0px;"></div>
				<?php require_once($prefix . "includes/comments.php"); ?>
			</div>
		
			<div id="pageRightColumn">
			
				<!--on the news section -->
				<div id="onTheNewsSection" class="" style="margin-top:0px;">
					<div class="sectionHeading" id="RMidHeading" style="height:20px">
						<div style="margin-left:0px; margin-top:5px;">Today On Nigeria News Network</div>
					</div>
					<div id="ontheNewsTabHolder">
						<ul id="tabs">
							<li class="<?php if ($onTheNewsTabSel != ON_THE_NEWS_MOST_VIEWED) echo "onTheNewsInactiveTab"; else echo "onTheNewsActiveTab"; ?>"><?php if ($onTheNewsTabSel != ON_THE_NEWS_MOST_VIEWED){ ?><a href="readArticle.php?otn=mv&sel=<?php echo $categoryId;?>&sc=<?php echo $subcategoryId;?>&article=<?php echo $articleId;?>"><?php } ?>Most Viewed<?php if ($onTheNewsTabSel != ON_THE_NEWS_MOST_VIEWED){ ?></a><?php } ?></li>
							<li class="<?php if ($onTheNewsTabSel != ON_THE_NEWS_LAST_24_HOURS) echo "onTheNewsInactiveTab"; else echo "onTheNewsActiveTab"; ?>"><?php if ($onTheNewsTabSel != ON_THE_NEWS_LAST_24_HOURS){ ?><a href="readArticle.php?otn=l24h&sel=<?php echo $categoryId;?>&sc=<?php echo $subcategoryId;?>&article=<?php echo $articleId;?>"><?php } ?>Last 24 Hours<?php if ($onTheNewsTabSel != ON_THE_NEWS_LAST_24_HOURS){ ?></a><?php } ?></li>
						</ul>
					</div>
					<?php
						//get news on the last 24 hours
						$sql = "";
						if ($onTheNewsTabSel == ON_THE_NEWS_LAST_24_HOURS)
							$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM Articles AS art JOIN Subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE PublishDate<>'0000-00-00' AND (PublishDate + INTERVAL(3600 * 24) SECOND >= NOW()) ORDER BY PublishDate DESC LIMIT 3");
						else
							$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, sc.CatGenID FROM Articles AS art JOIN Subcategory AS sc ON sc.SCGenID=art.SCGenID WHERE PublishDate<>'0000-00-00' AND ViewCount > 0 ORDER BY ViewCount DESC, PublishDate DESC LIMIT 3");
							
						$onTheNewsResult = $dbConn->query($sql);
						if ($onTheNewsResult && $onTheNewsResult->num_rows > 0)
						{
							
					?>
							<?php
								$otnsectionRow = $onTheNewsResult->fetch_array();
								if ($otnsectionRow)
								{
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
									}
							?>
					<div class="otnSnipetsHolder">
						<div class="otnThumbnail">
							<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $otnsectionArticlesPhotoRow['ArtPhotoID'];?>" />
						</div>
						<div class="otnThumbEx">
							<?php echo substr($otnsectionRow['TextContent'], 0, 122) . "...";?><a href="<?php echo $prefix; ?>processing/redirectToRead.php?sel=<?php echo $otnsectionRow['CatGenID']."&sc=".$otnsectionRow['SCGenID']."&article=".$otnsectionRow['ArticleGenID']?>" class="readMoreLink">more &raquo;</a>
						</div>
					</div>
							<?php
								}
							?>
					
							<?php
								$otnsectionRow = $onTheNewsResult->fetch_array();
								if ($otnsectionRow)
								{
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
				
				<!--top story from any category -->
				<?php
					//script to select a top story from 1 subcategory.
					$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, art.Author, art.PublishDate, sc.CatGenID, sc.SubCatName, cat.CategoryName FROM Articles AS art JOIN Subcategory AS sc ON art.SCGenID=sc.SCGenID JOIN Category AS cat ON sc.CatGenID=cat.CatGenID JOIN ArticlePhoto AS ap ON ap.ArticleGenID=art.ArticleGenID WHERE art.TopStory=1 AND art.PublishDate<>'0000-00-00' AND ap.Photo IS NOT NULL");
					
					$articlesResult = $dbConn->query($sql);
					if ($articlesResult && $articlesResult->num_rows > 0)
					{
						//randomly select an article.
						$selectArticle = rand(0, $articlesResult->num_rows - 1);
						$articlesResult->data_seek($selectArticle);
					}
					
					$articleRow = $articlesResult->fetch_array();
					
					//select a photo for the article
					if ($articleRow)
					{
						$sql = sprintf("SELECT ArtPhotoID FROM ArticlePhoto WHERE ArticleGenID='%s'", $articleRow['ArticleGenID']);
						$articlePhotoResult = $dbConn->query($sql);
						if ($articlePhotoResult && $articlePhotoResult->num_rows > 0)
						{
							//do a random select for a photo if we have more than 1.
							if ($articlePhotoResult->num_rows > 1)
							{
								$selectPhoto = rand(0, $articlePhotoResult->num_rows - 1);
								$articlePhotoResult->data_seek($selectPhoto);
							}
						
							$articlePhotoRow = $articlePhotoResult->fetch_array();
						}
				?>
				<div id="healthSection" style="border-top:1px solid #D1D1D1; margin-top:10px; margin-bottom:50px; height:auto; max-width:390px">
					<div class="sectionHeading" style="height:20px">
							<div style="margin-left:0px; margin-top:5px"><?php echo $articleRow['CategoryName'] . " - " . $articleRow['SubCatName'];?></div>
					</div>
					
					<div id="healthImageNewsHolder">
						<div id="healthNewsImage">
							<img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $articlePhotoRow['ArtPhotoID'];?>" />
						</div>
						
						<div id="healthNewsImageEx">
							<div style="color:#003366; font-size:13px; font-weight:bold"><?php echo substr($articleRow['Heading'], 0, 40);?></div>
							<div style="color:#666666; font-size:12px; margin-top:17px"><?php echo substr($articleRow['TextContent'], 0, 71);?>
<br/><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $articleRow['CatGenID'];?>&sc=<?php echo $articleRow['SCGenID'];?>&article=<?php echo $articleRow['ArticleGenID'];?>" style="font-size:9px; color:#DF0000; text-decoration:none"><?php echo substr($articleRow['Author'], 0, 22);?> | <?php echo date("j M, Y",strtotime($articleRow['PublishDate']));?></a></div>
						</div>
					</div>
					
					<?php
						//get related article.
						//display related articles.
						$sql = sprintf("SELECT ar.ARTID , art.AID, art.Heading, art.ArticleGenID, art.Heading, art.Author, art.PublishDate, art.SCGenID, sc.SubCatName, sc.CatGenID FROM ArticleRelated AS ar JOIN Articles AS art ON art.ArticleGenID=ar.RelatedArticleGenID AND ar.ParentArticleGenID='%s' JOIN Subcategory AS sc ON art.SCGenID=sc.SCGenID  ORDER BY art.ViewCount, art.PublishDate DESC, art.Heading ASC, art.AID LIMIT 3", $articleRow['ArticleGenID'], $articleRow['ArticleGenID']);


						
						$relatedArticles = $dbConn->query($sql);
						if ($relatedArticles && $relatedArticles->num_rows > 0)
						{	
					?>
					<div id="relatedHealthIssues" style="color:#E00000; font-size:12px; text-align:left; margin-left:5px;">
						Related Issues
					</div>
					<?php
							for ($i = 0; ($rIssuesRow = $relatedArticles->fetch_array()) != FALSE && $i < 3; $i++)
							{
					?>
					<div class="lilSnipetsHolder">
						<div class="lilSnipets">
							<span class="midNewsExText"><label class="snippetHeading"><?php echo $rIssuesRow['SubCatName'];?>:</label> <?php echo substr($rIssuesRow['Heading'], 0, 90);?> <a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $rIssuesRow['CatGenID'];?>&sc=<?php echo $rIssuesRow['SCGenID'];?>&article=<?php echo $rIssuesRow['ArticleGenID'];?>" class="readMoreLink"> more &raquo;</a></span>
						</div>
					</div>
					<?php
							}
						}
					?>
					
				</div>
				<?php
					 }
				?>
				
				
				<?php
					//script to get in the news today articles.
					$sql = sprintf("SELECT art.Heading, art.TextContent, art.ArticleGenID, art.SCGenID, cat.CatGenID FROM Articles AS art JOIN Subcategory AS sc ON art.SCGenID=sc.SCGenID JOIN Category AS cat ON cat.CatGenID=sc.CatGenID WHERE PublishDate<>'0000-00-00' AND DATE(PublishDate)=DATE(NOW()) ORDER BY ViewCount DESC, PublishDate DESC, art.AID DESC");
					
					$todaysArticlesResult = $dbConn->query($sql);
					if ($todaysArticlesResult && $todaysArticlesResult->num_rows > 0)
					{
				?>
				<div id="inTheNewsTodayHolder">
					<div class="sectionHeading" style="height:20px">
							<div style="margin-left:0px; margin-top:5px">In the news today</div>
					</div>
					<ul id="listNewToday12max">
					<?php
						for ($i = 0; ($todayRow = $todaysArticlesResult->fetch_array()) != FALSE && $i < 16; $i++)
						{
					?>
						<li><a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $todayRow['CatGenID'];?>&sc=<?php echo $todayRow['SCGenID'];?>&article=<?php echo $todayRow['ArticleGenID'];?>"><?php echo substr($todayRow['Heading'], 0, 56);?></a></li>
					<?php
						}
					?>
					</ul>
				</div>
				<?php
					}
				?>
				
				<!--poll boot section -->
					<?php include_once($prefix . "includes/pollBoot.php");?>
			</div>
			
			<div style="clear:both; height:1px;"></div>
			<?php require_once($prefix . "includes/bottomSection.php"); ?>
		</div>
	</div>
	<div style="width:1050px; height:20px; background-color:#FFFFFF;">
	</div>
<?php
	require_once($prefix . "includes/footer.php");
?>
</div>

<div id="popper">
</div>

</body>
</html>
<?php
		}
		else
		{
			header("Location: ". $prefix . "unavailable.php"); //redirect to this page when db is offline.
			exit();
		}
	}
	else
	{
		header("Location: ". $prefix . "unavailable.php"); //redirect to this page when db is offline.
		exit();
	}
?>