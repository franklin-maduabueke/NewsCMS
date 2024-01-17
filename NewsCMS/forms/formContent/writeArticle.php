<?php
	require_once("../modules/ckeditor/ckeditor.php");
?>
<form action="../processing/articlePost.php" method="post" name="writeArticleForm" enctype="multipart/form-data">
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
					<input type="text" style="width:300px; border:5px solid #ADD658" name="articleHeading" value="Enter Heading For Article" maxlength="100" />
				</td>
				<td>
					<input type="text" style="width:200px; border:5px solid #ADD658" name="articleAuthor" value="Enter name of author" maxlength="50" />
				</td>
				<td>
					<select name="pday" id="pday">
										<?php
											$nowDay = date("j");
											
											for ($i = 1; $i <= 31; $i++)
											{
												$selected = '';
												if ($nowDay == $i)
													$selected = "selected='selected'";
													
												echo "<option value='$i' $selected>$i</option>";
											}
										?>
									</select>
									<select name="pmonth" id="pmonth">
									  <?php
									  		$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
											$nowMonth = date("F");
											
											for ($i = 0; $i < 12; $i++)
											{
												$selected = '';
												
												if ( $nowMonth == $months[$i] )
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
					<input type="checkbox" name="makeTopStory" />
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
				for (; ($gRow = $groupResult->fetch_array()) != FALSE; )
				{
				?>
					<option value="<?php echo $gRow['GroupGenID'];?>"><?php echo $gRow['GroupName'];?></option>
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
		$articleEditor->editor("articleEditor", "Write Article Here", $toolbar);
	?>
	<!--use info -->
	<input type="hidden" name="userId" value="<?php echo $userId;?>" />
	<input type="hidden" name="categoryId" value="<?php echo $selectedCategory?>" />
	<input type="hidden" name="subcategoryId" value="<?php echo $scGenID;?>" />
	<input type="hidden" name="articleActionButton" id="actionButton" value="" />
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
</div>
</form>


<div id="writeArticleButtonCtrlHolder">
	<div class="wAButtonHolder">
		<div class="wABtnTopCurve">
		</div>
		<div class="wAButtonActiveArea clickable" id="uploadButton">
			<div class="wABtnIcon" style="width:37px; height:37px"><img src="../images/uploadIcon.png" alt="Upload articles will not be viewable by visitors until published." /></div>
			<div class="wABtnLabel">Upload</div>
		</div>
		<div class="wABtnBottomCurve">
		</div>
	</div>
	
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