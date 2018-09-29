<?php

require_once '../dbconfig.php';

include_once 'class.event.php';

// date_default_timezone_set('Etc/UTC');

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
	$eventName = trim($_GET['eventName']);
	$eventDescription = trim($_GET['eventDescription']);
	$eventDate = trim($_GET['eventDate']);
	$eventTimeHour = trim($_GET['eventTimeHour']);
	$eventTimeMinute = trim($_GET['eventTimeMinute']);
	$eventTimeMeridien = trim($_GET['eventTimeMeridien']);
	$eventGroupIds = trim($_GET['eventGroupIds']);

	// Creata Array of Group IDs
	$groupIds = explode(",", $eventGroupIds);
		
	// AIR sends time in milliseconds, PHP uses seconds
	$convertedTime = $eventDate / 1000;		// Divide by 1000 to get seconds
	// echo "DATE: " . date('m/d/Y', $convertedTime);
		
	if($user->login($uname,$umail,$upass))
	{
		$event = new EVENT($DB_con);
		
		$eventInfo = array();
		$eventInfo["eventName"] = $eventName;
		$eventInfo["eventDescription"] = $eventDescription;
		$eventInfo["eventDate"] = $convertedTime;
		$eventInfo["eventTimeHour"] = $eventTimeHour;
		$eventInfo["eventTimeMinute"] = $eventTimeMinute;
		$eventInfo["eventTimeMeridien"] = $eventTimeMeridien;
		$eventInfo["eventGroupIds"] = $eventGroupIds;
		
		$newEvent = $event->createEvent($uid, $eventInfo);
		
		if ($newEvent["result"] == true)
		{
			// Get newly created Event Insert ID and add the groups
			$newEventid = $newEvent["neweventid"];
			
			// Check if we need to add Groups to Event
			$max = sizeof($groupIds);
			
			if ($max > 0) 
			{
				$insertGroupIds = $event->addGroupIDToEvent($uid, $newEventid, $groupIds);
				
				if ($insertGroupIds["result"] == true)
				{
					$response["OK"] = "Your event has been created.";
				}
			}
			else $response["OK"] = "Your event has been created.";
		} 
		else $response["ERROR"] = "An error has occurred when creating your event. Please try again.";
		
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