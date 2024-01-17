<?php
	require_once("../modules/ckeditor/ckeditor.php");
	
	//get the artilce content.
	$sql = sprintf("SELECT art.Heading, art.Author, art.PublishDate, art.TextContent, art.TopStory, art.PublishDate FROM articles AS art WHERE art.ArticleGenID='%s'", $_GET['article']);
	$articleResult = $dbConn->query($sql);
	$sql = sprintf("SELECT ArtPhotoID FROM articlephoto WHERE ArticleGenID='%s'", $_GET['article']);
	$articlePhoto = $dbConn->query($sql);
	$sql = sprintf("SELECT VideoContentID, VideoSnapShot FROM articlevideo WHERE ArticleGenID='%s'", $_GET['article']);
	$articleVideo = $dbConn->query($sql);
	$sql = sprintf("SELECT FlashContentID, VideoSnapShot FROM articleflash WHERE ArticleGenID='%s'", $_GET['article']);
	$articleFlash = $dbConn->query($sql);
	
	$sql = sprintf("SELECT GroupGenID FROM grouparticle WHERE ArticleGenID='%s'", $_GET['article']);
	$articleGroup = $dbConn->query($sql);
	
	$articleRow = $articleResult->fetch_array();
	
?>
<form action="../processing/editArticle.php" method="post" name="writeArticleForm" enctype="multipart/form-data">
<div style="width:997px; height:auto; min-height:400px;background-color:#FFFF8A; border:1px solid #FEE956; text-align:left">
<div style="width:auto; height:auto;" id="writeArticleHeadingHolder">
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td>
					Article Heading
				</td>
				<td>
					Article Author
				</td>
				<td>
					Publish Date
				</td>
				<td>
					Make Top Story
				</td>
			</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
			<tr>
				<td>
					<input type="text" style="width:300px; border:5px solid #ADD658" name="articleHeading" value="<?php echo $articleRow['Heading'];?>" />
				</td>
				<td>
					<input type="text" style="width:200px; border:5px solid #ADD658" name="articleAuthor" value="<?php echo $articleRow['Author'];?>" />
				</td>
				<td>
					<select name="pday" id="pday">
										<?php
											$nowDay = date("j");
											$actualDay = date("j", strtotime($articleRow['PublishDate']));
											
											for ($i = 1; $i <= 31; $i++)
											{
												$selected = '';
												if ($actualDay == $i)
													$selected = "selected='selected'";
													
												echo "<option value='$i' $selected>$i</option>";
											}
										?>
									</select>
									<select name="pmonth" id="pmonth">
									  <?php
									  		$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
											$nowMonth = date("F");
											$actualMonth = date("F", strtotime($articleRow['PublishDate']));
											for ($i = 0; $i < 12; $i++)
											{
												$selected = '';
												
												if ( $actualMonth == $months[$i] )
												{
													$selected = "selected='selected'";
												}
												$incre = $i + 1;
												echo "<option value='$incre' $selected>" . $months[$i] . "</option>";
											}
									?>
									</select>
									<select name="pyear" id="pyear">
										<?php
											$nowYear = date("Y");
											echo "<option value='$nowYear' selected='selected'>$nowYear</option>";
											$nowYear++;
											echo "<option value='$nowYear'>$nowYear</option>";
										?>
									</select>
				</td>
				<td style="padding-left:0px; text-align:center">
					<input type="checkbox" name="makeTopStory" <?php if ($articleRow['TopStory'] == 1) echo 'checked="checked"'; ?> />
				</td>
			</tr>
		</tbody>
	</table>
	<?php
		$groupResult = $dbConn->query(sprintf("SELECT GroupName, GroupGenID FROM subcategorygroup WHERE SCGenID='%s'", $scGenID));
		if ($groupResult && $groupResult->num_rows > 0)
		{
	?>
	<div id="groupSelectionHolder" style="height:30px; background-color:#F0F0F0; border-bottom:1px solid #CCCCCC; padding-left:10px">
		<div style="font-size:12px; font-weight:bold; float:left; margin-top:5px;">Group With :</div>
		<select name="groupSelection" id="groupSelection" style="width:300px; float:left; margin-top:5px;">
			<option value="0">None</option>
			<?php
				if (isset($articleGroup) && $articleGroup->num_rows > 0)
					$articleGroupRow = $articleGroup->fetch_array();
					
				for (; ($gRow = $groupResult->fetch_array()) != FALSE; )
				{
				?>
					<option value="<?php echo $gRow['GroupGenID'];?>" <?php if (isset($articleGroupRow) && $articleGroupRow['GroupGenID'] == $gRow['GroupGenID']) echo 'selected="selected"';?>><?php echo $gRow['GroupName'];?></option>
			<?php
				}
			?>
		</select>
	</div>
	<?php
		}
	?>
</div>

	<?php
		$articleEditor = new CKEditor();
		$articleEditor->config['width'] = 997;
		$articleEditor->config['height'] = 320;
		$articleEditor->config['resize_enabled'] = false;
		//$articleEditor->config['resize_maxWidth'] = $articleEditor->config['width'];
		//$articleEditor->config['resize_minHeight'] =$articleEditor->config['height'] = 320;
		$articleEditor->config['toolbar'] = array(array('Bold', 'Italic', 'Underline', 'Strike'), array('NumberedList', 'BulletedList'), array('TextColor'), array('SpellChecker', 'Scayt'), array('Link', 'Unlink'));
		$toolbar = $articleEditor->config['toolbar'];
		$articleEditor->editor("articleEditor", trim($articleRow['TextContent']), $toolbar);
	?>
	
	<!--use info -->
	<input type="hidden" name="userId" value="<?php echo $userId;?>" />
	<input type="hidden" name="categoryId" value="<?php echo $selectedCategory?>" />
	<input type="hidden" name="subcategoryId" value="<?php echo $scGenID;?>" />
	<input type="hidden" name="articleActionButton" id="actionButton" value="" />
	<input type="hidden" name="articleGenId" id="articleGenId" value="<?php echo $_GET['article']; ?>" />
	<input type="hidden" name="isPublished" id="isPublished" value="<?php if ($articleRow['PublishDate'] != "0000-00-00") echo "1"; else echo "0"; ?>" />
	<input type="hidden" name="oldArticleGroup" value="<?php if (isset($articleGroupRow)) echo $articleGroupRow['GroupGenID']; else echo 0; ?>" />
	<div id="popUp" align="center">
		<div id="popUpHeadingHolder"><div id="popUpHeading"></div></div>

		<div id="photoFileObjectHolder" class="fileObjectHolder"></div>
		<div id="videoFileObjectHolder" class="fileObjectHolder"></div>
		<div id="flashFileObjectHolder" class="fileObjectHolder"></div>
		
		<div class="buttonOutline" style="margin-left:10px; margin-right:10px">
			<input type="button" class="clickButton clickable"  value="Cancel" id="closePopUpButton" />
		</div>
		<div class="buttonOutline">
			<input type="button" class="clickButton clickable"  value="Ok" id="attachFileButton" />
		</div>
	</div>
</form>
</div>

<div id="writeArticleButtonCtrlHolder">
	<div class="wAButtonHolder">
		<div class="wABtnTopCurve">
		</div>
		<div class="wAButtonActiveArea clickable" id="uploadButton">
			<div class="wABtnIcon" style="width:37px; height:37px"><img src="../images/uploadIcon.png" alt="Upload articles will not be viewable by visitors until published." /></div>
			<div class="wABtnLabel">Commit Edits</div>
		</div>
		<div class="wABtnBottomCurve">
		</div>
	</div>
	
	<?php
		//hide this button if the article has been published.
		if ($articleRow['PublishDate'] == "0000-00-00")
		{
	?>
	<div class="wAButtonHolder" style="margin-left:30px;">
		<div class="wABtnTopCurve">
		</div>
		<div class="wAButtonActiveArea clickable" id="publishButton">
			<div class="wABtnIcon" style="width:37px; height:37px"><img src="../images/publishIcon.png" alt="Publish articles will be viewable by visitors." /></div>
			<div class="wABtnLabel">Publish</div>
		</div>
		<div class="wABtnBottomCurve">
		</div>
	</div>
	<?php
		}
	?>
	
	<div class="wAButtonHolder">
		<div class="wABtnTopCurve">
		</div>
		<div class="wAButtonActiveArea clickable" id="flashButton">
			<div class="wABtnIcon" style="width:37px; height:37px"><img src="../images/flashIcon.png" alt="Attach flash file to article." /></div>
			<div class="wABtnLabel">Add Flash</div>
		</div>
		<div class="wABtnBottomCurve">
		</div>
	</div>
	
	<div class="wAButtonHolder">
		<div class="wABtnTopCurve">
		</div>
		<div class="wAButtonActiveArea clickable" id="videoButton">
			<div class="wABtnIcon" style="width:37px; height:37px"><img src="../images/videoIcon.png" alt="Attach video to article." /></div>
			<div class="wABtnLabel">Add Video</div>
		</div>
		<div class="wABtnBottomCurve">
		</div>
	</div>
	
	<div class="wAButtonHolder">
		<div class="wABtnTopCurve">
		</div>
		<div class="wAButtonActiveArea clickable" id="photoButton">
			<div class="wABtnIcon" style="width:37px; height:37px"><img src="../images/photoIcon.png" alt="Attach photo to article." /></div>
			<div class="wABtnLabel">Add Photo</div>
		</div>
		<div class="wABtnBottomCurve">
		</div>
	</div>
</div>