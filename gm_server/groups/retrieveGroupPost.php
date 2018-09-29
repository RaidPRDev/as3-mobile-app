<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once '../dbconfig.php';

include_once '../post/class.post.php';
include_once 'class.group.php';

if(isset($_POST['login']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$groupid = trim($_POST['groupid']);
	$skip = trim($_POST['skip']);
	$max = trim($_POST['max']);
	
	if($user->login($uname,$umail,$upass))
	{
		$uid = $_SESSION['user_session'];

		$post = new POST($DB_con);
		$group = new GROUP($DB_con);

		//Checking for the existence of group & its users
		$getGroupDetails = $group->getGroupDetails($groupid);
		$checkForGroupOwner = $group->checkForGroupOwner($groupid, $uid);
		$checkForGroupUser = $group->checkForGroupUser($groupid, $uid);
		
		if(isset($getGroupDetails["ERROR"]) || isset($checkForGroupOwner["ERROR"]) || isset($checkForGroupUser["ERROR"]))
		{
			$error[] = "An error has occurred! Please try again.";
		}
		else if(count($getGroupDetails["groupDetails"]) == 0)
		{
			$error[] = "Invalid Group";
		}
		else if(count($checkForGroupOwner["groupDetails"]) == 0 && count($checkForGroupUser["groupUserDetails"]) == 0)
		{
			$error[] = "Invalid Group User";
		}
		else
		{
			$retrieveGroupPost = $post->retrieveGroupPost($groupid, $skip, $max);

			if($retrieveGroupPost["result"] == true)
			{
				$response["OK"] = "Success";
				$response["groupPost"] = $retrieveGroupPost["groupPost"];
			}
			else
			{
				$response["ERROR"] = "An error has occured when posting your message. Please try again.";
			}
			
			echo json_encode($response);
		}
	}
	else
	{
		// Error when authenticating user
		$error[] = "Wrong Details!";
	}	
}

if(isset($error))
{
	$response["ERROR"] = $error;
	echo json_encode($response);
}

?>