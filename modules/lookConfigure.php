<?php
	//this class is used to parse the looknfeel files and set the section details
	
	class  LookConfigure
	{
		public function __construct($xmlFile)
		{
			$this->mDocument = new DOMDocument();
			
			if (isset($xmlFile) && !empty($xmlFile))
				if ($this->mDocument->load($xmlFile))
					$this->mfileLoaded = TRUE;
		}
		
		//loads the looknfeel file.
		public  function loadLookFile($path)
		{
			if (isset($path) && !empty($path))
				return  $this->mDocument->load($path);
		}
		
		public  function getLooknFeelWithID($id)
		{
			$allLooknFeel = $this->getAllLooknFeel();
			if ($allLooknFeel)
			{
				for ($i = 0; $i < $allLooknFeel->length; $i++)
				{
					$looknfeel = $allLooknFeel->item($i);
					if ($looknfeel->getAttribute("id") == $id)
						return $looknfeel;
				}
			}
			
			return FALSE;
		}
		
		public function getAllLooknFeel()
		{
			return $this->mDocument->getElementsByTagName("looknfeel");
		}
		
		//get the section in a looknfeel element with the id $secId
		//@param : $secId = id of the section to find.
		//@param : $looknfeel = the looknfeel element to search in.
		public function getLooknFeelSectionWithId($secId, DOMNode $looknfeel)
		{
			if (isset($secId, $looknfeel))
			{
				$sections = $looknfeel->childNodes;   //a looknfeel has only one sections element so i set 0. The sections are childNodes of sections.
				for ($i = 0; $i < $sections->length; $i++)
				{
					if ($sections->item($i)->tagName == "sections")
					{
						$sectionList = $sections->item($i)->childNodes;
						if ($sectionList->length > 0)
						{
							for ($j = 0; $j < $sectionList->length; $j++)
							{
								if ($sectionList->item($j)->tagName == "section")
								{
									if ($sectionList->item($j)->getAttribute("id") == $secId)
										return $sectionList->item($j);
								}
							}
						}
					}
				}
			}
			
			return FALSE;
		}
		
		//get the section heading and sectioncategory element text node values.
		//@param : $section =  a valid section element to fetch heading and subcategoryLink values
		public function  getLooknFeelSectionDetails(DOMNode $section)
		{
			$heading = "";
			$subCatID = "";
			if (isset($section))
			{
				$sectionElements = $section->childNodes;
				for ($i = 0; $i < $sectionElements->length; $i++) //we have the heading and the subcategoryLink elements....might do validation some more reliable way.
				{
					if ($sectionElements->item($i)->tagName == "heading")
						$heading = $sectionElements->item($i)->textContent;
					
					if ($sectionElements->item($i)->tagName == "subcategoryLink")
						$subCatID = $sectionElements->item($i)->textContent;
					
					if (isset($heading, $subCatID) && !empty($subCatID))
					{
						return  array(trim($heading), trim($subCatID), "heading" => trim($heading), "subcategoryLink" => trim($subCatID));
					}
				}
			}
			
			return FALSE;
		}
		
		//used to set the heading and subcategoryLink elements of a looknfeel section element.
		//@param : $section the section to set heading ans subcategoryLink element.
		public function setLooknFeelSectionDetails(DOMNode $section, $heading, $subcatLink)
		{
			if (isset($section, $heading, $subcatLink))
			{
				$headingNodes = $section->getElementsByTagName("heading");
				$subcatLinkNode = $section->getElementsByTagName("subcategoryLink");
				
				if (isset($headingNodes, $subcatLinkNode))
				{
					$headingNodes->item(0)->removeChild($headingNodes->item(0)->firstChild); //remove old text node
					$headingNodes->item(0)->appendChild($this->mDocument->createTextNode($heading)); //add new text node for the heading.
					
					$subcatLinkNode->item(0)->removeChild($subcatLinkNode->item(0)->firstChild);  //remove old text node
					$subcatLinkNode->item(0)->appendChild($this->mDocument->createTextNode($subcatLink)); //add new text node for the heading.
					
					return TRUE;
				}
			}
			
			return FALSE;
		}
		
		
		//used to count the number of sections in a looknfeel element
		public function  countLooknFeelSections(DOMNode $looknfeel)
		{
			$sectionCount = 0;
			if (isset($looknfeel))
			{
				$sections = $looknfeel->childNodes;   //a looknfeel has only one sections element so i set 0. The sections are childNodes of sections.
				
				for ($i = 0; $i < $sections->length; $i++)
					if ($sections->item($i)->tagName == "sections")
					{
						$sectionList = $sections->item($i)->childNodes;
						
						if ($sectionList->length > 0)
							for ($j = 0; $j < $sectionList->length; $j++)
								if ($sectionList->item($j)->tagName == "section")
									$sectionCount++;
					}
			}
				
			return $sectionCount;
		}
		
		
		//defined new element in xsd articleGroup for subcategory templates where a section
		//can filter articles in a certain group say in sports filter for only nigerian league.
		public function  getLooknFeelSectionGroup(DOMNode $section)
		{
			if (isset($section))
			{
				$sectionElements = $section->childNodes;
				for ($i = 0; $i < $sectionElements->length; $i++) //we have the heading and the subcategoryLink elements....might do validation some more reliable way.
				{
					if ($sectionElements->item($i)->tagName == "articleGroup")
						return $sectionElements->item($i);
				}
			}
			
			return FALSE;
		}
		
		public function addLooknFeelSectionGroup($groupId, DOMNode $section)
		{
			$groupId = trim($groupId);
			if (isset($section) && strlen(trim($groupId)) == 10)
			{
				$sectionElements = $section->childNodes;
				
				//remove all predecleared group elements cause we should only have this one.
				for ($i = 0; $i < $sectionElements->length; $i++)
				{
					if ($sectionElements->item($i)->tagName == "articleGroup")
						$section->removeChild( $sectionElements->item($i) );
				}
				
				//create an articleGroup element.
				$section->appendChild($this->mDocument->createElement("articleGroup", $groupId));
				return TRUE;
			}
			
			return FALSE;
		}
		
		public function getDocumentObject()
		{
			return $this->mDocument;
		}
		
		public function isXMLFileLoaded()
		{
			return $this->mfileLoaded;
		}
		
		private $mDocument;
		private $mfileLoaded; //indicates if an xml file has been loaded
	}
?>