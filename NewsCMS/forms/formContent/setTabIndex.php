<?php
	//listing for published articles in this category.
	$pageSize = 14; //reset to a higer value like 14.
	$countResult = $dbConn->query("SELECT COUNT(CatID) FROM category");
	
	$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
	if ($countResult && $countResult->num_rows > 0)
	{
		$countRow = $countResult->fetch_array();
		$count = $countRow[0];
		$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
		$offset = $pageSize * ($current_page - 1);
		$article = $_GET['article'];
		
		$sql = "SELECT CategoryName, WebCatTabIndex, CatGenID FROM category ORDER BY WebCatTabIndex, CatID";

		$categoryResult = $dbConn->query($sql);

		if ($categoryResult && $categoryResult->num_rows > 0)
		{
?>
	<form action="../processing/setTabIndex.php" method="post">
	<table id="publishedListingTable" cellpadding="0" cellspacing="0" style="width:400px">
		<thead>
			<tr>
				<td style="width:150px">
					Category Name
				</td>
				<td style="border-right:1px solid #FEE956; padding-left:0px; text-align:center;">
					Index
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>&nbsp;</td>
				<td style="text-align:right">
					<input type="submit" class="clickable" name="submitButton" value="Upload" style="color:#FFFFFF; width:96px; height:31px; background-color:#ADD658; border:1px solid #81AA2B; margin-right:5px" />
				</td>
			</tr>
		</tfoot>
		<tbody>
			<input type="hidden" name="categoryCount" value="<?php echo $count;?>" />
		<?php
			for ($i= 1; ($catRow = $categoryResult->fetch_array()) != FALSE; $i++)
			{
		?>
			<tr>
				<input type="hidden" name="cat<?php echo $i; ?>GenID" value="<?php echo $catRow['CatGenID'];?>" />
				<td style="border-left:0px">
					<?php echo $catRow['CategoryName']; ?>
				</td>
				<td>
					<select name="cat<?php echo $i; ?>Control" style="width:200px; border:1px solid #FFFFFF">
						<?php
							for ($j = 0; $j < $count; $j++)
							{
						?>
							<option value="<?php echo $j?>" <?php if ($catRow['WebCatTabIndex'] == $j) echo 'selected="selected"'; ?>><?php echo $j;?></option>
						<?php
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
			<div>No category created. Create a category.</div>
		</div>
<?php
		}
	}
?>