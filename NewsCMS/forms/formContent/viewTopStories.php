<?php
	//listing for published articles in this category.
	$pageSize = 14; //reset to a higer value like 14.
	$countResult = $dbConn->query("SELECT COUNT(AID) FROM articles WHERE SCGenID='$scGenID' AND TopStory=1");
	
	$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
	if ($countResult && $countResult->num_rows > 0)
	{
		$countRow = $countResult->fetch_array();
		$count = $countRow[0];
		$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
		$offset = $pageSize * ($current_page - 1);
		$article = $_GET['article'];
		
		$sql = "SELECT ArticleGenID, Heading, Author, PublishDate, TextContent, ViewCount, TopStory, art.SCGenID, sc.CatGenID FROM articles AS art JOIN subcategory AS sc ON art.SCGenID=sc.SCGenID WHERE TopStory=1 AND art.SCGenID='$scGenID' ORDER BY art.PublishDate DESC, art.Heading ASC LIMIT $pageSize OFFSET $offset";

		$publishedResult = $dbConn->query($sql);

		if ($publishedResult && $publishedResult->num_rows > 0)
		{
?>
	<table id="publishedListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:30px">
					MS
				</td>
				<td style="width:280px">
					Heading
				</td>
				<td style="padding-left:0px; text-align:center; width:150px">
					Author
				</td>
				<td style="width:190px">
					Date &amp; Time
				</td>
				<td style="border-right:1px solid #FEE956; padding-left:0px; text-align:center;">
					Actions
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2" style="text-align:left">
					<?php if ($current_page > 1) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=view_top_stories&sc=<?php echo $scGenID;?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page - 1; ?>">&laquo; Previous</a><?php } ?>
				</td>
				<td colspan="2" style="text-align:center">
					<label style="color:#0033FF">Page: <?php echo $current_page; ?> / <?php echo $totalPages . " [Total Articles: $count]"; ?></label>
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">
					<?php if ($current_page < $totalPages) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=view_top_stories&sc=<?php echo $scGenID;?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page + 1; ?>">Next &raquo;</a><?php } ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			for (; ($publishedRow = $publishedResult->fetch_array()) != FALSE; )
			{
		?>
			<tr>
				<td style="border-left:0px">
					<input type="radio" name="articleSelect" value="<?php echo $publishedRow['ArticleGenID']; ?>" />
				</td>
				<td style="border-left:0px">
					<?php echo $publishedRow['Heading']; ?>
				</td>
				<td>
					<?php echo $publishedRow['Author']; ?>
				</td>
				<td>
					<?php echo date("j F Y, g:i A", strtotime($publishedRow['PublishDate'])); ?>
				</td>
				<td style="text-align:center">
					<label><a href="../processing/deleteArticle.php?user=<?php echo $_SESSION['authentication'];?>&sel=<?php echo $publishedRow['CatGenID'];?>&sc=<?php echo $publishedRow['SCGenID'];?>&article=<?php echo $publishedRow['ArticleGenID'];?>&tabName=<?php echo $tabName; ?>">Delete</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=edit_article&sc=<?php echo $scGenID;?>&article=<?php echo $publishedRow['ArticleGenID'];?>&tabName=<?php echo $tabName; ?>">Edit</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=related_links&sc=<?php echo $scGenID;?>&article=<?php echo $publishedRow['ArticleGenID'];?>&tabName=<?php echo $tabName; ?>">Related-Articles</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=preview_article&sc=<?php echo $scGenID;?>&article=<?php echo $publishedRow['ArticleGenID'];?>&tabName=<?php echo $tabName; ?>">Preview</a></label>	
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
			<div>No Article has been set as top story for this subcategory. Click on 'Write Article' to write an article and check Top Story.</div>
		</div>
<?php
		}
	}
?>