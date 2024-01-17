<?php
	require_once($prefix . "modules/visitorMonitor.php");
	visitorMonitor($dbConn);
?>
<div id="header">
	<div id="headerContentHolder">
		<div id="logoHolder">
			<a href="<?php echo $prefix;?>index.php" style="text-decoration:none">
			<div id="logo">
				<div style="color:#FFFFFF; font-size:9px; padding-top:80px; margin-left:14px;"></div>
			</div>
			</a>
		</div>
		<div id="headerMid">
		
			<?php require_once($prefix . "includes/headerSearchBar.php"); ?>
			<div id="socialLinks">
				<ul>
					<li id="facebook"><div class="linkContainer"><img src="<?php echo $prefix;?>images/fbImgNone.png" /><a href="http://www.facebook.com" target="_blank">Facebook</a></div></li>
					<li id="twitter"><div class="linkContainer"><img src="<?php echo $prefix;?>images/twitImgNone.png" /><a href="http://www.twitter.com" target="_blank">Twitter</a></div></li>
					<li id="YouTube"><div class="linkContainer"><img src="<?php echo $prefix;?>images/youTubeImgNone.png" /><a href="http://www.youtube.com" target="_blank">YouTube</a></div></li>
				</ul>
			</div>
			
		</div>
		<div id="headerRight">
			<?php require_once($prefix . "includes/headerAdsHorizontal.php");?>
		</div>
	</div>
</div>