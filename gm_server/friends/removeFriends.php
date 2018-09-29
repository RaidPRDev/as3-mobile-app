<?php

require_once '../dbconfig.php';

include_once 'class.friends.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
	$friendUserId = trim($_GET['friendUserId']);
		
	if($user->login($uname,$umail,$upass))
	{
		$friends = new FRIENDS($DB_con);
		
		$removeFriend = $friends->removeFriend($uid, $friendUserId);
		
		if ($removeFriend["result"] == true)
		{
			$response["data"] = $removeFriend;
 			$response["OK"] = "Friend has been removed.";
		} 
		else $response["ERROR"] = "An error has occurred when removing friend. Please try again.";
		
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