<?php
	if (PAGE_NAME == "home")
	{
		require_once("config/db.php");
		require_once("modules/mysqli.php");
		require_once("includes/commons.php");
		require_once("modules/lookConfigure.php");
		require_once("modules/adsLauncher.php");
	}
	else
	{
		require_once("../config/db.php");
		require_once("../modules/mysqli.php");
		require_once("../includes/commons.php");
		require_once("../modules/lookConfigure.php");
		require_once("../modules/adsLauncher.php");
	}
	
	$result = NULL;
	
	//the id of the category this template will use for getting news.
	$categoryId = PAGE_NAME;
	$categoryName = NULL;
	
	//each section will collect its result from a mysqli result variable having its id
	//get the looknfeel definition for this page.
	$s1Result = NULL;
	$s2Result = NULL;
	
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
		header("Location: ../unavailable.php"); //redirect to this page when db is offline.
		exit();
	}
	
	$onTheNewsTabSel = ON_THE_NEWS_LAST_24_HOURS;//$_GET['o_news_tab_sel'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>www.nigerianewsnetwork.com<?php echo " : $categoryName"; ?></title>
</head>
<link rel="stylesheet" type="text/css" href="../stylesheet/global.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/jobsLook.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/scrollingCtrl.css">
<script type="text/javascript">
	<?php 
		$jsPrefix = $prefix;
	?>
	var prefix = <?php echo "'$jsPrefix'";?>;
</script>
<script type="text/javascript" src="../scripts/jQuery.js"></script>
<script type="text/javascript" src="../scripts/scrollingCtrl.js"></script>

<body>
<div id="pageContainter" align="center">
<?php
	require_once("../includes/header.php");
?>
	<?php require_once("../includes/headerBasePanel.php"); ?>
	<div id="pageContentHolder">
	  <div id="pageContentMainBlock">
			<?php require_once("../includes/jobsLookHeaderBasePanel.php"); ?>
		  	
			<div id="advertsHolder">
				<div style="text-align:right; font-size:10px">ADVERTISMENT</div>
				<div id="advertBox">
					<img src="../images/skybank.jpg" />
				</div>
			</div>
			
			<div id="jobsSearchPanel">
				<div id="jobsLabelHolder">
					<div id="jobsLabel">JOBS</div>
				</div>
				
				<div id="jobsSearchCtrlHolder">
					<div style="text-align:left; color:#003366; font-size:13px; font-weight:bold; margin-top:22px">Search for dream job</div>
					<div class="textControlHolder"><input type="text" value="What Job?" /></div>
					<div class="textControlHolder" style="margin-right:3px"><input type="text" value="Where?" /></div>
					<div class="buttonControlHolder"><input type="button" value="Search" /></div>
				</div>
			</div>
			
			<div id="jobListingHolderBorder">
				<div style="height:11px;"></div>
				<div id="jobsListingContentHolder">
				</div>
			</div>
	  </div>
	  
	  <div id="featuredJobsHolder">
	  	<div id="featuredJobsLabel">Featured Jobs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Browse All</a></div>
	  </div>
	  
	  <div id="bottomSection">
	  	<div id="bottonSectionLeftSide">
			<div class="featuredHolder">
			</div>
			
			<div class="featuredHolder">
			</div>
			
			<div class="featuredHolder">
			</div>
			
			<div class="featuredHolder">
			</div>
		</div>
		
		<div id="bottonSectionRightSide">
			<div id="bsNoticeBoard">
			</div>
			<div id="bsDivider">
			</div>
			<div id="bsBaseHeadingHolder">
				<div id="bsBaseHeading">For Recruiters</div>
				
				<div id="bsBaseLinksHolder">
					<ul>
						<li>Register with us</li>
						<li>Search our CV Database</li>
						<li>Get intouch with us</li>
						<li style="border:0px">Post Jobs</li>
					</ul>
				</div>
			</div>
		</div>
	  </div>
	  
	</div>

<?php
	if (PAGE_NAME == "home") require_once("includes/footer.php"); else require_once("../includes/footer.php");
?>
<div id="attachmentToFooter" style="height:130px; width:1050px; background-color:#FFFFFF">
</div>

</body>
</html>
