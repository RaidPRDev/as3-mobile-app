<?php

require_once '../dbconfig.php';

include_once 'class.group.php';

if(isset($_GET['login']))
{
	//$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);

	$groupUserId = trim($_GET['groupuserid']);
		
	if($user->login($uname,$umail,$upass))
	{
		$group = new GROUP($DB_con);
		
		$removeGroupInfo['groupUserId'] = $groupUserId;
		
		$newGroup = $group->removeGroupUser($removeGroupInfo);
		
		if ($newGroup["result"] == true)
		{
			$response["OK"] = "The user has been removed.";
		} 
		else $response["ERROR"] = "An error has occurred when removing the user. Please try again.";
		
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