<?php

require_once '../dbconfig.php';

if (isset($_GET['friendProjects']))
{

	$uid = trim($_GET['uid']);
	$skip = trim($_GET['skip']);
	$max = trim($_GET['max']);

	$project = new PROJECT($DB_con);
	$projectList = $project->getProjectList($uid, $ptagname, $skip, $max);
	echo json_encode($projectList);
	
}
else
{
	// Error when authenticating user
	if (!$error) $error[] = "Wrong Details!";
	$response["ERROR"] = $error;
	echo json_encode($response);
}
?>