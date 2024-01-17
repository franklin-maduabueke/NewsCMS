<?php
	$sql = "SELECT SubCatName, SCGenID, CatGenID FROM subcategory WHERE CatGenID='$categoryId' LIMIT 13";
	$scResult  = $dbConn->query($sql);
?>
<div id="newsHeaderBasePanelHolder">
		<div id="catSubCatHolder">
			<div id="catNameHolder">
				<div style="margin-top:5px; margin-left:12px; margin-right:3px;"><?php if (isset($_GET['sc']) && !empty($_GET['sc'])) { ?><a href="<?php echo $prefix;?>processing/redirectView.php?sel=<?php echo $categoryId?>" style="color:#FFFFFF; text-decoration:none; display:block"><?php } ?><?php echo $categoryName;?><?php if (isset($_GET['sc']) && !empty($_GET['sc'])) { ?></a><?php } ?></div>
			</div>
			<div id="subcategoryHolder">
				<?php
					if ($scResult && $scResult->num_rows > 0)
					{
						for (; ($scRow = $scResult->fetch_array()) != FALSE; )
						{
				?>
					<label><?php if ($_GET['sc'] != $scRow['SCGenID']) { ?><a href="<?php echo $prefix;?>processing/redirectView.php?sel=<?php echo $scRow['CatGenID'];?>&sc=<?php echo $scRow['SCGenID']; ?>"><?php } ?><?php echo $scRow['SubCatName'];?><?php if ($_GET['sc'] != $scRow['SCGenID']) { ?></a><?php } ?></label>
				<?php
						}
					}
				?>
			</div>
		</div>
</div>
<!--scrolling news control here. -->