<?php

require_once '../dbconfig.php';

if (isset($_GET['searchuser']))
{
	$uname = trim($_GET['uname']);
	$upass = trim($_GET['upass']);
	$umail = trim($_GET['umail']);
	$skip = trim($_GET['skip']);
	$max = trim($_GET['max']);
	$searchTerm = trim($_GET['searchuser']);
	
	if($user->login($uname,$umail,$upass))
	{
		$excludeUserIds = null;
		$searchedUsers = $user->searchUsers($searchTerm, $excludeUserIds, $skip, $max);
		
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
		$error[] = "Wrong Details!";
	}		
}

if(isset($error))
{
	$response["ERROR"] = $error;
	echo json_encode($response);
}


?>