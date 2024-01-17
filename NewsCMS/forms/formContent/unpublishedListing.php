<?php
	//listing for unpublished articles in this category.
	//listing for published articles in this category.
	$pageSize = 14; //reset to a higer value like 14.
	$countResult = $dbConn->query("SELECT COUNT(AID) FROM articles WHERE PublishDate='0000-00-00' AND SCGenID='$scGenID'");
	
	$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
	if ($countResult && $countResult->num_rows > 0)
	{
		$countRow = $countResult->fetch_array();
		$count = $countRow[0];
		$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
		$offset = $pageSize * ($current_page - 1);
		$article = $_GET['article'];
		
		$sql = "SELECT ArticleGenID, Heading, Author, PublishDate, TextContent, ViewCount, TopStory, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON art.SCGenID=sc.SCGenID WHERE PublishDate='0000-00-00' AND art.SCGenID='$scGenID' ORDER BY art.AID DESC, art.AID DESC LIMIT $pageSize OFFSET $offset";

		$unpublishedResult = $dbConn->query($sql);

		if ($unpublishedResult && $unpublishedResult->num_rows > 0)
		{
?>
	<table id="publishedListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:299px">
					Heading
				</td>
				<td style="padding-left:0px; text-align:center; width:250px">
					Author
				</td>
				<td style="width:150px; text-align:center">
					Date &amp; Time
				</td>
				<td style="border-right:1px solid #FEE956; padding-left:0px; text-align:center;">
					Actions
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>
					<?php if ($current_page > 1) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=published_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page - 1; ?>">&laquo; Previous</a><?php } ?>
				</td>
				<td colspan="2" style="text-align:center">
					<label style="color:#0033FF">Page: <?php echo $current_page; ?> / <?php echo $totalPages; ?> [Total Articles : <?php echo $count; ?>]</label>
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">
					<?php if ($current_page < $totalPages) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=published_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page + 1; ?>">Next &raquo;</a><?php } ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			for (; ($unpublishedRow = $unpublishedResult->fetch_array()) != FALSE; )
			{
		?>
			<tr>
				<td style="border-left:0px">
					<?php echo $unpublishedRow['Heading']; ?>
				</td>
				<td style="text-align:center">
					<?php echo $unpublishedRow['Author']; ?>
				</td>
				<td style="text-align:center">
					Not set
				</td>
				<td style="text-align:center">
					<label><a href="../processing/deleteArticle.php?user=<?php echo $_SESSION['authentication'];?>&sel=<?php echo $unpublishedRow['CatGenID'];?>&sc=<?php echo $unpublishedRow['SCGenID'];?>&article=<?php echo $unpublishedRow['ArticleGenID'];?>&tabName=<?php echo $tabName; ?>">Delete</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=edit_article&sc=<?php echo $scGenID;?>&article=<?php echo $unpublishedRow['ArticleGenID'];?>&tabName=<?php echo $tabName; ?>">Edit</a></label><label>|</label><label><a href="../processing/articlePostUnpublished.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=related_links&sc=<?php echo $scGenID;?>&article=<?php echo $unpublishedRow['ArticleGenID'];?>&tabName=<?php echo $tabName; ?>">Publish</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=preview_article&sc=<?php echo $scGenID;?>&article=<?php echo $unpublishedRow['ArticleGenID'];?>&tabName=<?php echo $tabName; ?>">Preview</a></label>	
				</td>
			</tr>
		<?php
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
				<div>No Unpublished Articles. To write an article that won't be viewable by visitors writle an article and click upload.</div>
			</div>
<?php
		}
	}
?>