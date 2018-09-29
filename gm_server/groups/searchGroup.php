<?php
require_once '../dbconfig.php';

include_once 'class.group.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
	$skip = trim($_GET['skip']);
	$max = trim($_GET['max']);

	if(isset($_GET['searchTerm']))
	{
		$searchTerm = trim($_GET['searchTerm']);
	}
	else
	{
		$searchTerm = null;
	}
	
	if($user->login($uname,$umail,$upass))
	{
		$group = new GROUP($DB_con);
		
		$searchGroup = $group->searchGroup($uid, $searchTerm, $skip, $max);
		
		if ($searchGroup["result"] == true)
		{
			$response = $searchGroup["groups"];
		} 
		else $response["ERROR"] = "An error has occurred when searching group. Please try again.";
		
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