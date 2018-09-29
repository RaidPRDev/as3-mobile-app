<?php

require_once '../dbconfig.php';

include_once 'class.group.php';

if(isset($_GET['login']))
{
	//$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
	$groupId = trim($_GET['groupId']);
	$skip = trim($_GET['skip']);
	$max = trim($_GET['max']);
		
	if($user->login($uname,$umail,$upass))
	{
		$group = new GROUP($DB_con);
		
		$newGroup = $group->getGroupUserList($groupId, $skip, $max);
		$groupDetails = $group->getGroupDetails($groupId);
		
		if ($newGroup["result"] == true && $groupDetails["result"] == true)
		{
			$response["OK"] = "success";
			$response["groupDetails"] = $groupDetails["groupDetails"];
			$response["groupUsers"] = $newGroup["groupUsers"];
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