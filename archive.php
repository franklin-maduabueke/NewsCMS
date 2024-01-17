<?php define('PAGE_NAME', 'archive'); ?>
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
 
	$prefix = "";
	
	require_once($prefix . "config/db.php");
	require_once($prefix . "modules/mysqli.php");
	require_once($prefix . "includes/commons.php");
	require_once($prefix . "modules/lookConfigure.php");
	require_once($prefix . "modules/adsLauncher.php");
	
	$result = NULL;
	
	//the id of the category this the user selected.
	$categoryId = $_GET['cat'];
	$scId = $_GET['sc'];
	$py = $_GET['py'];
	$q = $_GET['q'];
	
	$categoryName = "Archive";
	
	//listing for published articles in this category.
	$pageSize = 7; //reset to a higer value like 14.
	$count = 0;
	$totalPages = 0;
		
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$categoryResult = $dbConn->query("SELECT CategoryName, CatGenID FROM category ORDER BY CategoryName");
		
		if (isset($categoryId) && !empty($categoryId) && $categoryId != "00000000")
			$subCategoryResult = $dbConn->query("SELECT SubCatName, SCGenID FROM subcategory WHERE CatGenID LIKE '%$categoryId%'");
		
		$yearPublishedResult = $dbConn->query("SELECT DISTINCT YEAR(PublishDate) AS PublishDate FROM articles");
		
		$searchSql = "SELECT art.ArticleGenID, art.Heading, art.TextContent, cat.CatGenID, sc.SCGenID, COUNT(comments.CommentID) AS CommentCount FROM articles AS art LEFT JOIN comments ON comments.ArticleGenID=art.ArticleGenID JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID JOIN category AS cat ON cat.CatGenID=sc.CatGenID";
		
		if ((isset($categoryId) && $categoryId != "00000000") || (isset($py) && $py != "0000") || (isset($q) && strlen(trim($q))))
		{
			$catCheck = (isset($categoryId) && $categoryId != "00000000") ? "cat.CatGenID='$categoryId' " : "";
			$scCheck = (isset($scId) && $scId != "00000000") ? " AND sc.SCGenID='$scId' " : "";
			$pyCheck = (isset($py) && $py != "0000") ? " YEAR(art.PublishDate)='$py'" : "";
			$qCheck = (isset($_GET['q']) && !empty($_GET['q'])) ? " (art.Heading LIKE '%".$_GET['q'] ."%' OR art.TextContent LIKE '%" . $_GET['q'] . "%')"  : "";
			
			$searchSql .= " WHERE " . $catCheck . $scCheck;
			$searchSql .=  ((isset($categoryId) && $categoryId != "00000000") && (isset($py) && $py != "0000")) ? " AND " : "";
			$searchSql .= $pyCheck;
			$searchSql .=  (((isset($categoryId) && $categoryId != "00000000") || (isset($py) && $py != "0000")) && (isset($qCheck) && !empty($qCheck)))  ? " AND " : "";
			$searchSql .= $qCheck;
		}
		$searchSql .= " GROUP BY art.ViewCount DESC, art.PublishDate DESC, art.AID DESC LIMIT $pageSize";
		
		//count records that meet criteria
		$countSql = "SELECT COUNT(art.AID) FROM articles AS art JOIN subcategory AS sc ON sc.SCGenID=art.SCGenID JOIN category AS cat ON cat.CatGenID=sc.CatGenID";
		if ((isset($categoryId) && $categoryId != "00000000") || (isset($py) && $py != "0000"))
		{
			$catCheck = (isset($categoryId) && $categoryId != "00000000") ? "cat.CatGenID='$categoryId' " : "";
			$scCheck = (isset($scId) && $scId != "00000000") ? " AND sc.SCGenID='$scId' " : "";
			$pyCheck = (isset($py) && $py != "0000") ? " YEAR(art.PublishDate)='$py'" : "";
			$qCheck = (isset($_GET['q']) && !empty($_GET['q'])) ? " (art.Heading LIKE '%".$_GET['q'] ."%' OR art.TextContent LIKE '" . $_GET['q'] . "')"  : "";
			
			$countSql .= " WHERE " . $catCheck . $scCheck;
			$countSql .=  ((isset($categoryId) && $categoryId != "00000000") && (isset($py) && $py != "0000")) ? " AND " : "";
			$countSql .= $pyCheck;
			$countSql .=  ((isset($categoryId) && $categoryId != "00000000") && (isset($qCheck) && !empty($qCheck))) ? " AND " : "";
			$countSql .= $qCheck;
		}

		$countResult = $dbConn->query($countSql);
		
		$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
		
		if ($countResult && $countResult->num_rows > 0)
		{
			$countRow = $countResult->fetch_array();
			$count = $countRow[0];
			$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
			$offset = $pageSize * ($current_page - 1);
		}
		$searchSql .= " OFFSET $offset";
			
		if ($countResult && $countResult->num_rows > 0)
			$searchResult = $dbConn->query($searchSql);
	}
	else
	{
		header("Location: " . $prefix . "unavailable.php"); //redirect to this page when db is offline.
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>www.nigerianewsnetwork.com : Archives</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/global.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/businessLook.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/scrollingCtrl.css">
<link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>stylesheet/archive.css">
<script type="text/javascript">
	<?php 
		$jsPrefix = $prefix;
	?>
	var prefix = <?php echo "'$jsPrefix'";?>;
</script>
<script type="text/javascript" src="<?php echo $prefix; ?>scripts/jQuery.js"></script>
<script type="text/javascript" src="<?php echo $prefix; ?>scripts/scrollingCtrl.js"></script>
<script type="text/javascript" src="<?php echo $prefix; ?>scripts/archiveScript.js"></script>
<body>
<div id="pageContainter" align="center">
<?php
	require_once($prefix . "includes/header.php");
?>
	<?php
		//hide categoryid existence so we dont switch the heading active link.
		$theCategoryId = $categoryId;
		$categoryId = "";
	?>
	<?php require_once($prefix . "includes/headerBasePanel.php"); ?>
	<div id="pageContentHolder">
		<div id="pageContentMainBlock">
			<?php require_once($prefix . "includes/sportsLookHeaderBasePanel.php"); ?>
			<?php require_once($prefix . "includes/scrollingNewsControl.php"); ?>
	<?php
		//unhide categoryid existence to give other parts right access.
		$categoryId = $theCategoryId;
	?>
			<div id="pageLeftColumn" style="width:216px; border:0px; float:left; min-height:500px;">
				<div id="searchRightControlHolder" style="width:216px; height:500px; background-color:#E10000;">
					<div style="color:#FFFFFF; font-size:13px; text-align:left; margin-left:10px; padding-top:10px;">
						Select Category:
					</div>

					<div>
						<select name="searchCategoryOption" id="searchCategoryOption" class="selectionCntrl">
							<option value="00000000">All Category</option>
							<?php
								if ($dbConn)
									if ($categoryResult && $categoryResult->num_rows > 0)
										for (;($catRow = $categoryResult->fetch_array()) != FALSE;)
										{
											
							?>
										<option value="<?php echo $catRow['CatGenID']?>" <?php if (isset($categoryId) && $categoryId == $catRow['CatGenID']) echo 'selected="selected"'; ?>><?php echo $catRow['CategoryName']?></option>
							<?php
										}
							?>
						</select>
					</div>
					
					<div style="color:#FFFFFF; font-size:13px; text-align:left; margin-left:10px; padding-top:10px;">
						Select Subcategory:
					</div>
					<div>
						<select name="searchSubcategoryOption" id="searchSubcategoryOption" class="selectionCntrl">
							<option value="00000000">All Subcategory</option>
							<?php
								if ($dbConn && isset($categoryId) && isset($subCategoryResult))
									if ($subCategoryResult && $subCategoryResult->num_rows > 0)
										for (;($scRow = $subCategoryResult->fetch_array()) != FALSE;)
										{
											
							?>
										<option value="<?php echo $scRow['SCGenID']?>" <?php if (isset($scId) && $scId == $scRow['SCGenID']) echo 'selected="selected"'; ?>><?php echo $scRow['SubCatName']?></option>
							<?php
										}
							?>
						</select>
					</div>
					
					<div style="color:#FFFFFF; font-size:13px; text-align:left; margin-left:10px; padding-top:10px;">
						Publish Year:
					</div>
					<div>
						<select name="searchPublishYearOption" id="searchPublishYearOption" class="selectionCntrl">
							<option value="0000">All</option>
							<?php
								if ($dbConn)
									if ($yearPublishedResult && $yearPublishedResult->num_rows > 0)
										for (;($yearRow = $yearPublishedResult->fetch_array()) != FALSE;)
										{
											
							?>
										<option value="<?php echo $yearRow['PublishDate']?>" <?php if (isset($py) && $py == $yearRow['PublishDate']) echo 'selected="selected"'; ?>><?php echo $yearRow['PublishDate']?></option>
							<?php
										}
							?>
						</select>
					</div>
					
					<div style="text-align:right; margin-right:8px; margin-top:13px;">
						<input type="button" value="Search" name="searchControlSubmitBtn" id="searchControlSubmitBtn" class="clickable"  style="border:0px; width:72px; height:26px; background-color:#27BFE8; font-size:14px; color:#FFFFFF"/>
					</div>
				</div>
				<?php include($prefix . "includes/connectToUsCtrl.php"); ?>
			</div>
		
			<div id="pageRightColumn" style="border-left:1px solid #D1D1D1; min-height:712px; width:780px; font-family:Verdana, Arial, Helvetica, sans-serif">
				<div style="width:774px; height:auto; float:right;">
					<div id="searchByTitleControlHolder" style="background-color:#27BFE8; width:774px%; height:40px; text-align:left">
						<div style="float:left; width:auto; color:#FFFFFF; margin-left:5px; margin-top:8px;">
							<label style="">Search By Title:</label>
						</div>
						<div style="float:left; width:auto; margin-left:5px; margin-top:5px;">
							<input type="text" name="searchByTitleTxt" id="searchByTitleTxt" style="width:430px; border:3px solid #FFFFFF" value="<?php echo $_GET['q'];?>" />
						</div>
						<div style="float:left; width:auto; margin-left:5px; margin-top:6px;">
							<input type="button" value="Search" name="searchControlTitleSubmitBtn" id="searchControlTitleSubmitBtn" class="clickable"  style="border:0px; width:72px; height:26px; background-color:#E10000; font-size:14px; color:#FFFFFF"/>
						</div>
					</div>
					
					
					<div id="pagingControlHolder" style="width:100%; height:40px; border-bottom:1px solid #27BFE8; margin-bottom:10px;">
						<?php
							if ($count > 0)
							{
						?>
						<div style="float:left">
							<label>Total Result: <?php echo $count;?></label>|<label>Page: <?php echo "$current_page / $totalPages"; ?></label>
						</div>
						
						<div style="float:right">
							<?php
								if ($current_page > 1)
								{
								?>
							<a href="<?php echo $prefix;?>archive.php?<?php 
								if ($catCheck) echo "cat=$categoryId";
								if ($catCheck && $scCheck) echo "&";
								if ($scCheck) echo "&sc=$scId";
								if ($catCheck && $pyCheck) echo "&";
								if ($pyCheck) echo "py=" . $py;
								echo "&page_no=1";
								if ($_GET['q']) echo "&q=" . trim($_GET['q']); ?>">First</a>
							<?php
								}
							?>
							<?php
								if ($current_page > 1)
								{
							?>
							<a href="<?php echo $prefix;?>archive.php?<?php 
								if ($catCheck) echo "cat=$categoryId";
								if ($catCheck && $scCheck) echo "&";
								if ($scCheck) echo "&sc=$scId";
								if ($catCheck && $pyCheck) echo "&";
								if ($pyCheck) echo "py=" . $py;
								echo "&page_no=" . ($current_page - 1);
								if ($_GET['q']) echo "&q=" . trim($_GET['q']);
								?>">&laquo; Prev</a>
							<?php
								}
							?>
							
							<?php
								if ($current_page < $totalPages)
								{
							?>
							<a href="<?php echo $prefix;?>archive.php?<?php 
								if ($catCheck) echo "cat=$categoryId";
								if ($catCheck && $scCheck) echo "&";
								if ($scCheck) echo "&sc=$scId";
								if ($catCheck && $pyCheck) echo "&";
								if ($pyCheck) echo "py=" . $py;
								echo "&page_no=" . ($current_page + 1);
								if ($_GET['q']) echo "&q=" . trim($_GET['q']);
								?>">Next &raquo; </a>
							<?php
								}
							?>
							
							<?php
								if ($current_page != $totalPages)
								{
							?>
							<a href="<?php echo $prefix;?>archive.php?<?php 
								if ($catCheck) echo "cat=$categoryId"; 
								if ($scCheck) echo "&sc=$scId";
								if ($pyCheck) echo "&py=" . $py;
								echo "&page_no=" . $totalPages;
								if ($_GET['q']) echo "&q=" . trim($_GET['q']);
								?>">Last</a>
							<?php
								}
							?>
						</div>
						<?php
							}
						?>
					</div>
					
					<?php
						if ($searchResult && $searchResult->num_rows > 0)
						{
							for ($i = 0;($articleRow = $searchResult->fetch_array()) != FALSE; $i++)
							{ 
					?>
					<div class="searchResultPanel <?php if (($i % 2) == 0) echo "podd"; else echo "peven";?>">
						<div class="resultThumbTextHolder">
							<div class="resultThumbnail">
							<?php
								$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $articleRow['ArticleGenID']);
								$aPhotoResult = $dbConn->query($sql);
								if ($aPhotoResult && $aPhotoResult->num_rows > 0)
								{
									//do a random select for a photo if we have more than 1.
									if ($aPhotoResult->num_rows > 1)
									{
										$selectPhoto = rand(0, $aPhotoResult->num_rows - 1);
										$aPhotoResult->data_seek($selectPhoto);
									}
						
									$aPhotoRow = $aPhotoResult->fetch_array();
								}
							?>
								<img src="<?php echo $prefix; ?>processing/fetch_article_photo.php?photoId=<?php echo $aPhotoRow['ArtPhotoID'];?>" />
							<?php
							?>
							</div>
							<div class="resultTextHolder">
								<div class="resultHeader">
									<?php echo $articleRow['Heading']; ?>
								</div>
								<div class="resultContent">
									<?php echo substr($articleRow['TextContent'], 0, 386); ?>
								</div>
								<div style="clear:both" class="resultLinksHolder">
									<a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $articleRow['CatGenID'];?>&sc=<?php echo $articleRow['SCGenID'];?>&article=<?php echo $articleRow['ArticleGenID'];?>"><?php echo $articleRow['CommentCount'];?> Comment(s)</a>|<a href="<?php echo $prefix;?>processing/redirectToRead.php?sel=<?php echo $articleRow['CatGenID'];?>&sc=<?php echo $articleRow['SCGenID'];?>&article=<?php echo $articleRow['ArticleGenID'];?>">Read</a>
								</div>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
					<?php
							}
						}
						else
						{
					?>
							<div style="height:70px; color:#FFFFFF; font-weight:bold; font-size:20px; background-color:#27BFE8">
								<div style="padding-top:20px">No Results Found!</div>
							</div>
					<?php
						}
					?>
				</div>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
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
