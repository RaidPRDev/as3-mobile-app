<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../dbconfig.php';

include_once '../friends/class.friends.php';

if (isset($_GET['projectlist']))
{	
	$uid = trim($_GET['userid']);
	$uname = trim($_GET['uname']);
	$upass = trim($_GET['upass']);
	$umail = trim($_GET['umail']);
	$skip = trim($_GET['skip']);
	$max = trim($_GET['max']);

	$isParentAuthorized = null;
	if(isset($_GET['isParentAuthorized']) && !empty($_GET['isParentAuthorized']))
	{
		$isParentAuthorized = true;
	}
	else $isParentAuthorized = null;
	
	if(isset($_GET['ptagname']) && !empty($_GET['ptagname']))
	{
		$ptagname = trim($_GET['ptagname']); 
	}
	else
	{
		$ptagname = null; 
	}
	
	
	if($user->login($uname,$umail,$upass) || $isParentAuthorized)
	{
		$userid = $uid; /// $_SESSION['user_session'];
		
		$project = new PROJECT($DB_con);
		$friends = new FRIENDS($DB_con);

		if (isset($_GET['friends']) && $_GET['friends'] == "true")
		{
			$projectList = $project->getProjectListOfFriends($userid, $ptagname, $skip, $max);
			
			// After we get project list, we need to scan it and make sure the friend is authorized
			// If friend is not authorized then do not show project!
			
			for ($i = count($projectList)-1; $i >= 0; $i--)
			{
				$projectItem =  $projectList[$i];
				
				$projectList[$i]["stars"] = number_format($projectList[$i]["stars"],0);	
				
				$validationInfo = $friends->getFriendValidationInfo($userid, $projectItem['userid'])[0];
				if ($validationInfo['isAuthorized'] == "0")
				{
					array_splice($projectList, $i, 1);
				}
			}
		}
		else
		{
			$projectList = $project->getProjectList($userid, $ptagname, $skip, $max);
			for ($i = count($projectList)-1; $i >= 0; $i--)
			{
				$projectItem =  $projectList[$i];
			
				$projectList[$i]["stars"] = number_format($projectList[$i]["stars"],0);	
			
				// echo "STARS: " . $projectItem["stars"];
			}
		}
		echo json_encode($projectList);
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