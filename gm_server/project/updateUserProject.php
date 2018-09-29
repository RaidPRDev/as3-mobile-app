<?php

require_once '../dbconfig.php';

if(isset($_POST['updateProject']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	
	// Project Details
	$projectid = trim($_POST['projectid']);
	$title = trim($_POST['title']);	
	$description = trim($_POST['description']);
	$tags = trim($_POST['tags']);
	
	if($user->login($uname,$umail,$upass))
	{
		$sendInfo["projectid"] = $projectid;
		$sendInfo["title"] = $title;
		$sendInfo["description"] = $description;
		$sendInfo["tags"] = $tags;
		
		$project = new PROJECT($DB_con);
		
		$updateUserProject = $project->updateUserProject($sendInfo);
		
		if ($updateUserProject["result"] == true)
		{
			$response["OK"] = "Your project has been updated.";
		} 
		else $response["ERROR"] = "An error has occurred when updating this project.";
		
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