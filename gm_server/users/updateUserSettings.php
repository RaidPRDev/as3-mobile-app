<?php

require_once '../dbconfig.php';

include_once 'userSettings/class.user.settings.php';

if(isset($_GET['updateUserSettings']))
{
	
	$settingsInfo["userid"] = trim($_GET['uid']);
	$settingsInfo["enableFunding"] = trim($_GET['enableFunding']);
	$settingsInfo["maxFund"] = trim($_GET['maxFund']);
	$settingsInfo["enableNotify"] = trim($_GET['enableNotify']);
	$settingsInfo["enablePushNotify"] = 0;
	$settingsInfo["enableAddFriend"] = trim($_GET['enableAddFriend']);
	
	$userSettings = new USER_SETTINGS($DB_con);
	$updateUserSettings = $userSettings->updateUserSettings($settingsInfo);
	
	if ($updateUserSettings["result"] == true)
	{
		$response["OK"] = "User settings has been updated.";
	} 
	else $response["ERROR"] = "An error has occurred when updating the user settings.";
	
	echo json_encode($response);
}

?>