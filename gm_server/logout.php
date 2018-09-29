<?php

require_once 'dbconfig.php';

if(isset($_GET['logout']) && $_GET['logout']=="true")
{
	$userid = trim($_GET['userid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['upass']);

	if($user->login($uname,$umail,$upass))
	{
		if ($user->logout())
		{
			$response["OK"] = "User Logged Out!";
			echo json_encode($response);
		}
		else $error[] = "Logout Error";
	}
	else $error[] = "Logout Error";
	
}

if(isset($error))
{
	$response["ERROR"] = $error;
	echo json_encode($response);
}


?>