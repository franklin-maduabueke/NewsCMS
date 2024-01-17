<?php
	//listing for published articles in this category.
	require_once("../modules/lookConfigure.php");
	
	$sample = $_GET['sample'];
	$catId = $_GET['sel'];
	$scGenId = $_GET['sc'];
	
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
<form action="../processing/scSetTemplate.php" method="post">
	<input type="hidden" name="sectionCount" value="<?php echo $sectionsCount; ?>" />
	<input type="hidden" name="sample" value="<?php echo $_GET['sample']; ?>" />
	<input type="hidden" name="subcategory" value="<?php echo $_GET['sc']; ?>" />
	<input type="hidden" name="category" value="<?php echo $_GET['sel']; ?>" />
	
	<table id="publishedListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:50px">
					Section
				</td>
				<td style="width:400px">
					Section Heading
				</td>
				<td style="padding-left:0px; text-align:center;">
					Subcategory Link
				</td>
				<td style="padding-left:0px; text-align:center;">
					Subcategory Group
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td style="text-align:left">&nbsp;
					
				</td>
				<td style="text-align:center">&nbsp;
					
				</td>
				<td colspan="2" style="padding-left:0px; padding-right:10px; text-align:right">
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
					<input type="text" name="section<?php echo $i + 1; ?>Heading" style="width:300px; border:1px solid #CCCCCC" maxlength="100" />
				</td>
				<td>
					<select name="section<?php echo $i + 1; ?>subCategoryChoice" style="width:250px">
						<?php
							$firstChoice = NULL;
							for (; ($subcategoryRow = $subcategoryResult->fetch_array()) != FALSE; )
							{
								if (!isset($firstChoice))
									$firstChoice = $subcategoryRow['SCGenID'];  //use this to get the group for the first option.
						?>
							<option value="<?php echo $subcategoryRow['SCGenID']; ?>"><?php echo $subcategoryRow['CategoryName']. " [ " .$subcategoryRow['SubCatName'] . " ]"; ?></option>
						<?php
							}
							$subcategoryResult->data_seek(0);
						?>
					</select>
				</td>
				<td>
					<select name="section<?php echo $i + 1; ?>subCategoryGroup" style="width:200px">
						<!-- option gotten by ajax -->
						<option value="0">None</option>
						<?php
							//get the group that comes with the first subcategory inserted.
							$sql = sprintf("SELECT GroupName, GroupGenID FROM subcategorygroup WHERE SCGenID='%s'", $firstChoice);
							$groupResult = $dbConn->query($sql);
							if ($groupResult && $groupResult->num-rows > 0)
							{
								for (; ($gRow = $groupResult->fetch_array()) != FALSE; )
								{
							?>
									<option value="<?php echo $gRow['GroupGenID']; ?>"><?php echo $gRow['GroupName'];?></option>
						<?php
								}
							}
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
			<div>Error: Missing sections in template file.</div>
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