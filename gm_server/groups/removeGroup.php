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
		
	if($user->login($uname,$umail,$upass))
	{
		$group = new GROUP($DB_con);
		
		$removeGroupInfo['groupId'] = $groupId;
		
		$removeGroup = $group->removeGroupWithUsers($removeGroupInfo);
		// $removeGroup = $group->removeGroup($removeGroupInfo);
		
		if ($removeGroup["result"] == true)
		{
			$response["OK"] = "Your group has been removed.";
			
			// TODO: Once we remove a Group, we will need to remove all Users associated to this group by ID
		} 
		else $response["ERROR"] = "An error has occurred when removing the group. Please try again.";
		
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