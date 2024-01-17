<?php
	$msg = isset($_GET['msg']) ? $_GET['msg'] : NULL;
	define("PAGE_NAME", "index");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document<?php ?></title>
</head>
<link rel="stylesheet" type="text/css" href="../stylesheet/global.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/task_bar.css">

<script type="text/javascript" src="../scripts/jQuery.js"></script>
<script type="text/javascript" src="../scripts/headerSearchBoxScript.js"></script>

<body>
<div id="pageContainer" align="center">
	<?php include("../includes/header.php");?>
	<?php include("../includes/headerBasePanel.php");?>
	<div id="pageContentArea" style="background-color:#ADD658; width:1230px; height:550px">
		<div id="textHolder" style="float:left; margin-left:12px; margin-top:40px; width:683px; height:auto; text-align:left">
			<div id="welcomeText" style="font-size:25px; color:#FFFFFF; border-bottom:1px solid #FFFFFF">
				Welcome
			</div>
		  <div id="introText" style="font-size:15px; color:#FFFFFF; margin-top:3px">
				Thank you for obtaining the Your Site's Content Management System (CMS) from Havilah Creations.

<p/>
The CMS provides you with a simple to use web-based application for editing the contents that are viewed by your sites visitors without require the technical know-how of a web-page author.<p/>Login to enjoy the features of Your Sites Content Management system from Havilah Creations.
<p/>
Click <a href="#" style="color:#DCEDBA">Help</a> to get help.
</div>
		</div>
		<div id="loginFormHolder" style="float:right; margin-right:65px; margin-top:40px;width:280px; height:auto; text-align:left">
			<div id="authenticateText" style="font-size:25px; color:#FFFFFF; border-bottom:1px solid #FFFFFF; margin-bottom:25px">
				Please authenticate.
			</div>
			<form method="post" action="../processing/setup_create_user.php">
			<div id="formElements">
				<div class="textElementHolder">
					<div class="entryLabel">
						Username
					</div>
					<input type="text" class="textbox" id="username" name="username" maxlength="12" />
				</div>
				<div class="textElementHolder">
					<div class="entryLabel">
						Password
					</div>
					<input type="text" class="textbox" id="password" name="password" maxlength="20" />
				</div>
				
				<div class="textElementHolder">
					<div class="entryLabel">
						Surname
					</div>
					<input type="text" class="textbox" id="surname" name="surname" maxlength="20" />
				</div>
				<div class="textElementHolder">
					<div class="entryLabel">
						Firstname
					</div>
					<input type="text" class="textbox" id="firstname" name="firstname" maxlength="20" />
				</div>
				<div class="textElementHolder">
					<div class="entryLabel">
						Othername
					</div>
					<input type="text" class="textbox" id="othername" name="othername" maxlength="20" />
				</div>
				<div class="buttonOutline">
					<input type="submit" class="clickButton clickable"  value="Register" />
				</div>
				<label style="color:#FFFFFF; font-size:12px; float:right"><?php echo $_GET['msg'];?></label>
			</div>
			</form>
			
		</div>
	</div>
	<?php include("../includes/footer.php");?>
</div>
</body>
</html>
