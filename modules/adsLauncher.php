<?php
	//class used to get adverts for sites db.
	DEFINE("AD_FLASH", "1");
	DEFINE("AD_IMAGE", "2");
	
	DEFINE("AD_IMG_SQUARE", "1");
	DEFINE("AD_IMG_BANNER_HORIZONTAL", "2");
	DEFINE("AD_IMG_BANNER_VERTICAL", "3");
	
	DEFINE("AD_FLASH_BANNER_SQUARE", "1");
	DEFINE("AD_FLASH_BANNER_HORIZONTAL", "2");
	DEFINE("AD_FLASH_BANNER_VERTICAL", "3");
	
	
	class AdsLauncher
	{
		public function __construct(MySQLi $dbConn)
		{
			if (!isset($dbConn))
				throw new Exception("Connection object not given");
			else
			{
				//ping the connection.
				if (!$dbConn->ping())
					throw new Exception("Server has gone away.");
				else
					$this->mdbConn =& $dbConn;
			}
		}
		
		
		public  function  getImageAdvert($adType)
		{
			if (isset($adType))
			{
				switch ($adType)
				{
				case AD_IMG_BANNER_HORIZONTAL:
					//select all adverts that are not expired yet.
					$sql = sprintf("SELECT ad.ClientGenID, ad.AdvertType, ad.AdsGenID, ImAd.IATID FROM adverts AS ad JOIN imageads AS ImAd ON ad.AdsGenID=ImAd.AdsGenID WHERE ImAd.ImageAdType=%d AND ad.Duration>=%d AND ad.AdvertType=%d ORDER BY AdvertViewCount", AD_IMG_BANNER_HORIZONTAL, time(), AD_IMAGE);
					$adsResult = $this->mdbConn->query($sql);
					
					if ($adsResult && $adsResult->num_rows > 0)
					{
						//randomly select an advert.
						$selected = rand(0, $adsResult->num_rows - 1);
						$adsResult->data_seek($selected);
						
						$adRow = $adsResult->fetch_array();
						$sql = sprintf("SELECT AdImage, FileFormat, ImageAdLink, ImageAdGenID FROM imageads WHERE ImageAdType=%d AND IATID=%d", AD_IMG_BANNER_HORIZONTAL, $adRow['IATID']);
						$advertResult = $this->mdbConn->query($sql);
						
						if ($advertResult && $advertResult->num_rows > 0)
						{
							$advert = $advertResult->fetch_array();
							return array("image_data"=> $advert['AdImage'], "image_format"=> $advert['FileFormat'], "url"=> $advert['ImageAdLink']);
						}
					}
				break;
				case AD_IMG_BANNER_VERTICAL:
					//select all adverts that are not expired yet.
					$sql = sprintf("SELECT ad.ClientGenID, ad.AdvertType, ad.AdsGenID, ImAd.IATID FROM adverts AS ad JOIN imageads AS ImAd ON ad.AdsGenID=ImAd.AdsGenID WHERE ImAd.ImageAdType=%d AND ad.Duration>=%d AND ad.AdvertType=%d ORDER BY AdvertViewCount", AD_IMG_BANNER_VERTICAL, time(), AD_IMAGE);
					$adsResult = $this->mdbConn->query($sql);
					
					if ($adsResult && $adsResult->num_rows > 0)
					{
						//randomly select an advert.
						$selected = rand(0, $adsResult->num_rows - 1);
						$adsResult->data_seek($selected);
						
						$adRow = $adsResult->fetch_array();
						$sql = sprintf("SELECT AdImage, FileFormat, ImageAdLink, ImageAdGenID FROM imageads WHERE ImageAdType=%d AND IATID=%d", AD_IMG_BANNER_VERTICAL, $adRow['IATID']);
						$advertResult = $this->mdbConn->query($sql);
						
						if ($advertResult && $advertResult->num_rows > 0)
						{
							$advert = $advertResult->fetch_array();
							return array("image_data"=> $advert['AdImage'], "image_format"=> $advert['FileFormat'], "url"=> $advert['ImageAdLink']);
						}
					}
				break;
				case AD_IMG_SQUARE:
					//select all adverts that are not expired yet.
					$sql = sprintf("SELECT ad.ClientGenID, ad.AdvertType, ad.AdsGenID, ImAd.IATID FROM adverts AS ad JOIN imageads AS ImAd ON ad.AdsGenID=ImAd.AdsGenID WHERE ImAd.ImageAdType=%d AND ad.Duration>=%d AND ad.AdvertType=%d ORDER BY AdvertViewCount", AD_IMG_SQUARE, time(), AD_IMAGE);
					$adsResult = $this->mdbConn->query($sql);

					if ($adsResult && $adsResult->num_rows > 0)
					{
						//randomly select an advert.
						$selected = rand(0, $adsResult->num_rows - 1);
						$adsResult->data_seek($selected);
						
						$adRow = $adsResult->fetch_array();
						$sql = sprintf("SELECT AdImage, FileFormat, ImageAdLink, ImageAdGenID FROM imageads WHERE ImageAdType=%d AND IATID=%d", AD_IMG_SQUARE, $adRow['IATID']);
						$advertResult = $this->mdbConn->query($sql);
						
						if ($advertResult && $advertResult->num_rows > 0)
						{
							$advert = $advertResult->fetch_array();
							return array("image_data"=> $advert['AdImage'], "image_format"=> $advert['FileFormat'], "url"=> $advert['ImageAdLink']);
						}
					}
				break;
				}
			}
			
			return FALSE;
		}
		
		
		//used to fetch a flash advert
		public function  getFlashAdvert($adType)
		{
			if (isset($adType))
			{
				switch ($adType)
				{
				case AD_FLASH_BANNER_HORIZONTAL:
					//select all adverts that are not expired yet.
					$sql = sprintf("SELECT ad.ClientGenID, ad.AdvertType, ad.AdsGenID, fAd.FATID FROM adverts AS ad JOIN flashads AS fAd ON ad.AdsGenID=fAd.AdsGenID WHERE fAd.FlashAdType=%d AND ad.Duration>=%d AND ad.AdvertType=%d ORDER BY AdvertViewCount", AD_FLASH_BANNER_HORIZONTAL, time(), AD_FLASH);
					$adsResult = $this->mdbConn->query($sql);
					
					if ($adsResult && $adsResult->num_rows > 0)
					{
						//randomly select an advert.
						$selected = rand(0, $adsResult->num_rows - 1);
						$adsResult->data_seek($selected);
						
						$adRow = $adsResult->fetch_array();
						$sql = sprintf("SELECT Flash, FlashAdGenID FROM flashads WHERE FlashAdType=%d AND FATID=%d", AD_FLASH_BANNER_HORIZONTAL, $adRow['FATID']);
						$advertResult = $this->mdbConn->query($sql);
						
						if ($advertResult && $advertResult->num_rows > 0)
						{
							$advert = $advertResult->fetch_array();
							return array("flash_data"=> $advert['Flash']);
						}
					}
				break;
				case AD_FLASH_BANNER_VERTICAL:
					//select all adverts that are not expired yet.
					$sql = sprintf("SELECT ad.ClientGenID, ad.AdvertType, ad.AdsGenID, fAd.FATID FROM adverts AS ad JOIN flashads AS fAd ON ad.AdsGenID=fAd.AdsGenID WHERE fAd.FlashAdType=%d AND ad.Duration>=%d AND ad.AdvertType=%d ORDER BY AdvertViewCount", AD_FLASH_BANNER_VERTICAL, time(), AD_FLASH);
					$adsResult = $this->mdbConn->query($sql);
					
					if ($adsResult && $adsResult->num_rows > 0)
					{
						//randomly select an advert.
						$selected = rand(0, $adsResult->num_rows - 1);
						$adsResult->data_seek($selected);
						
						$adRow = $adsResult->fetch_array();
						$sql = sprintf("SELECT Flash, FlashAdGenID FROM flashads WHERE FlashAdType=%d AND FATID=%d", AD_FLASH_BANNER_VERTICAL, $adRow['FATID']);
						$advertResult = $this->mdbConn->query($sql);
						
						if ($advertResult && $advertResult->num_rows > 0)
						{
							$advert = $advertResult->fetch_array();
							return array("flash_data"=> $advert['Flash']);
						}
					}
				break;
				case AD_FLASH_BANNER_SQUARE:
					//select all adverts that are not expired yet.
					$sql = sprintf("SELECT ad.ClientGenID, ad.AdvertType, ad.AdsGenID, fAd.FATID FROM adverts AS ad JOIN flashads AS fAd ON ad.AdsGenID=fAd.AdsGenID WHERE fAd.FlashAdType=%d AND ad.Duration>=%d AND ad.AdvertType=%d ORDER BY AdvertViewCount", AD_FLASH_BANNER_SQUARE, time(), AD_FLASH);
					$adsResult = $this->mdbConn->query($sql);
					
					if ($adsResult && $adsResult->num_rows > 0)
					{
						//randomly select an advert.
						$selected = rand(0, $adsResult->num_rows - 1);
						$adsResult->data_seek($selected);
						
						$adRow = $adsResult->fetch_array();
						$sql = sprintf("SELECT Flash, FlashAdGenID FROM flashads WHERE FlashAdType=%d AND FATID=%d", AD_FLASH_BANNER_SQUARE, $adRow['FATID']);
						$advertResult = $this->mdbConn->query($sql);
						
						if ($advertResult && $advertResult->num_rows > 0)
						{
							$advert = $advertResult->fetch_array();
							return array("flash_data"=> $advert['Flash']);
						}
					}
				break;
				}
			}
			
			return FALSE;
		}
		
		
		//not implemented. Should get a google advert using google ads api
		public function  getGoogleAdvert()
		{
			return FALSE;
		}
		
		private $mdbConn;
	}
	
	/*
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");
	
	$dbConn = new mysqli(DB_SERVER,DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		try
		{
			$adLauncher = new AdsLauncher($dbConn);
			echo "<br/>AdsLauncher created";
			echo "<br/>AdsLauncher fetching flash advert";
			$flashAdvert = $adLauncher->getImageAdvert(AD_IMG_BANNER_HORIZONTAL);
			
			if ($flashAdvert)
			{
				echo "<br/>Flash advert fetched";
				echo "<br/> Good";
				//print_r ($flashAdvert['image_data']);
				echo "<br/>";
				echo $flashAdvert['image_data'];
			}
			else
			{
				echo "<br/>Flash advert not fetched";
			}
		}
		catch (Exception $ex)
		{
			echo $ex->getMessage();
		}
		
	}
	*/
?>