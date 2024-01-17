<?php
	//write comments and view comments holder here.
	if ($dbConn->ping())
	{	
?>
<a name="makeComment"></a>

<div id="commentsHolder">
	<?php
		//listing for published articles in this category.
		$pageSize = 7; //reset to a higer value like 14.
		$commentCountResult = $dbConn->query(sprintf("SELECT COUNT(ArticleGenID) AS CommentCount FROM comments WHERE ArticleGenID='%s'", $articleId));
	
		$current_page = (isset($_GET['page_no'])) ?  $_GET['page_no'] : 1;
	
		if ($commentCountResult && $commentCountResult->num_rows > 0)
		{
			$countRow = $commentCountResult->fetch_array();
			$count = $countRow[0];
			$totalPages = ($count % $pageSize == 0) ? ($count / $pageSize) : (((int)($count / $pageSize)) + 1);
			
			$offset = $pageSize * ($current_page - 1);
			
			$commentCountRow = $commentCountResult->fetch_array();
			$commentCount = $commentCountRow['CommentCount'];
			
			$sql = sprintf("SELECT c.Content, c.CommentGenID, c.PostDateTime, m.MemberGenID, m.NickName, m.Avatar FROM comments AS c JOIN members AS m ON c.MemberGenID=m.MemberGenID WHERE c.ArticleGenID='%s' ORDER BY PostDateTime ASC, CommentID LIMIT $pageSize OFFSET $offset", $articleId);
			$commentsResult = $dbConn->query($sql);
			
			if ($commentsResult && $commentsResult->num_rows > 0)
			{
			?>
			<div id="commentPageControl"><?php if ($current_page > 1) {?><a href="<?php echo $_SERVER['REQUEST_URI'] . "&page_no=1";?>">First</a><?php } ?><?php if ($current_page > 1) {?>|<a href="<?php echo $_SERVER['REQUEST_URI'] . "&page_no=" . ($current_page - 1);?>">&lt;&lt; Prev</a><?php }?><?php if ($current_page < $totalPages) {?>|<a href="<?php echo $_SERVER['REQUEST_URI'] . "&page_no=" . ($current_page + 1); ?>">Next &gt;&gt;</a><?php }?><?php if ($current_page< $totalPages) {?>|<a href="<?php echo $_SERVER['REQUEST_URI'] . "&page_no=" . $totalPages;?>">Last</a><?php } ?></div>
			<?php
				for ($i = 1; ($cRow = $commentsResult->fetch_array()) != FALSE; $i++)
				{
	?>
	<div class="avatarAndComentsHolder">
		
		<div class="avatarHolder">
			<?php
				if (strlen($cRow['Avatar']) == 0)
				{
			?>
					<img src="<?php echo $prefix;?>images/defaultAvatar<?php echo ($i % 2 == 0) ? 1 : 2;?>.jpg" />
			<?php
				}
				else
				{
			?>
					<img src="<?php echo $prefix;?>processing/getAvatar.php?m=<?php echo $cRow['MemberGenID'];?>&i=<?php echo $i;?>" />
			<?php
				}
			?>
		</div>
		
		<div class="commentsHolder">
			<div class="commentLinksHolder">
				<label class="personName"><?php echo $cRow['NickName'];?></label>
				<label class="reportAbuse"><a href="#">Report Abuse</a></label>
				<label class="separatorTime">|</label>
				<label class="timePeriod">
					<?php
						//if less than 5 min say just now.
						//if less than 24 hrs say number of hours since post
						//else show date
						$seconds10min = 60 * 5;
						$seconds24hrs = 3600 * 24;
						
						$time = strtotime($cRow['PostDateTime']);
						$duration = time() - $time;

						if ($duration <= $seconds10min)
							echo "Just now";
						else
						{	
							if ($duration < 3600) //when less than an hour
								echo ceil($duration / 60) . "min(s) ago";
							elseif ($duration < 3600 * 24)
								{
									echo "about " . ceil($duration / 3600) . "hr";
									if (($duration / 3600 > 1))
										echo "s";
									echo " ago";
								}
								else
									echo date("D j, M Y H:i", strtotime($cRow['PostDateTime']));
						}
					?>
				</label>
			</div>
			
			<div class="comment">
				<?php echo $cRow['Content'];?>
			</div>
		</div>
		<div style="clear:both; height:5px;"></div>
	</div>
	<?php
				}
			}
		}
	?>
	
	<div id="addCommentHolder">
	<form action="<?php echo $prefix;?>processing/postComment.php" method="post">
		<input type="hidden" name="articleId" value="<?php echo $articleId;?>"  />
		<div id="addCommentHeading" style="font-size:12px; text-align:left; margin-top:10px;margin-left:20px">Post Comment</div>
		<div id="commentEditorHolder">
			<textarea maxlength="500" name="commentEditor"></textarea>
		</div>
		<div style="text-align:right; font-size:12px; color:#999999; margin-right:20px; margin-top:10px"><label id="leftCount">500</label> characters remaining</div>
		
		<?php
			//check if the user cookie exists. Use for comments box.			
			if (!isset($_COOKIE['member_nigeriannewsnetwork']))
			{
		?>
		<div style="text-align:left; margin-left:20px; margin-top:10px; margin-bottom:10px;">
			<label style="font-size:12px; color:#666666;font-weight:bold">Appear as:</label> <input type="text" id="apperance" name="apperance" maxlength="20" style="width:300px; border:2px solid #FFFFFF; margin-bottom:5px" /> <label class="requiredField">*</label>
			<br/>
			<label style="font-size:12px; color:#666666; margin-right:36px; font-weight:bold">Email:</label><input type="text" id="email" name="email" maxlength="50" style="width:300px; border:2px solid #FFFFFF; margin-bottom:5px" /> <label class="requiredField">*</label>
		</div>
		<?php
			}
			else
			{
		?>
		<div style="text-align:left; font-size:12px; color:#666666; margin-left:20px;font-weight:bold">You will appear as:</div>
		<div style="text-align:left; margin-left:20px; margin-top:10px">
			<input type="text" id="commenterName" name="commenterName" style="width:300px; border:2px solid #FFFFFF" value="<?php echo $_COOKIE['member_nigeriannewsnetwork']['nickname'];?>" disabled="disabled" />
			<a href="#">Change Photo</a> <a href="<?php echo $prefix;?>processing/changeCommentName.php">Change Name</a>
		</div>
		<?php
			}
		?>
		
		<!--
		<div id="generatedImageCode" name="generatedImageCode">
			<img src="<?php echo $prefix;?>processing/genCommentCode.php"  />
		</div>
		-->
		<div style="clear:both"></div>
		<div style="text-align:left; margin-left:20px; margin-top:10px; margin-bottom:10px">
			<input type="submit" name="SubmitComment" id="SubmitComment" value="Post Comment" style="width:194px; height:42px; background-color:#FFCC33; border:1px solid #FFFFFF; font-weight:bold; background-image:url(<?php echo $prefix;?>images/commentBtnBg.jpg); background-repeat:repeat-x" class="clickable" />
			<a id="clearTxt" class="clickable">Clear</a>
		</div>
	</form>
	<!-- site does not have any need for registration now. When a user registers for comments the first time a cookie will be saved to the users system
	and his information on the database. When next the user accesses a page with the comments box the cookie will be checke if non is found then the users will
	have to provide information again. If found in the database then same avatar will be used for the user
		<div style="margin-top:10px;float:right;">
			<input type="button" name="Login" id="Login" value="Login" style="margin-right:5px;width:100px; height:42px; background-color:#FFCC33; border:1px solid #FFFFFF; font-weight:bold; background-image:url(<?php echo $prefix;?>images/commentBtnBg.jpg); background-repeat:repeat-x" class="clickable" />
			<input type="button" name="SignUp" id="SignUp" value="Sign Up" style="margin-right:20px;width:100px; height:42px; background-color:#FFCC33; border:1px solid #FFFFFF; font-weight:bold; background-image:url(<?php echo $prefix;?>images/commentBtnBg.jpg); background-repeat:repeat-x" class="clickable" />
		</div>
	-->
	</div>
</div>
<script type="text/javascript">
	$().ready( function () {
		$.getScript("<?php echo $prefix;?>scripts/comments.js");
	});
</script>
<?php
	}
?>