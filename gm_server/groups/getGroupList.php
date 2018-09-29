<?php

require_once '../dbconfig.php';

include_once 'class.group.php';
include_once '../mailer/class.mailer.php';

if(isset($_POST['login']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$skip = trim($_POST['skip']);
	$max = trim($_POST['max']);
		
	if($user->login($uname,$umail,$upass))
	{
		$group = new GROUP($DB_con);
		
		$userGroup = $group->getGroupList($uid, $skip, $max);
		$joinedGroup = $group->getJoinedGroupList($uid, $skip, $max);
		$allGroup = $group->getAllGroupList($uid, $skip, $max);
		
		if ($userGroup["result"] == true && $joinedGroup["result"] == true)
		{
			$response["OK"] = "success";
			$response["usergroups"] = $userGroup["groups"];
			$response["joinedgroups"] = $joinedGroup["groups"];
			$response["allGroups"] = $allGroup["groups"];
		} 
		else $response["ERROR"] = "An error has occurred when accessing the server. Please try again.";
		
		echo json_encode($response);
	}
	else
	{
		// Error when authenticating user
		if (!$error) $error[] = "Wrong Details!";
	}	
}
if(isset($error))
{
	$response["ERROR"] = $error;
	echo json_encode($response);
}

?>