<?php
	//script processing to set index template.
	require_once("../includes/user_checker.php");
	require_once("../modules/lookConfigure.php");
	require_once("../includes/commons.php");
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	
	$msg = "Error: Unable to set template for index";
	
	$sectionCount = $_POST['sectionCount'];

	$ini = parse_ini_file("../config/app.ini", true);
	$sample = "indexLook.php";
		
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
									//write to xml looknfeel file.
									$xml = '<?xml version="1.0" encoding="iso-8859-1"?><templates xmlns="http://www.w3schools.com"	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xsi:schemaLocation="http://www.w3schools.com template.xsd">' . $lookConf->getDocumentObject()->saveXML($looknfeel) . "</templates>"; //write modified xml for this looknfeel element to string.
 									$dom = $lookConf->getDocumentObject();
									if ($dom->loadXML($xml))
									{
											
										if ($dom->save($sitePath . "index.xml"))
										{
											//copy the xsd file for validation.
											$xsd = file_get_contents($xmls . "/template.xsd");
												
											if (isset($xsd))
											{
												//append the PAGE_NAME for the page at the top. This is the SCGenID for the subcategory.
												$templateFile = sprintf("<?php define('PAGE_NAME', '%s'); ?>\n", $scGenID) . $templateFile;
													
												file_put_contents($sitePath . "template.xsd", $xsd);

												$msg = "The template has been set";
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
	
	header("Location: ../forms/room.php?user=" . $_SESSION['authentication']. "&role=" . $_SESSION['Role']. "&tsk=" . GENERAL_TASK_SELECT_CATEGORY . "&msg=" . urlencode($msg));
	exit();
?>