<?php

require_once '../dbconfig.php';

include_once 'class.group.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
	$groupid = trim($_GET['groupid']);
		
	if($user->login($uname,$umail,$upass))
	{
		$group = new GROUP($DB_con);
		
		$timeAdded = round(microtime(true) * 1000);
		$validateid = MD5($timeAdded);
		
		$addGroupUser = $group->addUserToGroup($groupid, $uid, $validateid);
		
		if ($addGroupUser["result"] == true)
		{
			$response["OK"] = "You have been added to group.";
			$response["newgroupuserid"] = $addGroupUser["newgroupuserid"];
			$response["validateid"] = $validateid;
		} 
		else $response["ERROR"] = "An error has occurred when adding you to a group. Please try again.";
		
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