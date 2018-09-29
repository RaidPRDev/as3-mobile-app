<?php

require_once '../dbconfig.php';

include_once 'class.group.php';

if(isset($_POST['updatePassword']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$newPassword = trim($_POST['newPassword']);
		
	if($user->login($uname,$umail,$upass))
	{
		$sendInfo["uid"] = $uid;
		$sendInfo["password"] = $newPassword;
		$changePassword = $user->updateUserPassword($sendInfo);
		
		if ($changePassword["result"] == true)
		{
			$response["OK"] = "Your password has been updated.";
		} 
		else $response["ERROR"] = "An error has occurred when updating the password.";
		
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