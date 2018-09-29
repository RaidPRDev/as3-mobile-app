<?php

require_once '../dbconfig.php';

if (isset($_POST['projectdetails']))
{
	/*
		var urlvars:URLVariables = new URLVariables(); 
		urlvars.projectdetails = true;
		urlvars.uname = userData.username;
		urlvars.upass = userData.password;
		urlvars.umail = userData.email;
		urlvars.projectid = projectData.id;
	*/

	$uname = trim($_POST['uname']);
	$upass = trim($_POST['upass']);
	$umail = trim($_POST['umail']);
	$projectid = trim($_POST['projectid']);

	if($user->login($uname,$umail,$upass))
	{
		$project = new PROJECT($DB_con);
		$projectdetails = $project->getProjectDetails($projectid);
		
		echo json_encode($projectdetails);
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