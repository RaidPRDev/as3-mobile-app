<?php

require_once '../dbconfig.php';

include_once 'class.profiles.php';
include_once '../avatar/class.avatar.php';
include_once '../avatar/class.avatar.data.php';

if(isset($_GET['pws']))	// This means we will only get the Parent User Details
{
	$pws = trim($_GET['pws']);
	if ($pws != "true") 
	{
		// Error when authenticating user
		$error[] = "Authentication Error!";
		$response["ERROR"] = $error;
		echo json_encode($response);
		return;
	}

	// Get User Credentials
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['pw']);
	$parentUserid = trim($_GET['parentUserid']);
		
	if($user->login($uname,$umail,$upass))
	{
		if ($parentUserid > -1)
		{
			$parentUserInfo = $user->getUserInfo($parentUserid)["userInfo"][0];
		
			$avatar = new AVATAR($DB_con);
			
			$parentUserInfo["thumb"] = $avatar->fetchUserAvatarByUserId($parentUserid)[0]["thumb"];
			
			$response["OK"] = "success";
			$response["profiles"] = [];
			$response["profiles"][] = $parentUserInfo;
			
			// var_dump($parentUserInfo);
			
			echo json_encode($response);
			
			return;
		}
		else
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