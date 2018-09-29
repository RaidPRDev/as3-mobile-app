<?php

require_once '../dbconfig.php';

include_once 'class.profiles.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['pw']);
		
	if($user->login($uname,$umail,$upass))
	{
		$profiles = new PROFILES($DB_con);
		
		$profileList = $profiles->getProfileList($uid);
		
		if ($profileList["result"] == true)
		{
			$response["OK"] = "success";
			$response["profiles"] = $profileList["profiles"];
		} 
		else 
		{
			// $response["ERROR"] = "An error has occurred when accessing the server. Please try again.";
			$response["ERROR"] = $profileList["ERROR"];
		}
		
		echo json_encode($response);
	}
	else
	{
		// Error when authenticating user
		if (!$error) $error[] = "Wrong Details!";
		$response["ERROR"] = $error;
		echo json_encode($response);
	}	
}

?>