<div id="indexTemplateSampleHolder" style="width:274px; height:400px; background-color:#FFFFFF; border:5px solid #000000; float:left">
	<img src="../samples/indexLook.jpg" style="width:100%; height:100%" />
</div>

<div style="float:left; margin-left:40px; width:600px; height:auto;">
	<?php
	//listing for published articles in this category.
	require_once("../modules/lookConfigure.php");
	
	$sample = "indexLook.php";
	
	//parse ini get path for xml
	$ini = parse_ini_file("../config/app.ini", true);
	
	if ($ini)
	{
		//$templatesPath = $ini['PATHS']['templatesDir'];
		$xmls = $ini['PATHS']['xmlSchema'];
		
		if (isset($xmls) && file_exists($xmls))
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
					$sectionsCount = $lookConf->countLooknFeelSections($looknfeel);

				$sql = "SELECT sc.SCGenID, sc.CatGenID, cat.CategoryName, sc.SubCatName FROM subcategory AS sc JOIN category AS cat ON sc.CatGenID=cat.CatGenID";

				$subcategoryResult = $dbConn->query($sql);

				if ($subcategoryResult && $subcategoryResult->num_rows > 0)
				{
?>
<form action="../processing/indexSetTemplate.php" method="post">
	<input type="hidden" name="sectionCount" value="<?php echo $sectionsCount; ?>" />
	<table id="publishedListingTable" cellpadding="0" cellspacing="0" style="width:100%;">
		<thead>
			<tr>
				<td style="width:80px">
					Section
				</td>
				<td style="width:260px">
					Section Heading
				</td>
				<td style="padding-left:0px; text-align:center;">
					Subcategory Link
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td style="text-align:left">&nbsp;
					
				</td>
				<td style="text-align:center">&nbsp;
					
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">
					<input type="submit" class="clickable" value="Set Template" style="color:#FFFFFF; background-color:#81AA2B; border:1px solid #FFFFFF; width:120px; height:30px;" />
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php		
					for ($i = 0; $i < $sectionsCount; $i++)
					{
		?>
			<tr>
				<td style="border-left:0px; text-align:center">
					<?php echo $i + 1; ?>
				</td>
				<td>
					<input type="text" name="section<?php echo $i + 1; ?>Heading" style="width:230px; border:1px solid #CCCCCC" maxlength="100" />
				</td>
				<td>
					<select name="section<?php echo $i + 1; ?>subCategoryChoice" style="width:200px">
						<?php
							for (; ($subcategoryRow = $subcategoryResult->fetch_array()) != FALSE; )
							{
						?>
							<option value="<?php echo $subcategoryRow['SCGenID']; ?>"><?php echo $subcategoryRow['CategoryName']. " [ " .$subcategoryRow['SubCatName'] . " ]"; ?></option>
						<?php
							}
							$subcategoryResult->data_seek(0);
						?>
					</select>
				</td>
			</tr>
		<?php
					}
		?>
		</tbody>
	</table>
</form>
<?php
				}
				else
				{
?>
		<div class="noticeBoard">
			<div>No Published Articles Yet. Click on 'Write Article' to write an article.</div>
		</div>
<?php
				}
			}
		}
	}
	else
	{
?>
		<div class="noticeBoard">
			<div>Error: Missing application configuration file.</div>
		</div>
<?php
	}
?>
</div>
<div style="clear:both"></div>
