<?php
	//listing for published articles in this category.
	$pageSize = 14; //reset to a higer value like 14.
	$countResult = $dbConn->query("SELECT COUNT(ADID) FROM adverts");
	
	$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
	if ($countResult && $countResult->num_rows > 0)
	{
		$countRow = $countResult->fetch_array();
		$count = $countRow[0];
		$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
		$offset = $pageSize * ($current_page - 1);
		$article = $_GET['article'];
		
		$sql = "SELECT ads.ClientGenID, ads.AdvertType, ads.AdvertViewCount, ads.Duration, ads.AdsGenID, client.Email FROM adverts AS ads JOIN advertclient AS client ON client.ClientGenID=ads.ClientGenID LIMIT $pageSize OFFSET $offset";

		$adsResult = $dbConn->query($sql);

		if ($adsResult && $adsResult->num_rows > 0)
		{
?>
	<table id="publishedListingTable" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td style="width:90px">
					ClientGenID
				</td>
				<td style="width:200px">
					Client Email
				</td>
				<td style="padding-left:0px; text-align:center; width:150px">
					Advert Type
				</td>
				<td style="width:150px">
					Advert Views
				</td>
				<td style="width:150px">
					Duration
				</td>
				<td style="border-right:1px solid #FEE956; padding-left:0px; text-align:center;">
					Actions
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2" style="text-align:left">
					<?php if ($current_page > 1) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=published_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&page_no=<?php echo $current_page - 1; ?>">&laquo; Previous</a><?php } ?>
				</td>
				<td colspan="2" style="text-align:center">
					<label style="color:#0033FF">Page: <?php echo $current_page; ?> / <?php echo $totalPages; ?> [Total Articles : <?php echo $count; ?>]</label>
				</td>
				<td colspan="2" style="padding-left:0px; padding-right:10px; text-align:right">
					<?php if ($current_page < $totalPages) { ?><a href="room.php?sel=<?php echo $selectedCategory;?>&user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=published_articles&sc=<?php echo $scGenID;?>&tabName=<?php echo urlencode($tabName); ?>&page_no=<?php echo $current_page + 1; ?>">Next &raquo;</a><?php } ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			for (; ($adsRow = $adsResult->fetch_array()) != FALSE; )
			{
		?>
			<tr>
				<td style="border-left:0px">
					<?php echo $adsRow['ClientGenID']; ?>
				</td>
				<td>
					<?php echo $adsRow['Email']; ?>
				</td>
				<td>
					<?php echo $adsRow['AdvertType']; ?>
				</td>
				<td>
					<?php echo $adsRow['AdvertViewCount']; ?>
				</td>
				<td>
					<?php echo date("j M, Y", $adsRow['Duration']); ?>
				</td>
				<td style="text-align:center">
					<label><a href="../processing/deleteAdvert.php?user=<?php echo $_SESSION['authentication']; ?>&role=<?php echo $_SESSION['Role'];?>&tsk=adverts&sa=view_adverts&ad=<?php echo $adsRow['AdsGenID'];?>">Delete</a></label>
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