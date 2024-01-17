<?php
	require_once("config/db.php");
	require_once("modules/mysqli.php");
	require_once("includes/commons.php");
	require_once("modules/lookConfigure.php");
	require_once("modules/adsLauncher.php");
	
	$result = NULL;
	
	//the id of the category this template will use for getting news.
	$categoryId = PAGE_NAME;
	$categoryName = "How To";
	
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
		header("Location: unavailable.php"); //redirect to this page when db is offline.
		exit();
	}
	
	$onTheNewsTabSel = ON_THE_NEWS_LAST_24_HOURS;//$_GET['o_news_tab_sel'];
	
	$categoryName = "How To Advertise";
	
	$aboutUsContent = "Not Written Yet!";
	
	$content = file_get_contents("config/how_to_place_ads.txt");
	if ($content && strlen(trim($content)) > 0)
		$aboutUsContent = trim($content);
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>www.nigerianewsnetwork.com : How To</title>
</head>
<link rel="stylesheet" type="text/css" href="stylesheet/global.css" />
<link rel="stylesheet" type="text/css" href="stylesheet/aboutusLook.css" />
<link rel="stylesheet" type="text/css" href="stylesheet/adsSquare.css" />

<body>
<div id="pageContainter" align="center">
<?php
	require_once("includes/header.php");
?>
	<?php require_once("includes/headerBasePanel.php") ?>
	<div id="pageContentHolder">
	  <div id="pageContentMainBlock">
			<?php require_once("includes/jobsLookHeaderBasePanel.php"); ?>
			
			<div id="jobsSearchPanel">
				<div id="jobsLabelHolder">
					<div id="jobsLabel">Ads</div>
				</div>
				
				<div id="jobsSearchCtrlHolder">
					<ul class="jobSearchUL">
						<li><a href="#">About Nigeria News Network</a></li>
						<li><a href="#">Feedbacks and Observations</a></li>
						<li><a href="#">Using NNN Resources</a></li>
					</ul>
					
					<ul class="jobSearchUL">
						<li><a href="#">About Archives</a></li>
						<li><a href="#">Privacy Policy</a></li>
						<li><a href="#">Terms of Use</a></li>
					</ul>
					
					<ul class="jobSearchUL">
						<li><a href="#">Contacts and Locations</a></li>
						<li><a href="#">Frequently Asked Questions</a></li>
						<li><a href="#">Advert Placement</a></li>
					</ul>
				</div>
			</div>
			
			<div id="aboutusPageLeftColumn">
				<div id="aboutusPLColHeading">How To Advertise On Nigeria News Network</div>
				
				<div id="aboutusPLColStatement">
					<?php echo $aboutUsContent; ?>
				</div>
			</div>
			
			<div id="aboutusPageRightColumn">
				<!-- include adverts gettter with php -->
				<?php include("includes/adsSquare.php");?>
			</div>
			
			<div style="clear:both; height:1px;border-bottom:1px dotted #CCCCCC; width:1000px"></div>
	  </div>
	</div>

<?php
	require_once("includes/footer.php");
?>
</body>
</html>