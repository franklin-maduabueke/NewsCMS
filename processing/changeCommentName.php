<?php
	//delete cookie
	setcookie("member_nigeriannewsnetwork[id]", $_COOKIE['member_nigeriannewsnetwork']['id'], strtotime("29 May 1982"), "/");
	setcookie("member_nigeriannewsnetwork[nickname]", $_COOKIE['member_nigeriannewsnetwork']['nickname'], strtotime("29 May 1982"), "/");
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit();
?>