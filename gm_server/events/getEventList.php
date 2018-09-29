<?php

require_once '../dbconfig.php';

include_once 'class.event.php';
include_once '../mailer/class.mailer.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
		
	if($user->login($uname,$umail,$upass))
	{
		$event = new EVENT($DB_con);
		
		$newEvent = $event->getEventList($uid, 0, 500);
					
		if ($newEvent["result"] == true)
		{
			$response["OK"] = "success";
			$response["events"] = $newEvent["events"];
		} 
		else $response["ERROR"] = "An error has occurred when accessing the server. Please try again.";
		
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