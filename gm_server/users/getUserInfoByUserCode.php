<?php

require_once '../dbconfig.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['upass']);
	
	$usercode = trim($_GET['usercode']);
		
	if($user->login($uname,$umail,$upass))
	{
		$userInfo = $user->fetchUserByUserCode($usercode);
		
		if ($profileList["result"] == true)
		{
			$response["OK"] = "success";
			$response["userInfo"] = $userInfo;
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