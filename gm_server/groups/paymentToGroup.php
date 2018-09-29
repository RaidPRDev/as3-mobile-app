<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once '../class.wallet.php';
include_once '../notifications/class.notification.php';
include_once '../stripe/goaliemind/Transaction.php';
include_once 'class.group.php';

if(isset($_POST['payForGroup']))
{
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$groupid = trim($_POST['groupid']);
	$groupOwnerid = trim($_POST['groupOwnerid']); 
	
	if($user->login($uname,$umail,$upass))
	{
		$uid = $_SESSION['user_session'];
		$wallet = new WALLET($DB_con);
		$group = new GROUP($DB_con);

		//check wallet
		$amount = 25; //For now we set $amount=25
		$checkUserWallet = $wallet->checkUserWallet($uid, $amount);

		$getGroupDetails = $group->getGroupDetails($groupid);
		$checkForGroupOwner = $group->checkForGroupOwner($groupid, $groupOwnerid);

		if(isset($getGroupDetails["ERROR"]) || isset($checkForGroupOwner["ERROR"]) || isset($checkUserWallet["ERROR"]))
		{
			$error[] = "An error has occurred! Please try again.";
		}
		else if(count($getGroupDetails["groupDetails"]) == 0)
		{
			$error[] = "Invalid Group";
		}
		else if(count($checkForGroupOwner["groupDetails"]) == 0)
		{
			$error[] = "Invalid Group Owner";
		}
		else
		{
			if($checkUserWallet["result"] == true)
			{
				//Add amount to Group owner account
			$addAmountToGroupOwner = $wallet->updateUserWallet($groupOwnerid, $amount);

				//Deduct Amount
				$deductFromUserWallet = $wallet->deductFromUserWallet($uid, $amount);

				$logGroupFundInDB = $group->groupFunds($amount, $groupid, $groupOwnerid, $uid);

				if(isset($deductFromUserWallet["ERROR"]))
				{
					$error[] = "An error has occurred! Please try again.";
				}
				else
				{
					if($deductFromUserWallet["result"] == true)
					{
						//Begin Save Wallet Transfer
						$deduction_type = 3;
						$wallet->saveWalletDeduction($amount, $uid, $groupid, $deduction_type);
						//End Save Wallet Transfer

						//Add user as project users
						/*$project = new PROJECT($DB_con);
						$userIdArr = array();
						$userIdArr[] = $uid;
						$addUsersToProject = $project->addUsersToProject($projectid, $userIdArr);*/

						//Notification to user
						$notification = new NOTIFICATION($DB_con);
						$notificationInfo = array();
						$notificationInfo['subject_type'] = "payment_for_group";
						$notificationInfo['subject_id'] = $groupid;
						$notificationInfo['object_type'] = "user";
						$notificationInfo['object_id'] = $uid;
						$notificationInfo['notification_message'] = "$".$amount." have been deducted from your wallet for the group payment";
						$notificationInfo['params'] = '{"groupid":"'.$groupid.'"}';

						$notification->createNotification($notificationInfo);

						//Logging the transaction into DB
						$transaction = new transaction($DB_con);
						$transactionInfo = array();
						$transactionInfo["userid"] = $uid;
						$transactionInfo["type"] = "user_wallet_deduct";
						$transactionInfo["desc"] = "$".$amount." have been deducted from users wallet for group payment";
						$transactionInfo["amount"] = $amount;
						$transactionInfo["groupid"] = $groupid;

						$transaction->createTransactionLog($transactionInfo);

						$response["OK"] = "Payment for the group has been successfully done.";
						$response["groupid"] = $groupid;

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