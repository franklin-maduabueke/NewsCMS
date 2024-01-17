<div class="topRadvertSection">
	<!-- section can contain flash or image advert -->
	<div class="toptRadvertSectionAdvertHolder">
		<?php
				//select what type of advert to show.
				$adToShow = rand(1, 2); //flash or image
				switch ($adToShow)
				{
				case AD_FLASH:
				//check to see if we have flash ads.
					$sql = sprintf("SELECT ad.ClientGenID, ad.AdvertType, ad.AdsGenID, fAd.FATID, fAd.FlashAdGenID FROM adverts AS ad JOIN flashads AS fAd ON ad.AdsGenID=fAd.AdsGenID WHERE fAd.FlashAdType=%d AND ad.Duration>=%d AND ad.AdvertType=%d ORDER BY AdvertViewCount", AD_FLASH_BANNER_SQUARE, time(), AD_FLASH);
		
					$flashResults = $dbConn->query($sql);
					
					if ($flashResults && $flashResults->num_rows > 0)
					{
						//random select
						$selection = rand(0, $flashResults->num_rows - 1);
						$flashResults->data_seek($selection);
						
						$advertRow = $flashResults->fetch_array();
			?>
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="305" height="250">
       		 	<param name="movie" value="<?php echo $prefix;?>temps/<?php echo $advertRow['AdsGenID'] . $advertRow['FlashAdGenID'] . ".swf";?>" />
        		<param name="quality" value="high" />
        		<embed src="<?php echo $prefix;?>temps/<?php echo $advertRow['AdsGenID'] . $advertRow['FlashAdGenID'] . ".swf";?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="305" height="250"></embed>
      		</object>
			<?php
						//update view count.
						$sql = sprintf("UPDATE adverts SET AdvertViewCount = AdvertViewCount + 1 WHERE AdsGenID='%s'", $advertRow['AdsGenID']);
						$dbConn->query($sql);
					}
					else
					{
				?>
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="305" height="250">
       		 	<param name="movie" value="<?php echo $prefix;?>images/flashSquare.swf" />
        		<param name="quality" value="high" />
        		<embed src="<?php echo $prefix;?>images/flashSquare.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="305" height="250"></embed>
      		</object>
			<?php
					}
				break;
				case AD_IMAGE:
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
					<img src="<?php echo $prefix . "temps/" . $advertRow['AdsGenID'] . $advertRow['ImageAdGenID'] . "." . $advertRow['FileFormat'];?>" style="width:305px; height:250px" />
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
					<img src="<?php echo $prefix;?>images/advertShop.jpg"  style="width:305px; height:250px" />
				</a>
				<?php
					}
				break;
				}
			?>
	</div>
</div>