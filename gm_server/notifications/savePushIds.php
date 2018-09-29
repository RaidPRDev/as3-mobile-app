<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once 'class.push.php';

if(isset($_GET['tokenPush']))
{
	$pushNotify = new PUSHNOTIFY($DB_con);
	
	$uid = trim($_GET['uid']);
	$oneSignalUserId = trim($_GET['oneSignalUserId']);
	$pushToken = trim($_GET['pushToken']);

	$updatePush = $pushNotify->updatePush($uid, $oneSignalUserId, $pushToken);
		
	if ($updatePush["result"] == true)
	{
		$response["OK"] = "Updated Push Notification Ids";
	} 
	else $response["ERROR"] = "An error has occurred when updating notifications.";
	
	echo json_encode($response);
	
}
else
{
	$response["ERROR"] = "Wrong notifications details";
	echo json_encode($response);
}	
?>