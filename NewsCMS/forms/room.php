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
 
	require_once("../modules/mysqli.php");
	require_once("../config/db.php");
	require_once("../includes/commons.php");
	require_once("../includes/user_checker.php");

	if (!userSessionGood())
	{
		header("Location: ../processing/logout.php");
		exit();
	}
	
	define("PAGE_NAME", "room");
	
	$msg = "Fatal Error in CMS System. Unable to connect to database";
	
	$user = $_SESSION['Role'];
	$userId = $_GET['user'];
	$current_task = $_GET['tsk'];
	$selectedCategory = $_GET['sel']; //code id of the category
	$categoryName = NULL;
	$scGenID = $_GET['sc'];
	$scName = NULL;
	$grpId = $_GET['gid'];
	$groupName = NULL;
	
	//subtask.
	$sub_task = $_GET['sa']; //subaction or subtask.
	
	$tabName = urldecode($_GET['tabName']);
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		if (isset($selectedCategory) && !empty($selectedCategory))
		{
			$sql = "SELECT CategoryName FROM category WHERE CatGenID='$selectedCategory'";
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$categoryName = $row['CategoryName'];
			}
		}
		
		if (isset($scGenID) && !empty($scGenID))
		{
			$sql = "SELECT SubCatName FROM subcategory WHERE SCGenID='$scGenID'";
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$scName = $row['SubCatName'];
			}
		}
		
		if (isset($grpId) && !empty($grpId))
		{
			$sql = "SELECT GroupName FROM subcategorygroup WHERE GroupGenID='$grpId'";
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$groupName = $row['GroupName'];
			}
		}
	}
	else
	{
		$msg = urlencode($msg);
		header("Location: ../index.php?msg=$msg");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Havilah Creations Ltd - Your Site's CMS ver 1.0 : <?php if (isset($categoryName)) echo $categoryName; if (isset($scName)) echo " > $scName"; ?></title>
</head>
<link rel="stylesheet" type="text/css" href="../stylesheet/global.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/task_bar.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/room.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/popper.css">
<link rel="SHORTCUT ICON" href="../images/yourcms.ico" />

<script type="text/javascript" src="../scripts/jQuery.js"></script>
<script type="text/javascript" src="../scripts/headerSearchBoxScript.js"></script>

<?php if ($current_task == GENERAL_TASK_WRITE_ARTICLE || $current_task == GENERAL_TASK_EDIT_ARTICLE) {?>
<script src="../scripts/writeArticleScript.js" type="text/javascript"></script>
<?php } ?>

<?php if ($current_task == ADMIN_TASK_ABOUT_US || $current_task == ADMIN_TASK_HOW_TO_PLACE_ADS) {?><script src="../scripts/writeAboutUsScript.js" type="text/javascript"></script>
<?php } ?>

<?php if ($current_task == GENERAL_TASK_PUBLISHED_ARTICLES || $current_task == GENERAL_TASK_UNPUBLISHED_ARTICLES) { ?>
<script type="text/javascript" src="../scripts/setRelatedArticle.js"></script>
<?php } ?>

<?php if ($current_task == ADMIN_TASK_ADVERTS) { ?>
<script type="text/javascript" src="../scripts/adsScript.js"></script>
<?php } ?>

<script type="text/javascript" src="../scripts/textSlider.js"></script>
<script type="text/javascript" src="../scripts/switchSubcategory.js"></script>
<script type="text/javascript" src="../scripts/confirmDelete.js"></script>
<?php
	if ($current_task == ADMIN_TASK_SUBCATEGORY_SELECTED_TEMPLATE_SET_SECTIONS)
	{
?>
	<script type="text/javascript" src="../scripts/scSetTemplateScript.js"></script>
<?php
	}
?>

<body>

<div id="pageContainer" align="center">
<input type="hidden" name="userKey" id="userKey" value="<?php echo $_SESSION['authentication'];?>" />
<input type="hidden" name="roleKey" id="roleKey" value="<?php echo $_SESSION['Role'];?>" />

	<?php include("../includes/header.php");?>
	<?php include("../includes/headerBasePanel.php");?>
	<div id="pageContentArea">
		<div id="infoPanelHolder">
			<a href="../processing/advertiseProducts.php" target="_blank" style="text-decoration:none; color:#000000">
				<div id="newMessageInfo">
					<div style="font-size:12px; font-weight:bold; margin-top:18px; visibility:hidden">You have (1<?php ?>) new message</div>
				</div>
			</a>
			<div id="scrollingInfo">
				<div id="marqueeArea">
					<div id="marquee">
      					<div id="text_slide">
                    		<div class="content">Your Site's CMS ver 1.0 from <a href="#" style="color:#FF9900">Havilah Creations Ltd</a>. &copy; Copyright <?php echo date("Y");?>. All rights reserved</div>
							<div class="content"></div>
						</div>
					</div>
				</div>
				<div id="viewPage">
					<div style="margin-top:8px;">
						<a href="#">View Page</a>
					</div>
				</div>
			</div>
		</div>
		
		<div id="userActionsMenuHolder">
			<?php include("../includes/user_actions.php"); ?>
		</div>
		
		<div id="userContentsHolder">
			<div id="currentTaskSwitchers">
				<?php require_once("../includes/taskSwitcherRender.php");?> <!-- include file for this sections elements-->
			</div>
			<div id="userMainContent">
				<?php
				switch ($current_task) //check for general task and render views
				{
				case GENERAL_TASK_SELECT_CATEGORY:
					include("formContent/tabCategory.php");
					$categoryCount = getCategory($dbConn);
				break;
				case GENERAL_TASK_CHANGE_PASSWORD:
					
				break;
				case GENERAL_TASK_CREATE_CATEGORY:
					include("formContent/createCategory.php");
				break;
				case GENERAL_TASK_SUB_CATEGORY_LISTING:
					include("formContent/subCategoryListing.php");
				break;
				case GENERAL_TASK_CREATE_SUB_CATEGORY:
					include("formContent/createSubCategory.php");
				break;
				case GENERAL_TASK_ACTIVE_CATEGORY:
					include("formContent/subCategoryListing.php");
				break;
				case GENERAL_TASK_PUBLISHED_ARTICLES:
					include("formContent/publishedListing.php");
				break;
				case GENERAL_TASK_UNPUBLISHED_ARTICLES:
					include("formContent/unpublishedListing.php");
				break;
				case GENERAL_TASK_WRITE_ARTICLE:
					include("formContent/writeArticle.php");
				break;
				case GENERAL_TASK_EDIT_ARTICLE:
					include("formContent/editArticle.php");
				break;
				case GENERAL_TASK_SET_RELATED_ARTICLES:
					include("formContent/setRelatedArticle.php");
				break;
				case GENERAL_TASK_RELATED_LINKS:
					include("formContent/viewRelatedArticle.php");
				break;
				case GENERAL_TASK_PREVIEW_ARTICLE:
					include("formContent/editArticle.php");
				break;
				case GENERAL_TASK_VIEW_TOP_STORIES:
					include("formContent/viewTopStories.php");
				break;
				case GENERAL_TASK_SEARCH:
					include("formContent/articleSearch.php");
				break;
				case GENERAL_TASK_VIEW_SUBCATEGORY_GROUP:
					switch ($sub_task)
					{
					case GENERAL_TASK_VIEW_SUBCATEGORY_GROUP_ARTICLES:
						include("formContent/scViewGroupArticles.php");
					break;
					default:
						include("formContent/scViewGroups.php");
					}
				break;
				default: //not a general task...check for specific task base on user
					switch ($user)
					{
					case USER_BASIC:
						switch ($current_task)
						{
						case BASIC_TASK_ACTIVE_CATEGORY:
						break;
						}
					break;
					case USER_ADMIN:
						switch ($current_task)
						{
						case ADMIN_TASK_ADD_USER:
							include("formContent/addUser.php");
						break;
						case ADMIN_TASK_POLL_BOOT:
							switch ($sub_task)
							{
							case ADMIN_TASK_POLL_BOOT_LISTING:
								include("formContent/viewWeeklyPoll.php");
							break;
							case ADMIN_TASK_POLL_BOOT_POST:
								include("formContent/postWeeklyPoll.php");
							break;
							case ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT:
								include("formContent/postWeeklyPoll.php");
							break;
							}
						break;
						case ADMIN_TASK_SELECT_TEMPLATE:
							include("formContent/selectTemplate.php");
						break;
						case ADMIN_TASK_SELECTED_TEMPLATE_SET_SECTIONS:
							include("formContent/setSectionDetails.php");
						break;
						case ADMIN_TASK_SELECT_TEMPLATE_SUBCATEGORY:
							include("formContent/scSelectTemplate.php");
						break;
						case ADMIN_TASK_SUBCATEGORY_SELECTED_TEMPLATE_SET_SECTIONS:
							include("formContent/scSetSectionDetails.php");
						break;
						case ADMIN_TASK_CREATE_SUBCATEGORY_GROUP:
							include("formContent/createGroup.php");
						break;
						case ADMIN_TASK_ADVERTS:
							switch ($sub_task)
							{
							case ADMIN_TASK_ADVERTS_SUB_UPLOAD:
								include("formContent/uploadAdverts.php");
							break;
							case ADMIN_TASK_ADVERTS_SUB_VIEW:
								include("formContent/viewAdverts.php");
							break;
							case ADMIN_TASK_ADVERTS_SUB_REGISTER_CLIENT:
								include("formContent/registerAdsClient.php");
							break;
							}
						break;
						case ADMIN_TASK_ABOUT_US:
							include("formContent/writeAboutUs.php");
						break;
						case ADMIN_TASK_HOW_TO_PLACE_ADS:
							include("formContent/placeAdsInstruction.php");
						break;
						case ADMIN_TASK_SET_TAB_INDEX:
							include("formContent/setTabIndex.php");
						break;
						case ADMIN_TASK_SECTION_INDEX_TEMPLATE:
							include("formContent/indexSetup.php");
						break;
						case ADMIN_TASK_MAINTENANCE:
							include("formContent/maintenance.php");
						break;
						}
					break;
					}
				}
				?>
				<div style="clear:both"></div>
			</div>
		</div>
		<div id="userActionSubtaskHolder">
			<?php include("../includes/rightSideMenu.php"); ?>
		</div>
		<div style="clear:left"></div>
	</div>
	<?php include("../includes/footer.php");?>
</div>

<div id="popper">
</div>
<div id="loading">
</div>
<div id="dlgShadow">
</div>
</body>
</html>
