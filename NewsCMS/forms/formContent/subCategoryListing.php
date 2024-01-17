<div id="subCategoryListingHolder">
	<ul id="subCatItems">
		<?php
			//find subcategories.
			$sql = sprintf("SELECT SCGenID, SubCatName, CatGenID FROM subcategory WHERE CatGenID='%s'",$selectedCategory);
			$result = $dbConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				for (; ($row = $result->fetch_array()) != FALSE; )
				{
		?>
					<li><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=published_articles&sc=<?php echo $row['SCGenID'];?>&tabName=<?php echo urlencode($tabName); ?>" style="font-weight:bold; letter-spacing:1px;"><?php echo $row['SubCatName']; ?></a></li>
		<?php
				}
			}
		?>
		<li style="border-right:0px"><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=create_sub_category&tabName=<?php echo urlencode($tabName); ?>">Create Subcategory</a></li>
	</ul>
</div>