<?php
	//listing for published articles in this category.
	$pageSize = 14; //reset to a higer value like 14.
	$countResult = $dbConn->query("SELECT COUNT(GroupGenID) FROM subcategorygroup WHERE SCGenID='$scGenID'");
	
	$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
	if ($countResult && $countResult->num_rows > 0)
	{
		$countRow = $countResult->fetch_array();
		$count = $countRow[0];
		$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
		$offset = $pageSize * ($current_page - 1);
		$article = $_GET['article'];
		
		$sql = sprintf("SELECT COUNT(ga.ArticleGenID) AS ArticleCount, sg.GroupName, sg.GroupGenID FROM subcategorygroup AS sg LEFT JOIN grouparticle AS ga ON sg.GroupGenID=ga.GroupGenID WHERE sg.SCGenID='%s' GROUP BY sg.GroupGenID ORDER BY sg.GroupName LIMIT $pageSize OFFSET $offset", $scGenID);

		$groupResult = $dbConn->query($sql);

		if ($groupResult && $groupResult->num_rows > 0)
		{
?>
	<table id="publishedListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:30px">
					MS
				</td>
				<td style="width:280px">
					Group Name
				</td>
				<td style="padding-left:0px; text-align:center; width:200px">
					Total Articles In Group
				</td>
				<td style="border-right:1px solid #FEE956; padding-left:0px; text-align:center;">
					Actions
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2" style="text-align:left">
					<?php if ($current_page > 1) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=view_subcategory_group&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&page_no=<?php echo $current_page - 1; ?>">&laquo; Previous</a><?php } ?>
				</td>
				<td style="text-align:center">
					<label style="color:#0033FF">Page: <?php echo $current_page; ?> / <?php echo $totalPages; ?> [Total Groups : <?php echo $count; ?>]</label>
				</td>
				<td style="padding-left:0px; padding-right:10px; text-align:right">
					<?php if ($current_page < $totalPages) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=view_subcategory_group&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&page_no=<?php echo $current_page + 1; ?>">Next &raquo;</a><?php } ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			for (; ($groupRow = $groupResult->fetch_array()) != FALSE; )
			{
		?>
			<tr>
				<td style="border-left:0px">
					<input type="radio" name="articleSelect" value="<?php echo $publishedRow['ArticleGenID']; ?>" />
				</td>
				<td style="border-left:0px">
					<?php echo $groupRow['GroupName']; ?>
				</td>
				<td style="text-align:center">
					<?php echo $groupRow['ArticleCount']; ?>
				</td>
				<td style="text-align:center">
					<label><a href="../processing/scdeleteGroup.php?user=<?php echo $_SESSION['authentication'];?>&role=<?php echo $_SESSION['Role'];?>&sel=<?php echo $selectedCategory;?>&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&gid=<?php echo $groupRow['GroupGenID'];?>">Delete</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=edit_article&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&gid=<?php echo $groupRow['GroupGenID'];?>">Edit</a></label><label>|</label><label><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=view_subcategory_group&sa=<?php echo GENERAL_TASK_VIEW_SUBCATEGORY_GROUP_ARTICLES;?>&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&gid=<?php echo $groupRow['GroupGenID'];?>">View Articles In Group</a></label>
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
			<div>No Group(s) created for this Subcategory. Click on Create Group to create a group.</div>
		</div>
<?php
		}
	}
?>