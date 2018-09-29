<?php
require_once '../dbconfig.php';
include_once '../class.wallet.php';

if (isset($_POST['subscribeProjects']))
{
	/*
		var urlvars:URLVariables = new URLVariables(); 
		urlvars.subscribeProjects = true;
		urlvars.uname = userData.username;
		urlvars.upass = userData.password;
		urlvars.umail = userData.email;
		urlvars.organisationId = projectInfo.userid;
	*/
	$uname = trim($_POST['uname']);
	$upass = trim($_POST['upass']);
	$umail = trim($_POST['umail']);
	$organisationId = trim($_POST['organisationId']);

	if($user->login($uname,$umail,$upass))
	{
		$userId = $_SESSION['user_session'];

		//Check user wallet
		$wallet = new WALLET($DB_con);
		$amountInUserWallet = $wallet->retrieveUserWallet($userId);

		if($amountInUserWallet>0)
		{
			//subscribe to the organisation
			$project = new PROJECT($DB_con);
			$subscribeProjects = $project->subscribeProjects($organisationId, $userId);

			if(!isset($subscribeProjects["ERROR"]))
			{
				$response["OK"] = "You have been successfully subscribed to the organisation.";
				$response["organisationId"] = $organisationId;
				echo json_encode($response);
			}
			else
			{
				$error[] = "An error has occured while subscription. Please try again.";		
			}
		}
		else
		{
			$error[] = "You cannot subscribe to the organisation due to Insufficient balance! Please add balance to your wallet.";
		}
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