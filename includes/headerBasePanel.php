<div id="headerBasePanelHolder">
	<div class="catItemsHolder" style="margin-left:10px; padding-top:5px; margin-left:27px">
	<?php
		$sql = "SELECT CategoryName, CatGenID FROM category ORDER BY WebCatTabIndex ASC, CategoryName ASC";
		$resultCat = $dbConn->query($sql);
		if ($resultCat && $resultCat->num_rows > 0)
		{
			for ($i = 0; ($rowCat = $resultCat->fetch_array()) != FALSE && $i < 10; $i++ )
			{
	?>
			<label><?php if ($categoryId != $rowCat['CatGenID']) { ?><a href="<?php echo $prefix;?>processing/redirectView.php?sel=<?php echo $rowCat['CatGenID'];?>"><?php } ?><?php echo $rowCat['CategoryName'];?><?php if ($categoryId != $rowCat['CatGenID']) { ?></a><?php } ?></label>
	<?php
			}
			
			if ($resultCat->num_rows > 10)
			{
		?>
			<label><a href="#siteMap">More &raquo;&raquo;</a></label>
		<?php
			}
		?>
			<label><?php if (PAGE_NAME != "archive") { ?><a href="<?php echo $prefix;?>archive.php"><?php } ?>Archive<?php if (PAGE_NAME != "archive") { ?></a><?php } ?></label>
		<?php
		}
	?>
	</div>
</div>