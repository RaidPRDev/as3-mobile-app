<?php

require_once '../dbconfig.php';

include_once 'class.friends.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
	$searchTerm = trim($_GET['searchTerm']);
	
	if (isset($_GET['excludeUserIds']))
	{
		$excludeUserIds = $_GET['excludeUserIds'];
	}
	else $excludeUserIds = null;
		
	if($user->login($uname,$umail,$upass))
	{
		$friends = new FRIENDS($DB_con);
		
		$searchedUsers = $user->searchUsers($uid, $searchTerm, $excludeUserIds, 0, 500);
		
		if ($searchedUsers["result"] == true)
		{
			$response["OK"] = "success";
			$response["usersFound"] = $searchedUsers["usersFound"];
		} 
		else $response["ERROR"] = "An error has occurred when accessing the server. Please try again.";
		
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