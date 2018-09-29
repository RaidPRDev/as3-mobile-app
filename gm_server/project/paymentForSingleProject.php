<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once '../class.wallet.php';
include_once '../notifications/class.notification.php';
include_once '../stripe/goaliemind/Transaction.php';

if(isset($_POST['payForProject']))
{
	/*
		var urlvars:URLVariables = new URLVariables(); 
		urlvars.payForProject = true;
		urlvars.uname = userData.username;
		urlvars.upass = userData.password;
		urlvars.umail = userData.email;
		urlvars.projectid = projectData.projectid;
	*/
	$uname = trim($_POST['uname']);
	$upass = trim($_POST['upass']);
	$umail = trim($_POST['umail']);
	$projectid = trim($_POST['projectid']);
	
	if($user->login($uname,$umail,$upass))
	{
		$uid = $_SESSION['user_session'];
		$wallet = new WALLET($DB_con);

		//check wallet
		$amount = 1.2; //For now we set $amount=1.2
		$checkUserWallet = $wallet->checkUserWallet($uid, $amount);

		if(isset($checkUserWallet["ERROR"]))
		{
			$error[] = "An error has occurred! Please try again.";
		}
		else
		{
			if($checkUserWallet["result"] == true)
			{
				//Deduct Amount
				$deductFromUserWallet = $wallet->deductFromUserWallet($uid, $amount);

				if(isset($deductFromUserWallet["ERROR"]))
				{
					$error[] = "An error has occurred! Please try again.";
				}
				else
				{
					if($deductFromUserWallet["result"] == true)
					{
						//Begin Save Wallet Transfer
						$deduction_type = 2;
						$wallet->saveWalletDeduction($amount, $uid, $projectid, $deduction_type);
						//End Save Wallet Transfer

						//Add user as project users
						$project = new PROJECT($DB_con);
						$userIdArr = array();
						$userIdArr[] = $uid;
						$addUsersToProject = $project->addUsersToProject($projectid, $userIdArr);

						//Notification to user
						$notification = new NOTIFICATION($DB_con);
						$notificationInfo = array();
						$notificationInfo['subject_type'] = "charge_to_view_project";
						$notificationInfo['subject_id'] = $projectid;
						$notificationInfo['object_type'] = "user";
						$notificationInfo['object_id'] = $uid;
						$notificationInfo['notification_message'] = "$".$amount." have been deducted from your wallet to view the project";
						$notificationInfo['params'] = '{"projectid":"'.$projectid.'"}';

						$notification->createNotification($notificationInfo);

						//Logging the transaction into DB
						$transaction = new transaction($DB_con);
						$transactionInfo = array();
						$transactionInfo["userid"] = $uid;
						$transactionInfo["type"] = "user_wallet_deduct";
						$transactionInfo["desc"] = "$".$amount." have been deducted from users wallet";
						$transactionInfo["amount"] = $amount;
						$transactionInfo["projectid"] = $projectid;

						$transaction->createTransactionLog($transactionInfo);

						$response["OK"] = "Payment for the project has been successfully done.";
						$response["projectid"] = $projectid;

						echo json_encode($response);
					}
					else
					{
						$error[] = "An error has occurred! Please try again.";		
					}	
				}			
			}
			else
			{
				$error[] = "Insufficient Amount! Add money to your wallet.";
			} 
		}
	}
	else
	{
		// Error when authenticating user
		$error[] = "Wrong Details!";
	}	
}

if(isset($error))
{
	$response["ERROR"] = $error;
	echo json_encode($response);
}

?>