<?php

require_once '../dbconfig.php';

include_once 'class.friends.php';
include_once '../mailer/class.mailer.php';

include_once '../avatar/class.avatar.php';
include_once '../avatar/class.avatar.data.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
		
	if($user->login($uname,$umail,$upass))
	{
		$friends = new FRIENDS($DB_con);
		
		$newGroup = $friends->getFriendsList($uid, 0, 500);
		
		if ($newGroup["result"] == true)
		{
			$response["OK"] = "success";
			$response["friends"] = $newGroup["friends"];
		} 
		else 
		{
			// $response["ERROR"] = "An error has occurred when accessing the server. Please try again.";
			$response["ERROR"] = $newGroup["ERROR"];
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