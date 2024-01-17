<div style="width:1050px; height:20px; background-color:#FFFFFF">
</div>
<div id="headerAdvertHolder" style="width:1050px; background-color:#FFFFFF">
		<?php include_once($prefix . "includes/adsHorizontal.php");?>
</div>
<div id="footer">
	<div style="width:1050px; height:40px; background-color:#FFFFFF"></div>
	<div id="footerContentHolder">
		<div class="catItemsHolder">
			<?php
		$sql = "SELECT CategoryName, CatGenID FROM category ORDER BY WebCatTabIndex ASC, CategoryName ASC";
		$resultCat = $dbConn->query($sql);
		if ($resultCat && $resultCat->num_rows > 0)
		{
			for (; ($rowCat = $resultCat->fetch_array()) != FALSE; )
			{
	?>
			<label><?php if ($categoryId != $rowCat['CatGenID']) { ?><a href="<?php echo $prefix;?>processing/redirectView.php?sel=<?php echo $rowCat['CatGenID'];?>"><?php } ?><?php echo htmlentities($rowCat['CategoryName']);?><?php if ($categoryId != $rowCat['CatGenID']) { ?></a><?php } ?></label>
	<?php
			}
		}
	?>
		<div style="clear:both"></div>
		</div>
		<div id="copyrights">
			<label>Terms of Service</label><label>|</label><label>Privacy Policy</label><label>|</label><label>&copy; 2016 <?php if (date("Y") > 2011) echo " - " . date("Y") . " ";?>Copyright . All right Reserved</label><label>|</label><label style="font-size:9px">Credit: <a href="#" style="color:#B8EBF8; font-size:9px">ISV</a></label>
		</div>
		
		<?php
			if (isset($_GET['stats']) && $_GET['stats'] == 1)
			{
		?>
		<div id="siteStats" style="font-size:12px; color:#999999; margin-top:5px;">
			<?php
			$visitDateResult = $dbConn->query("SELECT VisitDate FROM visitormonitor");
			
			//scan the data searching for the number of comma indicating the dates and likewise the hits on the site.
			$count = 0;
			for (; (($row = $visitDateResult->fetch_array()) != FALSE);)
			{
				$count += substr_count($row['VisitDate'], ",");
			}
			
			?>
			Site Hits: ( <?php echo number_format($count);?> )
		</div>
		<?php
			}
		?>
	</div>
</div>
<a name="siteMap"></a>
<div id="siteMapHolder" style="display:none">
	<?php
		//list the categorys and their subcategory
		$categoryResult = $dbConn->query("SELECT CategoryName, CatGenID FROM category ORDER BY WebCatTabIndex ASC, CategoryName");
		
		if ($categoryResult && $categoryResult->num_rows > 0)
		{
			for (;($catRow = $categoryResult->fetch_array()) != FALSE;)
			{
				$subcategoryResult = $dbConn->query(sprintf("SELECT sc.SubCatName, sc.SCGenID, cat.CategoryName, cat.CatGenID FROM subcategory AS sc JOIN category AS cat ON sc.CatGenID=cat.CatGenID WHERE sc.CatGenID='%s' ORDER BY sc.SCID", $catRow['CatGenID']));
				
			?>
				<div class="siteMapCategoryHolder">
					<ul>
						<li class="categoryName"><a href="<?php echo $prefix . "processing/redirectView.php?sel=" . $catRow['CatGenID'];?>"><?php echo $catRow['CategoryName'];?></a></li>
			<?php
				if ($subcategoryResult && $subcategoryResult->num_rows > 0)
				{
	?>
				<?php
					for (;($scRow = $subcategoryResult->fetch_array()) != FALSE;)
					{
				?>
					<li><a href="<?php echo $prefix . "processing/redirectView.php?sel=" . $catRow['CatGenID'] . "&sc=" . $scRow['SCGenID']; ?>"><?php echo $scRow['SubCatName'];?></a></li>
				<?php
					}
				}
			?>
					</ul>
				</div>
		<?php
			}
		}
	?>
	<div style="clear:both"></div>
</div>