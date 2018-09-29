<?php

require_once '../dbconfig.php';

include_once 'class.event.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
	$eventid = trim($_GET['eventid']);
		
	if($user->login($uname,$umail,$upass))
	{
		$event = new EVENT($DB_con);
		
		$removeEventInfo['eventId'] = $eventid;
		
		$removeEvent = $event->removeEvent($removeEventInfo);
		
		if ($removeEvent["result"] == true)
		{
			// $response["OK"] = "Your group has been removed.";
			
			// TODO: Once we remove a Group, we will need to remove all Users associated to this group by ID
			
			$removeGroupsInEvent = $event->removeGroupsInEvent($eventid);
			
			if ($removeGroupsInEvent["result"] == true)
			{
				$response["OK"] = "Your event has been removed.";
			}
			else $response["ERROR"] = "An error has occurred when removing the group. Please try again.";
		} 
		else $response["ERROR"] = "An error has occurred when removing the group. Please try again.";
		
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