<?php

require_once '../dbconfig.php';

include_once '../groups/class.group.php';
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
		$group = new GROUP($DB_con);
		
		// Get Groups added to Event
		$eventGroups = $event->getEventGroupsList($eventid, 0, 500);
		if ($eventGroups["result"] == true)
		{
			$response["eventGroups"] = $eventGroups["eventGroups"];
			
			// Get Group List based on User
			$groupList = $group->getGroupExcludedIdList($uid, $eventGroups["eventGroups"], 0, 500);
			
			if ($groupList["result"] == true)
			{
				$response["OK"] = "success";
				$response["groups"] = $groupList["groups"];
			}
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