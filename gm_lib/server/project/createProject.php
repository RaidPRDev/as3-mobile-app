<?php

require_once '../dbconfig.php';

$ext = ".jpg";
// $ext = ".png";

if (isset($_GET['newproject']))
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
		urlvars.month = now.month;
		urlvars.year = now.fullYear;	
	*/
	$uname = trim($_GET['uname']);
	$upass = trim($_GET['upass']);
	$umail = trim($_GET['umail']);
	$atype = trim($_GET['atype']);
	$parentid = trim($_GET['parentid']);
	$description = trim($_GET['description']);
	$tags = trim($_GET['tags']);
	$timestamp = trim($_GET['time']);
	$year = trim($_GET['year']);
	$month = trim($_GET['month']);		
	
	if($user->login($uname,$umail,$upass))
	{
		$userid = $_SESSION['user_session'];
		
		$imageName = MD5($userid + "_" + $uname + "_" + $timestamp);
		$thumbName = $imageName . "_thumb" . $ext;
		$imageName .= $ext;
		
		// create new project item
		// createProject($userid, $description, $tags, $acctype, $timestamp)
		
		$project = new PROJECT($DB_con);
		
		$project->createProject($_SESSION['user_session'], $description, $tags, $atype, $timestamp, $month, $year, $imageName, $thumbName);
		
		$newprojectid = $_SESSION['newprojectid'];
	
		// public function createProjectMedia($userid, $projectid, $imageName, $thumbName, $videoName, $month, $year, $timestamp)
		$project->createProjectMedia($_SESSION['user_session'], $newprojectid, $imageName, $thumbName, '', $month, $year, $timestamp);
		
		$response["OK"] = "Project Created!";
		$response["userid"] = $userid;
		$response["projectid"] = $newprojectid;
		$response["month"] = $month;
		$response["year"] = $year;
		$response["imageName"] = $imageName;
		$response["thumbName"] = $thumbName;
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