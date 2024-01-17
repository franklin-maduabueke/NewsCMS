<?php
	require_once("../includes/user_checker.php");
	
	if (!userSessionGood())
	{
		header("Location: logout.php");
		exit();
	}
	/*
	function  cleanUp($text)
	{
		$text = str_replace("<i>", "<span style='font-style:italic'>",$text);
		$text = str_replace("</i>", "</span>",$text);
		$text = str_replace("</div>", "",$text);
		$text = str_replace("`", "&lsquo;",$text);
		$text = str_replace("&nbsp;", " ",$text);
		$text = str_replace("\t;", " ",$text);
		$text = str_replace("</p>", "",$text);
		
		//now check for paragraph tags that have no text behind them.
		for ($i = 0; $i < strlen($text); $i++)
		{
			$fParaPos = strpos($text, "<p>");
			if (!($fParaPos === false))
			{
				$checkBehind = trim(substr($text, 0, $fParaPos + 4));
				if (strlen($checkBehind) == 3)
					$text = trim(substr($text, $fParaPos + 4));
			}
		}
		
		//remove initial div.
		for ($i = 0; $i < strlen($text); $i++)
		{
			$fParaPos = strpos($text, "<div>");
			if (!($fParaPos === false))
			{
				$checkBehind = trim(substr($text, 0, $fParaPos + strlen("<div>") + 1));

				if (strlen($checkBehind) == strlen("<div>"))
				{
					$text = trim(substr($text, $fParaPos + strlen("<div>") + 1));
				}
					
			}
		}
		
		$text = trim($text);
		$text = str_replace("<p>", "<p/>",$text);
		$text = str_replace("<div>", "<p/>",$text);

		return $text;
	}
	*/
	
	$pathToAboutUsTxtFile = $_POST['pathToFile'];
	
	$content = $_POST['articleEditor'];
	
	$msg = "Last update operation failed";
	
	if (file_exists($pathToAboutUsTxtFile))
	{
		//write into it the new content
		$fHnd = fopen($pathToAboutUsTxtFile,"w");
		if ($fHnd)
		{
			$error = fwrite($fHnd, $content);
			if (!(error === FALSE))
				$msg = "Page has been updated";
		}
	}
	
	//extract the task
	$needle = "&tsk="; //search for tsk representing task query argument
	$referer = $_SERVER['HTTP_REFERER'];
	
	$tskSPos = strpos($referer, $needle);
	$preTsk = substr($referer, $tskSPos + strlen($needle)); //get the actual task exluding &tsk=

	$nextArg = strpos($preTsk, "&"); //search for next argument 
	if (!($nextArg === FALSE))
		$preTsk = substr($preTsk, 0, $nextArg + 1); //next argument exists so remove that
		
	//we now how our tsk string
	header("Location: ../forms/room.php?user=".$_SESSION['authentication']."&role=" . $_SESSION['Role'] . "&tsk=" . $preTsk . "&msg=".urlencode($msg));
	exit();
?>