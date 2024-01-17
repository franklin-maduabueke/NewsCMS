<?php
	//listing for published articles in this category.
	$pageSize = 14;
	$countResult = $dbConn->query(sprintf("SELECT COUNT(ParentArticleGenID) FROM articlerelated WHERE ParentArticleGenID='%s'", $_GET['article']));
	
	$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
	if ($countResult && $countResult->num_rows > 0)
	{
		$countRow = $countResult->fetch_array();
		$count = $countRow[0];
		$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
		$offset = $pageSize * ($current_page - 1);
		$article = $_GET['article'];
		
		$sql = "SELECT ar.ARTID , art.AID, art.Heading, art.ArticleGenID, art.Heading, art.Author, art.PublishDate, art.SCGenID FROM articlerelated AS ar JOIN articles AS art ON art.ArticleGenID=ar.RelatedArticleGenID AND ar.ParentArticleGenID='$article'
ORDER BY art.PublishDate DESC, art.Heading ASC LIMIT $pageSize OFFSET $offset";

		$relatedResult = $dbConn->query($sql);

		if ($relatedResult && $relatedResult->num_rows > 0)
		{
?>
	<table id="publishedListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:58px">&nbsp;
					
				</td>
				<td style="width:299px">
					Heading
				</td>
				<td style="padding-left:0px; text-align:center; width:150px">
					Author
				</td>
				<td style="width:229px">
					Publish Data & Time
				</td>
				<td style="border-right:1px solid #FEE956; padding-left:0px; text-align:center;">
					Actions
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2" style="text-align:left">
					<?php if ($current_page > 1) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=related_links&sc=<?php echo $scGenID;?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page - 1; ?>">&laquo; Previous</a><?php } ?>
				</td>
				<td colspan="2" style="text-align:center">
					<label style="color:#0033FF">Page: <?php echo $current_page; ?> / <?php echo $totalPages . " [Total Articles: $count]"; ?></label>
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">
					<?php if ($current_page < $totalPages) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=related_links&sc=<?php echo $scGenID;?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page + 1; ?>">Next &raquo;</a><?php } ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			for (; ($relatedRow = $relatedResult->fetch_array()) != FALSE; )
			{
		?>
			<tr>
				<td style="border-left:0px; text-align:center">
					<input type="checkbox"></input>
				</td>
				<td>
					<?php echo $relatedRow['Heading']; ?>
				</td>
				<td>
					<?php echo $relatedRow['Author']; ?>
				</td>
				<td>
					<?php echo date("j F Y", strtotime($relatedRow['PublishDate'])); ?>
				</td>
				<td style="text-align:center">
					<a href="../processing/unsetRelatedArticle.php?sel=<?php echo $_GET['sel'];?>&user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&tsk=<?php echo GENERAL_TASK_RELATED_LINKS;?>&sc=<?php echo $_GET['sc'];?>&tabName=<?php echo $_GET['tabName'];?>&article=<?php echo $article;?>&u_article=<?php echo $relatedRow['ArticleGenID'];?>">Unset As Related Article</a>
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
				<div>Articles have not been related with this article.</div>
			</div>
<?php
		}
	}
?>