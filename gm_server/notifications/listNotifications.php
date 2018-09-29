<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once '../dbconfig.php';
require_once 'class.notification.php';

if (isset($_GET['listnotification']))
{
	/*
		var urlvars:URLVariables = new URLVariables(); 
		urlvars.listnotification = true;
		urlvars.uname = userData.username;
		urlvars.upass = userData.password;
		urlvars.umail = userData.email;
	*/
	
	$uname = trim($_GET['uname']);
	$upass = trim($_GET['upass']);
	$umail = trim($_GET['umail']);
	
	if($user->login($uname,$umail,$upass))
	{
		$userid = $user->fetchUserIdByUsername($uname)[0]["id"];
		$objectid = $userid;
		$notification = new NOTIFICATION($DB_con);
		$listNotification = $notification->listNotification($objectid);
		echo json_encode($listNotification);
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