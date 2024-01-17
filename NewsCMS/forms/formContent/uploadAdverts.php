<div class="noticeBoard" style="height:20px; margin-bottom:10px; width:auto"><?php if (isset($_GET['msg'])) echo $_GET['msg'];?></div>

<div class="advertTypeHolder">
<form id="imageAdsForm" action="../processing/uploadAds.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="advertType" id="imageAdvertType" value="image" />
	<div class="advertTypeHolderHeading">
		<div>Image Advert Upload Dialog</div>
	</div>
	<select name="imageAdvertType" class="paddedChoiceBox">
	  <option value="1">Square Image Advert (305 x 250)</option>
	  <option value="2">Banner Horizontal Image Advert (1000 x 105)</option>
	  <option value="3">Banner Vertical Image Advert (160 x 600)</option>
	</select>
	<br/>
	<input type="file" name="imageUpload" />
	<div class="clientNameHolder">
		<div style="font-size:12px;">Client PIN :</div>
		<input type="text" name="imageAdvertClientPIN" id="imageAdvertClientPIN" style="width:300px" maxlength="10"/>
	</div>
	
	<div class="expireDateCtrlHolder">
		<div style="font-size:12px;">Expire Date :</div>
									<select name="eday" id="eday">
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
									<select name="emonth" id="emonth">
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
									<select name="eyear" id="eyear">
										<?php
											$nowYear = date("Y");
											echo "<option value='$nowYear' selected='selected'>$nowYear</option>";
											$nowYear++;
											echo "<option value='$nowYear'>$nowYear</option>";
										?>
									</select>
	</div>
	
	<div style="margin-top:10px;">
		<div style="font-size:12px;">URL to direct to :</div>
		<input type="text" name="imageAdvertLink" id="imageAdvertLink" style="width:300px" />
	</div>
	
	<div style="margin-top:10px;">
		<label style="font-size:12px;">Check For Gold Advert :</label><input type="checkbox" name="goldAdvert" id="goldAdvert" />
	</div>
	
	<div class="wAButtonHolder" style="margin-right:90px; margin-top:20px; margin-bottom:20px;">
		<div class="wABtnTopCurve">
		</div>
		<div class="wAButtonActiveArea clickable" id="uploadImageAdvertButton">
			<div class="wABtnIcon" style="width:37px; height:37px"><img src="../images/uploadIcon.png" alt="Upload articles will not be viewable by visitors until published." /></div>
			<div class="wABtnLabel">Upload</div>
		</div>
		<div class="wABtnBottomCurve">
		</div>
	</div>
	</form>
</div>


<div class="advertTypeHolder">
<form id="flashAdsForm" action="../processing/uploadAds.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="advertType" id="flashAdvertType1" value="flash" />
	<div class="advertTypeHolderHeading">
		<div>Flash Advert Upload Dialog</div>
	</div>
	<select name="flashAdvertType" class="paddedChoiceBox">
	  <option value="1">Square Flash Advert (305 x 250)</option>
	  <option value="2">Banner Horizontal Flash Advert (1000 x 105)</option>
	  <option value="3">Banner Vertical Flash Advert (160 x 600)</option>
	</select>
	<br/>
	<input type="file" name="flashUpload" />
	<div class="clientNameHolder">
		<div style="font-size:12px;">Client PIN :</div>
		<input type="text" name="flashAdvertClientPIN" style="width:300px;"  id="flashAdvertClientPIN" maxlength="10" />
	</div>
	
	<div class="expireDateCtrlHolder">
		<div style="font-size:12px;">Expire Date :</div>
									<select name="eday" id="eday">
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
									<select name="emonth" id="emonth">
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
									<select name="eyear" id="eyear">
										<?php
											$nowYear = date("Y");
											echo "<option value='$nowYear' selected='selected'>$nowYear</option>";
											$nowYear++;
											echo "<option value='$nowYear'>$nowYear</option>";
										?>
									</select>
	</div>
	
	<div class="wAButtonHolder" style="margin-right:90px; margin-top:40px">
		<div class="wABtnTopCurve">
		</div>
		<div class="wAButtonActiveArea clickable" id="uploadFlashAdvertButton">
			<div class="wABtnIcon" style="width:37px; height:37px"><img src="../images/uploadIcon.png" alt="Upload articles will not be viewable by visitors until published." /></div>
			<div class="wABtnLabel">Upload</div>
		</div>
		<div class="wABtnBottomCurve">
		</div>
	</div>
	</form>
</div>
