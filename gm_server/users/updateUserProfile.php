<?php

require_once '../dbconfig.php';

include_once 'class.group.php';

if(isset($_POST['updateProfile']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$ugender = trim($_POST['gender']);	
	$ubirth = trim($_POST['birthdate']);
	$newUsername = trim($_POST['newUsername']);
	$newEmail = trim($_POST['newEmail']);
		
	if($user->login($uname,$umail,$upass))
	{
		$sendInfo["uid"] = $uid;
		$sendInfo["gender"] = $ugender;
		$sendInfo["birthDate"] = $ubirth;
		$sendInfo["newUsername"] = $newUsername;
		$sendInfo["newEmail"] = $newEmail;
		$updateUserProfile = $user->updateUserProfile($sendInfo);
		
		if ($updateUserProfile["result"] == true)
		{
			$response["OK"] = "Your profile has been updated.";
		} 
		else $response["ERROR"] = "An error has occurred when updating the user profile.";
		
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