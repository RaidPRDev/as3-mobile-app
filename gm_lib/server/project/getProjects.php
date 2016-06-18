<?php

require_once '../dbconfig.php';

if (isset($_GET['projectlist']))
{
	/*
		var urlvars:URLVariables = new URLVariables(); 
		urlvars.projectlist = true;
		urlvars.uid = userData.uid;
		urlvars.uname = userData.username;
		urlvars.upass = userData.password;
		urlvars.umail = userData.email;
		urlvars.atype = userData.accountType;
		urlvars.skip = skip; // to start from
		urlvars.max = max; // max items
	*/

	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$upass = trim($_GET['upass']);
	$umail = trim($_GET['umail']);
	$atype = trim($_GET['atype']);
	$skip = trim($_GET['skip']);
	$max = trim($_GET['max']);
	
	if($user->login($uname,$umail,$upass))
	{
		$userid = $_SESSION['user_session'];
		
		$project = new PROJECT($DB_con);
		
		$projectList = $project->getProjectList($userid, $skip, $max);
		
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