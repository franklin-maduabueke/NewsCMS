<?php
	function sendMail($from, $to, $message)
	{
		if (isset($from, $to) && filter_var_array(array("from"=>$from, "to"=>$to), array("from"=>FILTER_VALIDATE_EMAIL, "to"=>FILTER_VALIDATE_EMAIL)))
		{
			if (@mail($to, "Intersting www.nigeriannewsnetwork", wordwrap($message,70)))
				return TRUE;
			else
				return FALSE;
		}
		else
			return FALSE;
	}
	
	$message = sprintf("You have been sent this mail because some one invited you to read an article published on www.nigeriannewsnetwork. Click on %s to read this article.", $_SERVER['HTTP_REFERER']);
	
	if (sendMail($_POST['sender'], $_POST['receiver'], $message))
		echo "Mail sent to " . $_POST['receiver'];
	else
		echo "0";
	
	flush();
	exit();
?>