<?php

require_once '../dbconfig.php';

include_once 'class.group.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
	$groupName = trim($_GET['groupName']);
	$groupDescription = trim($_GET['groupDescription']);
	
	if(isset($_GET['groupUsers'])) $groupUsers = json_decode($_GET["groupUsers"], true);
	else $groupUsers = null;
	
	if($user->login($uname,$umail,$upass))
	{
		$group = new GROUP($DB_con);
		
		// $newGroup = $group->createGroup($uid, $groupName, $groupDescription);
		$newGroup = $group->createGroupWithUsers($uid, $groupName, $groupDescription, $groupUsers);
		
		if ($newGroup["result"] == true)
		{
			$response["OK"] = "Your group has been created.";
			$response["UniqueGroupName"] = $newGroup["uniqueName"];
		} 
		else $response["ERROR"] = "An error has occurred when creating your group. Please try again.";
		
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