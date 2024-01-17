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
	$scGenID = $_POST['subcategory'];
	
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
										$articleGroup = $_POST['section'. ($i + 1) . 'subCategoryGroup'];
										
										//set the properties of this section element.
										if (!$lookConf->setLooknFeelSectionDetails($section, $heading, $subcategoryLink))
										{
											$error = TRUE; //unable to set a sections information.
											break;
										}
										//include group if we are setting section to use articles from a certain group.
										if ($articleGroup != "0")
										{
											if (!$lookConf->addLooknFeelSectionGroup(trim($articleGroup), $section))
											{
												$error = TRUE;
												break;
											}
										}
										
									}
								}
								
								if (!$error)
								{
									//write to xml looknfeel file for this category.
									//get the category folder name.
									$sql = sprintf("SELECT sc.SubCatName, sc.WebFolderName AS scFolderName, cat.WebFolderName AS catFolderName, cat.CategoryName FROM subcategory AS sc JOIN category AS cat ON sc.CatGenID=cat.CatGenID WHERE sc.SCGenID='%s'", $scGenID);
									$scFolderNameResult = $dbConn->query($sql);
									
									if ($scFolderNameResult && $scFolderNameResult->num_rows > 0)
									{
										$xml = '<?xml version="1.0" encoding="iso-8859-1"?><templates xmlns="http://www.w3schools.com"	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xsi:schemaLocation="http://www.w3schools.com template.xsd">' . $lookConf->getDocumentObject()->saveXML($looknfeel) . "</templates>"; //write modified xml for this looknfeel element to string.
 										$dom = $lookConf->getDocumentObject();
										if ($dom->loadXML($xml))
										{
											$scFolderRow = $scFolderNameResult->fetch_array();
											
											if ($dom->save($sitePath . $scFolderRow['catFolderName'] . "/" . $scFolderRow['scFolderName'] . "/" . $scFolderRow['scFolderName']. ".xml"))
											{
												//copy the xsd file for validation.
												$xsd = file_get_contents($xmls . "/template.xsd");
												//get sample file corresponding template file.
												$templateFileName = $ini['SAMPLE_TEMPLATE'][basename($sample, ".php")];
												
												$templateFile = file_get_contents($templatesPath . "/" . $templateFileName);
												
												$readArticleFile = "<?php define('SUBCAT', 'subcategory'); ?>" . file_get_contents($templatesPath . "/readArticle.php");
												
												if (isset($xsd, $templateFile))
												{
													//append the PAGE_NAME for the page at the top. This is the SCGenID for the subcategory.
													$templateFile = sprintf("<?php define('PAGE_NAME', '%s'); ?>\n", $scGenID) . $templateFile;
													
													file_put_contents($sitePath . $scFolderRow['catFolderName'] . "/" . $scFolderRow['scFolderName'] . "/template.xsd", $xsd);
													file_put_contents($sitePath . $scFolderRow['catFolderName'] . "/" . $scFolderRow['scFolderName'] . "/index.php", $templateFile);
													file_put_contents($sitePath . $scFolderRow['catFolderName'] . "/" . $scFolderRow['scFolderName'] . "/readArticle.php", $readArticleFile);
													
													$dbConn->query(sprintf("UPDATE subcategory SET WebLooknFeelName='%s' WHERE SCGenID='%s'", basename($sample, ".php"), $scGenID));
													$msg = "The template has been set for " . $scFolderRow['SubCatName'];
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
	
	header("Location: ../forms/room.php?sel=". $catId . "&user=" . $_SESSION['authentication']. "&role=" . $_SESSION['Role']. "&t=active_category&tabName=". urlencode($scFolderRow['CategoryName'])."&tsk=" . GENERAL_TASK_SUB_CATEGORY_LISTING . "&msg=" . urlencode($msg));
	exit();
?>