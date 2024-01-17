<?php
	//listing for published articles in this category.
	$pageSize = 14; //reset to a higer value like 14.
	$countResult = $dbConn->query(sprintf("SELECT COUNT(ArticleGenID) FROM grouparticle WHERE GroupGenID='%s'", $_GET['gid']));
	
	$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
	if ($countResult && $countResult->num_rows > 0)
	{
		$countRow = $countResult->fetch_array();
		$count = $countRow[0];
		$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
		$offset = $pageSize * ($current_page - 1);
		$article = $_GET['article'];
	
		$sql = sprintf("SELECT art.ArticleGenID, art.Heading, art.Author, art.PublishDate, art.TextContent, art.ViewCount, art.TopStory, art.SCGenID FROM articles AS art JOIN grouparticle AS ga ON art.ArticleGenID=ga.ArticleGenID WHERE ga.GroupGenID='%s' ORDER BY art.PublishDate DESC, art.AID DESC LIMIT $pageSize OFFSET $offset", $_GET['gid']);

		$groupArticleResult = $dbConn->query($sql);

		if ($groupArticleResult && $groupArticleResult->num_rows > 0)
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
					<?php if ($current_page > 1) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=<?php echo GENERAL_TASK_PUBLISHED_ARTICLES;?>&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&page_no=<?php echo $current_page - 1; ?>">&laquo; Previous</a><?php } ?>
				</td>
				<td colspan="2" style="text-align:center">
					<label style="color:#0033FF">Page: <?php echo $current_page; ?> / <?php echo $totalPages; ?> [Total Articles : <?php echo $count; ?>]</label>
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">
					<?php if ($current_page < $totalPages) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=<?php echo GENERAL_TASK_PUBLISHED_ARTICLES;?>&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&page_no=<?php echo $current_page + 1; ?>">Next &raquo;</a><?php } ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			for (; ($groupArticleRow = $groupArticleResult->fetch_array()) != FALSE; )
			{
		?>
			<tr>
				<td style="border-left:0px">
					<input type="radio" name="articleSelect" value="<?php echo $publishedRow['ArticleGenID']; ?>" />
				</td>
				<td style="border-left:0px">
					<?php echo $groupArticleRow['Heading']; ?>
				</td>
				<td>
					<?php echo $groupArticleRow['Author']; ?>
				</td>
				<td>
					<?php echo date("j F Y, g:i A", strtotime($groupArticleRow['PublishDate'])); ?>
				</td>
				<td style="text-align:center">
					<label><a href="../processing/deleteArticle.php?user=<?php echo $_SESSION['authentication'];?>&sel=<?php echo $groupArticleRow['CatGenID'];?>&sc=<?php echo $groupArticleRow['SCGenID'];?>&article=<?php echo $groupArticleRow['ArticleGenID'];?>&tabName=<?php echo urlencode($tabName); ?>">Delete</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=<?php echo GENERAL_TASK_EDIT_ARTICLE;?>&sc=<?php echo $scGenID;?>&article=<?php echo $groupArticleRow['ArticleGenID'];?>&tabName=<?php echo urlencode($tabName); ?>">Edit</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=<?php echo GENERAL_TASK_RELATED_LINKS;?>&sc=<?php echo $scGenID;?>&article=<?php echo $groupArticleRow['ArticleGenID'];?>&tabName=<?php echo urlencode($tabName); ?>">Related-Articles</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=<?php echo GENERAL_TASK_PREVIEW_ARTICLE;?>&sc=<?php echo $scGenID;?>&article=<?php echo $groupArticleRow['ArticleGenID'];?>&tabName=<?php echo urlencode($tabName); ?>">Preview</a></label>	
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
			<div>No Published Articles Yet. Click on 'Write Article' to write an article.</div>
		</div>
<?php
		}
	}
?>