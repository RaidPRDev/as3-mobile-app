<?php

require_once 'dbconfig.php';

if(isset($_GET['login']))
{
	$uname = trim($_GET['form_uname']);
	$umail = trim($_GET['form_umail']);
	$upass = trim($_GET['form_upass']);
		
	if($user->login($uname,$umail,$upass))
	{
		$response["OK"] = "User authenticated!";
		$response["user_session"] = $_SESSION['user_session'];
		$response["username"] = $_SESSION['username'];
		$response["useremail"] = $_SESSION['useremail'];
		$response["accountType"] = $_SESSION['accountType'];
		$response["parentid"] = $_SESSION['parentid'];
		$response["gender"] = $_SESSION['gender'];
		$response["birthDate"] = $_SESSION['birthDate'];
		$response["isAuthorized"] = $_SESSION['isAuthorized'];
		$response["authorizeKey"] = $_SESSION['authorizeKey'];
		$response["registerDate"] = $_SESSION['registerDate'];
		$response["signedInDate"] = $_SESSION['signedInDate'];
		$response["loginToken"] = $_SESSION['loginToken'];

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