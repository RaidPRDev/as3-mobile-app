<?php

require_once '../dbconfig.php';

if(isset($_GET['login']))
{
	$uname = trim($_GET['form_uname']);
	$umail = trim($_GET['form_umail']);
	$upass = trim($_GET['form_upass']);
		
	if($user->login($uname,$umail,$upass))
	{
		$response["OK"] = "User authenticated!";
		$response["PARENTID"] = $_SESSION['user_session'];
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