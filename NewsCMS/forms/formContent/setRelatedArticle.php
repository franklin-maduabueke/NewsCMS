<?php
	//listing for published articles in this category.
	$pageSize = 14;
	$countResult = $dbConn->query("SELECT COUNT(AID) FROM articles WHERE SCGenID='$scGenID'");
	
	$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
	if ($countResult && $countResult->num_rows > 0)
	{
		$countRow = $countResult->fetch_array();
		$count = $countRow[0];
		$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
		$offset = $pageSize * ($current_page - 1);
		$article = $_GET['article'];
		
		/*$sql = "SELECT art.AID, art.Heading, ArticleGenID, Heading, Author, PublishDate, TextContent, ViewCount, TopStory, art.SCGenID FROM Articles  AS art WHERE art.ArticleGenID NOT IN(SELECT RelatedArticleGenID FROM ArticleRelated WHERE ParentArticleGenID='$article') AND SCGenID='$scGenID' AND ArticleGenID<>'$article' LIMIT $pageSize OFFSET $offset";*/
		$sql = sprintf("SELECT art.AID, art.Heading, ArticleGenID, Heading, Author, PublishDate, TextContent, ViewCount, TopStory, art.SCGenID FROM articles  AS art WHERE art.ArticleGenID NOT IN(SELECT RelatedArticleGenID FROM articlerelated WHERE ParentArticleGenID='$article') AND SCGenID IN(SELECT SCGenID FROM subcategory WHERE CatGenID='%s') AND ArticleGenID<>'$article' ORDER BY PublishDate DESC LIMIT $pageSize OFFSET $offset", $selectedCategory);

		$relatedResult = $dbConn->query($sql);

		if ($relatedResult && $relatedResult->num_rows > 0)
		{
?>
	<table id="publishedListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:58px">&nbsp;
					
				</td>
				<td style="width:299px; border-left:0px">
					Heading
				</td>
				<td style="padding-left:0px; text-align:center; width:250px">
					Author
				</td>
				<td style="width:150px">
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
					<?php if ($current_page > 1) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=set_related_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page - 1; ?>">&laquo; Previous</a><?php } ?>
				</td>
				<td colspan="2" style="text-align:center">
					<label style="color:#0033FF">Page: <?php echo $current_page; ?> / <?php echo $totalPages; ?></label>
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">
					<?php if ($current_page < $totalPages) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=set_related_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo $tabName; ?>&page_no=<?php echo $current_page + 1; ?>">Next &raquo;</a><?php } ?>
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
				<td style="border-left:0px">
					<?php echo $relatedRow['Heading']; ?>
				</td>
				<td style="text-align:center">
					<?php echo $relatedRow['Author']; ?>
				</td>
				<td style="text-align:center">
					<?php echo date("j F Y", strtotime($relatedRow['PublishDate'])); ?>
				</td>
				<td style="text-align:center">
					<a href="../processing/setRelatedArticle.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=set_related_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&article=<?php echo $_GET['article'];?>&r_article=<?php echo $relatedRow['ArticleGenID'];?>">Set As Related Article</a>
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
				<div>No Other Articles to relate this article with. Click on 'Write Article' to write an article.</div>
			</div>
<?php
		}
	}
?>