<?php
				//pick a category at random.
				$sql = sprintf("SELECT COUNT(SCGenID) AS ArticleCount, SCGenID FROM articles GROUP BY SCGenID ORDER BY ArticleCount DESC");
				$subCatResult = $dbConn->query($sql);
				
				$scSelect = NULL;
				
				//select a subcategory
				if ($subCatResult && $subCatResult->num_rows > 0)
				{
					$scSelect = rand(0, $subCatResult->num_rows - 1);
					$subCatResult->data_seek($scSelect);
				}
				
				$scCountRow = $subCatResult->fetch_array();
				
				//get category the get all subcategorys and their articles to give more to select from by bottom section
				//seems its just selecting from 1 subcategory which may not have enough articles to fill it.
				
				$sql = sprintf("SELECT cat.CategoryName, sc.CatGenID, sc.SCGenID FROM subcategory AS sc JOIN category AS cat ON sc.CatGenID=cat.CatGenID WHERE sc.SCGenID='%s'",  $scCountRow['SCGenID']);
				
				$categoryWithSCResult = $dbConn->query($sql);
				$categoryWithSCRow = NULL;
				
				if ($categoryWithSCResult && $categoryWithSCResult->num_rows > 0)
				{	
					$categoryWithSCRow = $categoryWithSCResult->fetch_array();
				}
				
				//echo "<br/> ID = " . $scCountRow['SCGenID'];
?>
			<div id="bottomSection">
				<div id="bSAdvertBig">
				<?php 
					if (isset($categoryWithSCRow))
					{
				?>
					<div style="color:#DF0000; font-size:14px; font-weight:bold; width:633px; height:23px; border-bottom:1px solid #D1D1D1; text-align:left; padding-left:10px; margin-top:10px;" id="bsAdvertBigHeading">
						<?php echo $categoryWithSCRow['CategoryName']; ?>
					</div>
				<?php
					}
				?>
				
				<?php 
					if (isset($categoryWithSCRow))
					{
						//fetch an article.
						
						$sql = sprintf("SELECT art.ArticleGenID, art.Heading, art.TextContent, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON art.SCGenID=sc.SCGenID WHERE art.SCGenID IN(SELECT SCGenID FROM subcategory WHERE CatGenID='%s') AND PublishDate<>'0000-00-00' ORDER BY ViewCount DESC", $categoryWithSCRow['CatGenID'], $scCountRow['SCGenID']);
						$bsArticlesResult = $dbConn->query($sql);
						if ($bsArticlesResult && $bsArticlesResult->num_rows > 0)
						{
				?>
					<?php
							//randomly select an article from the result to show on big screen so it doesnt appear static.
							$bsBigSelect = rand(0, $bsArticlesResult->num_rows - 1);
							$bsArticlesResult->data_seek($bsBigSelect);
							
							$bsBigArticleID = NULL;
							$bsArticleRow = $bsArticlesResult->fetch_array();
							if ($bsArticleRow)
							{
									$bsBigArticleID = $bsArticleRow['ArticleGenID'];
									
									$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $bsArticleRow['ArticleGenID']);
									$bsArticlesPhotoResult = $dbConn->query($sql);
									if ($bsArticlesPhotoResult && $bsArticlesPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($bsArticlesPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $bsArticlesPhotoResult->num_rows - 1);
											$bsArticlesPhotoResult->data_seek($selectPhoto);
										}
						
										$bsArticlesPhotoRow = $bsArticlesPhotoResult->fetch_array();
									}
					?>
					<div id="bsAdvertBigMainScreenHolder">
						<div id="bsAdvertBigMainText">
							<?php echo $bsArticleRow['Heading']; ?>
						</div>
						<div id="bsAdvertBigScreenImageHolder">
							<a href="<?php echo $prefix; ?>processing/redirectToRead.php?sel=<?php echo $bsArticleRow['CatGenID'];?>&sc=<?php echo $bsArticleRow['SCGenID'];?>&article=<?php echo $bsArticleRow['ArticleGenID'];?>"><img src="<?php echo $prefix;?>processing/fetch_article_photo.php?photoId=<?php echo $bsArticlesPhotoRow['ArtPhotoID'];?>" /></a>
						</div>
					</div>
					<?php
						}
					?>
					
					
					<div id="bsAdvertBigThumbnailHolder">
						<?php
							$bsArticlesResult->data_seek(0); //reset to first row.
							$drawCount = 0;
							for (; $drawCount < 3;)
							{
								if ($bsArticleRow = $bsArticlesResult->fetch_array())
								{
									if ($bsArticleRow['ArticleGenID'] == $bsBigArticleID)
										continue;
										
									$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $bsArticleRow['ArticleGenID']);
									$bsArticlesPhotoResult = $dbConn->query($sql);
									if ($bsArticlesPhotoResult && $bsArticlesPhotoResult->num_rows > 0)
									{
										//do a random select for a photo if we have more than 1.
										if ($bsArticlesPhotoResult->num_rows > 1)
										{
											$selectPhoto = rand(0, $bsArticlesPhotoResult->num_rows - 1);
											$bsArticlesPhotoResult->data_seek($selectPhoto);
										}
						
										$bsArticlesPhotoRow = $bsArticlesPhotoResult->fetch_array();
									}
						?>
						<div id="bsAdvertThumbnailHolder1" class="bsAdvertThumbnailAndTextHolder">
							<div class="bsAdvertThumbnail">
								<a href="<?php echo $prefix; ?>processing/redirectToRead.php?sel=<?php echo $bsArticleRow['CatGenID'];?>&sc=<?php echo $bsArticleRow['SCGenID'];?>&article=<?php echo $bsArticleRow['ArticleGenID'];?>"><img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $bsArticlesPhotoRow['ArtPhotoID'];?>"  /></a>
							</div>
							<div class="bsAdvertThumbnailExHolder" id="bsAdvertThumbnail_1_Text">
								<?php echo $bsArticleRow['Heading']; ?>
							</div>
						</div>
						<?php
									$drawCount++;
								}
								else
									break;
							}
						?>
					</div>
				<?php
						}
					}
				?>
				</div>
				
				<div id="bSAdvertSmall">
					<div id="bSAdvertHeading" style="font-size:12px; color:#666666; margin-top:20px; margin-bottom:2px;">
						advertisment
					</div>
					<div id="bSAdvertSmallImageHolder">
					<?php
						$sql = sprintf("SELECT ad.ClientGenID, ad.AdvertType, ad.AdsGenID, ImAd.IATID, ImAd.ImageAdGenID, ImAd.ImageAdLink, ImAd.FileFormat FROM adverts AS ad JOIN imageads AS ImAd ON ad.AdsGenID=ImAd.AdsGenID WHERE ImAd.ImageAdType=%d AND ad.Duration>=%d AND ad.AdvertType=%d AND ad.GoldAdvert<>1 ORDER BY AdvertViewCount ASC, ad.AdsGenID", AD_IMG_SQUARE, time(), AD_IMAGE);
		
					$advertResult = $dbConn->query($sql);
					if ($advertResult && $advertResult->num_rows > 0)
					{
						//random select
						$selection = rand(0, $advertResult->num_rows - 1);
						$advertResult->data_seek($selection);
						
						$advertRow = $advertResult->fetch_array();
			?>
				<a href="<?php echo $advertRow['ImageAdLink'];?>" style="text-decoration:none; color:#FFFFFF; border:0px" target="_blank">
					<img src="<?php echo $prefix . "temps/" . $advertRow['AdsGenID'] . $advertRow['ImageAdGenID'] . "." . $advertRow['FileFormat'];?>" style="width:301px; height:267px" />
				</a>
			<?php
						//update view count.
						$sql = sprintf("UPDATE adverts SET AdvertViewCount = AdvertViewCount + 1 WHERE AdsGenID='%s'", $advertRow['AdsGenID']);
						$dbConn->query($sql);
					}
					else
					{
					?>
				<a href="#" style="text-decoration:none; color:#FFFFFF; border:0px">
					<img src="<?php echo $prefix;?>images/advertShop.jpg"  style="width:301px; height:267px" />
				</a>
				<?php
					}
			?>
					</div>
				</div>
			</div>