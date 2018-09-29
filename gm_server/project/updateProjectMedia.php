<?php

require_once '../dbconfig.php';

if (isset($_GET['updateproject']))
{
	/*
		var urlvars:URLVariables = new URLVariables(); 
		urlvars.newproject = true;
		urlvars.uname = userData.username;
		urlvars.upass = userData.password;
		urlvars.umail = userData.email;
		urlvars.atype = userData.accountType;
		urlvars.parentid = userData.parentUserid;
		urlvars.description = projectInfo.description;
		urlvars.tags = projectInfo.tags;
	*/
	$uname = trim($_GET['uname']);
	$upass = trim($_GET['upass']);
	$umail = trim($_GET['umail']);
	$atype = trim($_GET['atype']);
	$parentid = trim($_GET['parentid']);
	$description = trim($_GET['description']);
	$tags = trim($_GET['tags']);
	$timestamp = trim($_GET['time']);
		
	if($user->login($uname,$umail,$upass))
	{
		// create new project item
		// createProject($userid, $description, $tags, $acctype, $timestamp)
		$user->createProject($_SESSION['user_session'], $description, $tags, $atype, $timestamp);
		
		$userid = $_SESSION['user_session'];
		$newprojectid = $_SESSION['newprojectid'];
	
		$response["OK"] = "User authenticated!";
		$response["userid"] = $userid;
		$response["projectid"] = $newprojectid;
		echo json_encode($response);
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