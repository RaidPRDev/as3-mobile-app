<?php

require_once '../dbconfig.php';

// $root = $_SERVER['DOCUMENT_ROOT'] . "/clients/goaliemind/media/images/";
$root = "/var/www/html/glib/media/images/";

if ( isset ( $GLOBALS["HTTP_RAW_POST_DATA"] ) && isset ( $_GET['i'] ) ) 
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['upass']);
	$year = trim($_GET['year']);
	$month = trim($_GET['month']);	
	$imgFilename = $_GET['i'];
	
	if($user->login($uname,$umail,$upass))
	{
		$im = $GLOBALS["HTTP_RAW_POST_DATA"];	// get the binary stream
 
		$imageFolderName = $month . "_" . $year;
		
		// Check if folder exists
		if (!is_dir($root . $imageFolderName))  // is_dir - tells whether the filename is a directory
		{
			//echo "The Directory {$imageFolderName} does not exist!<br/>";
			mkdir($root . $imageFolderName);
		}
		else
		{
			//echo "The Directory {$imageFolderName} exist!<br/>";
		}
		
		// save image
		$saveLocation = $root . $imageFolderName . "/" . $imgFilename;
		$fp = fopen($saveLocation, 'wb');
		fwrite($fp, $im);
		fclose($fp);
	 
		// save thumbnail????
	}
	else
	{
		$error[] = "Wrong Details!";
	}	
}

if(isset($error))
{
	$response["ERROR"] = $error;
	echo json_encode($response);
}

?>