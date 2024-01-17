<?php
	//listing for published articles in this category.
	$pageSize = 2; //reset to a higer value like 14.
	
	$ini = parse_ini_file("../config/app.ini", true);
	
	$foundTemplates = array(); //collection of found templates.
	$missingTemplates = array(); //collection of missing template names.
	
	$foundCount = 0;
	$missingCount = 0;
	
	if ($ini)
	{
		$templatesPath = $ini['PATHS']['templatesDir'];
		$xmls = $ini['PATHS']['xmlSchema'];

		//find the template samples images and the templates actual slice.
		//path is relative to room.php file in CMS.
		if (file_exists("../samples"))
		{
			$samples = scandir("../samples");
			
			//directory found.
			if ($samples && count($samples) > 0)
			{
				//find jpg files.
				$jpgFound = 0; //mix up in the elements without this.
				$jpgFiles = array();
				
				for ($i = 0; $i < count($samples); $i++)
				{
					if (strpos($samples[$i], ".jpg") && strpos($samples[$i], "Subcat") == TRUE) //select only jpg files that have the subcategory bar linked.
					{
						$jpgFiles[$jpgFound++] = $samples[$i];
					}
				}
				
				for ($i = 0; $i < count($jpgFiles); $i++) //for files we have.
				{
					//check for corresponding files.
					$sampleTempIniSection = $ini['SAMPLE_TEMPLATE'];
					
					if (array_key_exists(basename($jpgFiles[$i], ".jpg"), $sampleTempIniSection)) //do we find the path to the template for this
					{
						$template = $sampleTempIniSection[basename($jpgFiles[$i], ".jpg")];

						//locate the template.
						if (file_exists($templatesPath . "/" . $template))
							$foundTemplates[$foundCount++] = $jpgFiles[$i]; 
						else
							$missingTemplates[$missingCount++] = $jpgFiles[$i]; 
					}
				}
			}
		}
		
		$countResult = $foundCount;
		$totalPages = ($foundCount % $pageSize == 0) ? ($foundCount / $pageSize) : (((int)($foundCount / $pageSize)) + 1);
	
		$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
		$offset = $pageSize * ($current_page - 1);
	
	
		if ($countResult > 0) //found templates
		{
?>
	<table id="templatesListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:300px;text-align:center">
					Template Name
				</td>
				<td style="width:145px; text-align:center">
					Preview
				</td>
				<td style="border-right:1px solid #FEE956; padding-left:0px; text-align:center;">
					Actions
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td style="text-align:left">
					<?php if ($current_page > 1) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=<?php echo ADMIN_TASK_SELECT_TEMPLATE_SUBCATEGORY;?>&sc=<?php echo $scGenID?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page - 1; ?>">&laquo; Previous</a><?php } ?>
				</td>
				<td style="text-align:center">
					<label style="color:#0033FF">Page: <?php echo $current_page; ?> / <?php echo $totalPages; ?></label>
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">
					<?php if ($current_page < $totalPages) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=<?php echo ADMIN_TASK_SELECT_TEMPLATE_SUBCATEGORY;?>&sc=<?php echo $scGenID?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page + 1; ?>">Next &raquo;</a><?php } ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
				$drawCount = 0;
				for ($i = $offset; $i < $foundCount ; $i++)
				{
					if ($drawCount < $pageSize)
					{
			?>
			<tr>
				<td style="width:300px; text-align:center; border-left:0px">
					<?php echo basename($foundTemplates[$i], ".jpg"); ?>
				</td>
				<td style="width:150px; text-align:center">
					<img src="<?php echo "../samples/" . $foundTemplates[$i] ?>" style="width:78px; height:121px" />
				</td>
				<td style="border-right:0px; padding-left:0px; text-align:center;">
					<a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&sc=<?php echo $scGenID;?>&tsk=<?php echo ADMIN_TASK_SUBCATEGORY_SELECTED_TEMPLATE_SET_SECTIONS;?>&sample=<?php echo basename($foundTemplates[$i],".jpg") . ".php"; ?>&tabName=<?php echo $tabName; ?>">Use Template</a>
				</td>
			</tr>
			<?php
						$drawCount++;
					}
					else
						break;
				}
			?>
		</tbody>
	</table>
<?php
		}
		else
		{
?>
			<div class="noticeBoard">
				<div>No template available for use. Get some [www.havilahcreations.com] [megadon84@yahoo.ca] [dueal21@yahoo.co.uk].</div>
			</div>
<?php
		}
	}
	else
	{
?>
		<div class="noticeBoard">
			<div>Configuration file missing. Please reinstall application to fix.</div>
		</div>
<?php
	}
?>