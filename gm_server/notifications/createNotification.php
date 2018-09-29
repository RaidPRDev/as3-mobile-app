<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../dbconfig.php';
require_once 'class.notification.php';

if (isset($_GET['createnotification']))
{
	/*
		var urlvars:URLVariables = new URLVariables(); 
		urlvars.createnotification = true;
		urlvars.uname = userData.username;
		urlvars.upass = userData.password;
		urlvars.umail = userData.email;
		urlvars.subject_type = notification_from;
		urlvars.subject_id = notification_from.id;
		urlvars.object_type = notification_to;
		urlvars.object_id = notification_to.id;
		urlvars.notifcation_message = notification.message;
		urlvars.params = notification.params;
	*/
	
	$uname = trim($_GET['uname']);
	$upass = trim($_GET['upass']);
	$umail = trim($_GET['umail']);
		
	if($user->login($uname,$umail,$upass))
	{
		$userid = $_SESSION['user_session'];
		$notification = new NOTIFICATION($DB_con);
		$notificationInfo = array();
		$notificationInfo['subject_type'] = "user";
		$notificationInfo['subject_id'] = $userid;
		$notificationInfo['object_type'] = "user";
		$notificationInfo['object_id'] = "154";
		$notificationInfo['notification_message'] = $uname." has funded to your project.";
		$notificationInfo['params'] = '{"project_id":"1"}';

		$createNotification = $notification->createNotification($notificationInfo);
		echo json_encode($createNotification);
	}
	else
	{
		$error[] = "Wrong Details!";
	}	
}

if(isset($error))
{
	$response["ERROR"] = $error;
	echo json_encode($response);
}


?>