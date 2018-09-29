<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../dbconfig.php';

include_once '../friends/class.friends.php';

// $userSearch = $_GET['userSearch'];

// if($userSearch == true)
if(isset($_GET['userSearch']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['upass']);
	$searchTerm = $_GET['searchTerm'];
	$skip = trim($_GET['skip']);
	$max = trim($_GET['max']);
	
	/*$uid = "16";
	$uname = "fania";
	$umail = "fania@raidpr.com";
	$upass = "Fania4093";
	$searchTerm = "e";
	$skip = 0;
	$max = 5000;*/
	
	if($user->login($uname,$umail,$upass))
	{
		// Get Friends IDs
		$friends = new FRIENDS($DB_con);
		$fetchFriendsUserID = $friends->getFriendsUserIDList($uid)["friends"];
		
		$excludeUsersIdsTemp = "";
		foreach ($fetchFriendsUserID as $row)
		{
			$excludeUsersIdsTemp .= $row["id"] . ","; 
		}
		
		if (count($fetchFriendsUserID) > 0)
		{
			$len = strlen($excludeUsersIdsTemp);
			$excludeUsersIds = substr($excludeUsersIdsTemp, 0, $len - 1);
		}
		else $excludeUsersIds = null;
		
		$searchedUsers = $user->searchUsers($searchTerm, $excludeUsersIds, $skip, $max);
		
		if ($searchedUsers["result"] == true)
		{
			$response["OK"] = "success";
			$response["usersFound"] = $searchedUsers["usersFound"];
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