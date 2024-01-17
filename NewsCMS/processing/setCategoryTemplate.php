<?php
	//script processing the set category template.
	require_once("../config/db.php");
	require_once("../modules/mysqli.php");
	require_once("../includes/user_checker.php");
	require_once("../includes/commons.php");
	require_once("../modules/lookConfigure.php");
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	$msg = "Error: Unable to set template for category ( " . $_GET['tabName'] . " )";
	
	$sample = $_POST['sample'];
	$catId = $_POST['category'];
	$sectionCount = $_POST['sectionCount'];
	
	$dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(DB_NAME))
	{
		$ini = parse_ini_file("../config/app.ini", true);
		
		if ($ini)
		{
			$templatesPath = $ini['PATHS']['templatesDir'];
			$xmls = $ini['PATHS']['xmlSchema'];
			$sitePath = $ini['PATHS']['site_path'];
			
			if (isset($templatesPath, $xmls))
			{
				if (file_exists($templatesPath) && file_exists($xmls))
				{
					//found directories.
					//get the dom for the looknfeel.
					$lookConf = new LookConfigure($xmls . "/template.xml");
					if ($lookConf->isXMLFileLoaded())
					{
						//get looknfeel definition based choice.
						$looknfeelName = basename($sample, ".php");
						$looknfeel = $lookConf->getLooknFeelWithID("$looknfeelName");

						if ($looknfeel)
						{
							$defSectionCount = $lookConf->countLooknFeelSections($looknfeel);
							
							if ($defSectionCount == $sectionCount) //check if the definition file has section elements equal to the number of sections set.
							{
								$error = FALSE;
								
								for ($i = 0; $i < $sectionCount; $i++)
								{
									$id = "s" . ($i + 1);
									
									$section = $lookConf->getLooknfeelSectionWithID($id, $looknfeel);
									if ($section)
									{
										//set the section heading.
										$heading = $_POST["section". ($i + 1) . "Heading"];
										$subcategoryLink = $_POST['section'. ($i + 1) . 'subCategoryChoice'];
										
										//set the properties of this section element.
										if (!$lookConf->setLooknFeelSectionDetails($section, $heading, $subcategoryLink))
										{
											$error = TRUE; //unable to set a sections information.
											break;
										}
									}
								}
								
								if (!$error)
								{
									//write to xml looknfeel file for this category.
									//get the category folder name.
									$sql = sprintf("SELECT CategoryName, WebFolderName FROM category WHERE CatGenID='%s'", $catId);
									$catFolderNameResult = $dbConn->query($sql);
									
									if ($catFolderNameResult && $catFolderNameResult->num_rows > 0)
									{
										$xml = '<?xml version="1.0" encoding="iso-8859-1"?><templates xmlns="http://www.w3schools.com"	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xsi:schemaLocation="http://www.w3schools.com template.xsd">' . $lookConf->getDocumentObject()->saveXML($looknfeel) . "</templates>"; //write modified xml for this looknfeel element to string.
 										$dom = $lookConf->getDocumentObject();
										if ($dom->loadXML($xml))
										{
											$catFolderRow = $catFolderNameResult->fetch_array();
											
											if ($dom->save($sitePath . $catFolderRow['WebFolderName'] . "/" . $catFolderRow['WebFolderName']. ".xml"))
											{
												//copy the xsd file for validation.
												$xsd = file_get_contents($xmls . "/template.xsd");
												//get sample file corresponding template file.
												$templateFileName = $ini['SAMPLE_TEMPLATE'][basename($sample, ".php")];
												
												$templateFile = file_get_contents($templatesPath . "/" . $templateFileName);
												
												//$readArticleFile = file_get_contents($templatesPath . "/readArticle.php");
												
												if (isset($xsd, $templateFile))
												{
													//append the PAGE_NAME for the page at the top. This is the CategoryGenID for the category.
													$templateFile = sprintf("<?php define('PAGE_NAME', '%s'); ?>\n", $catId) . $templateFile;
													
													file_put_contents($sitePath . $catFolderRow['WebFolderName'] . "/template.xsd", $xsd);
													file_put_contents($sitePath . $catFolderRow['WebFolderName'] . "/index.php", $templateFile);
													//file_put_contents($sitePath . $catFolderRow['WebFolderName'] . "/readArticle.php", $readArticleFile);
													
													$dbConn->query(sprintf("UPDATE category SET WebLooknFeelName='%s' WHERE CatGenID='%s'", basename($sample, ".php"), $catId));
													$msg = "The template has been set for " . $catFolderRow['CategoryName'];
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	header("Location: ../forms/room.php?sel=". $catId . "&user=" . $_SESSION['authentication']. "&role=" . $_SESSION['Role']. "&t=active_category&tabName=". urlencode($catFolderRow['CategoryName'])."&tsk=" . GENERAL_TASK_SUB_CATEGORY_LISTING);
	exit();
?>