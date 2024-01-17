<?php
	require_once("../modules/ckeditor/ckeditor.php");
	
	$aboutUsTxtContent = "Write about us content here!";
	$aboutUsTxtPath = NULL;
	//get the about us text location
	$ini = parse_ini_file("../config/app.ini", TRUE);
	
	if ($ini && array_key_exists("SITE_MISC", $ini) && array_key_exists("AboutUsFile", $ini['SITE_MISC']))
	{
		$aboutUsTxtPath = $ini['SITE_MISC']['AboutUsFile'];
		
		if (file_exists($ini['SITE_MISC']['AboutUsFile']))
		{
			$aboutUsTxtContent = file_get_contents($ini['SITE_MISC']['AboutUsFile']);
		}
		else
		{
			//create the file in that path.
			$fHnd = fopen($ini['SITE_MISC']['AboutUsFile'], "x");
			
			if ($fHnd)
			{
				fwrite($fHnd, $aboutUsTxtContent);
				fclose($fHnd);
			}
		}
	}
?>
<form action="../processing/writeAboutUs.php" method="post" name="writeAboutusForm" enctype="multipart/form-data">
<div style="width:997px; height:auto; min-height:400px;background-color:#FFFF8A; border:1px solid #FEE956; text-align:left">
	<input type="hidden" value="<?php echo $aboutUsTxtPath; ?>" name="pathToFile" />
	<?php
		$articleEditor = new CKEditor();
		$articleEditor->config['width'] = 997;
		$articleEditor->config['height'] = 320;
		$articleEditor->config['resize_enabled'] = false;
		//$articleEditor->config['resize_maxWidth'] = $articleEditor->config['width'];
		//$articleEditor->config['resize_minHeight'] =$articleEditor->config['height'] = 320;
		$articleEditor->config['toolbar'] = array(array('Bold', 'Italic', 'Underline', 'Strike'), array('NumberedList', 'BulletedList'), array('TextColor'), array('SpellChecker', 'Scayt'), array('Link', 'Unlink'));
		$toolbar = $articleEditor->config['toolbar'];
		$articleEditor->editor("articleEditor", $aboutUsTxtContent, $toolbar);
	?>
	<!--use info -->
</div>
</form>

<div id="writeArticleButtonCtrlHolder">
	<div class="wAButtonHolder">
		<div class="wABtnTopCurve">
		</div>
		<div class="wAButtonActiveArea clickable" id="SaveButton">
			<div class="wABtnIcon" style="width:37px; height:37px"><img src="../images/uploadIcon.png" alt="Upload articles will not be viewable by visitors until published." /></div>
			<div class="wABtnLabel">Save</div>
		</div>
		<div class="wABtnBottomCurve">
		</div>
	</div>
	
</div>