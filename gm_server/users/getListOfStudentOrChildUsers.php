<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once '../dbconfig.php';

if(isset($_POST['login']))
{
	
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['umail']);
	$upass = trim($_POST['upass']);
		
	if($user->login($uname,$umail,$upass))
	{
		$result = $user->getListOfStudentOrChildUsers();
		echo json_encode($result);
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