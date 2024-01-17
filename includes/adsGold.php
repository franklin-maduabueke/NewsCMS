
			<?php
					$sql = sprintf("SELECT ad.ClientGenID, ad.AdvertType, ad.AdsGenID, ImAd.IATID, ImAd.ImageAdGenID, ImAd.ImageAdLink, ImAd.FileFormat FROM adverts AS ad JOIN imageads AS ImAd ON ad.AdsGenID=ImAd.AdsGenID WHERE ImAd.ImageAdType=%d AND ad.Duration>=%d AND ad.GoldAdvert=1 AND ad.AdvertType=%d ORDER BY AdvertViewCount ASC, ad.AdsGenID", AD_IMG_SQUARE, time(), AD_IMAGE);
		
					
					$advertResult = $dbConn->query($sql);
					if ($advertResult && $advertResult->num_rows > 0)
					{
						//random select
						$selection = rand(0, $advertResult->num_rows - 1);
						$advertResult->data_seek($selection);
						$advertRow = $advertResult->fetch_array();
			?>
				<a href="<?php echo $advertRow['ImageAdLink'];?>" style="text-decoration:none; color:#FFFFFF; border:0px" target="_blank">
					<img src="<?php echo $prefix . "temps/" . $advertRow['AdsGenID'] . $advertRow['ImageAdGenID'] . "." . $advertRow['FileFormat'];?>" style="width:100%; height:100%" />
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
					<img src="<?php echo $prefix;?>images/goldads.jpg"  style="width:100%; height:100%" />
				</a>
				<?php
					}
			?>