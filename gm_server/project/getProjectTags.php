<?php

require_once '../dbconfig.php';

if (isset($_GET['projecttaglist']) && $_GET['projecttaglist'] == "true")
{
	$skip = trim($_GET['skip']);
	$max = trim($_GET['max']);

	$project = new PROJECT($DB_con);
	
	$projectTagList = $project->getProjectTagList(-1, $skip, $max);
	echo json_encode($projectTagList);
}
else
{
	$response["ERROR"] = "Error occured, please check your connection.";
	echo json_encode($response);
}
?>