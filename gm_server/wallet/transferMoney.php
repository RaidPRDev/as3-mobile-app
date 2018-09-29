<?php
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
require_once '../dbconfig.php';
require_once '../class.wallet.php';
include_once '../stripe/goaliemind/Transaction.php';
require_once '../notifications/class.notification.php';
require_once '../avatar/class.avatar.php';

if(isset($_POST['login']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$amount = trim($_POST['amount']);
	$transfer_uid = trim($_POST['transfer_uid']);  // To whom tranfer the amount.
	
	if($user->login($uname,$umail,$upass))
	{
		$wallet = new WALLET($DB_con);

		//Check user wallet for availability of amount to transfer
		$checkUserWallet = $wallet->checkUserWallet($uid, $amount); 
		if($checkUserWallet)
		{
			//Add amount to $transfer_uid
			$updateTransferUserWallet = $wallet->updateUserWallet($transfer_uid, $amount);
			if($updateTransferUserWallet)
			{
				//Begin Save Wallet Transfer
				$deduction_type = 1;
				$wallet->saveWalletDeduction($amount, $uid, $transfer_uid, $deduction_type);
				//End Save Wallet Transfer

				$response["OK"] = "Amount transfer successfully.";

				$notification = new NOTIFICATION($DB_con);
				$userAvatar = new AVATAR($DB_con);
				
				// Uid Avatar
				$userAvatarInfo = $userAvatar->fetchUserAvatarByUserId($uid);
				$userAvatarThumb = $userAvatarInfo[0]['thumb'];
				// Add notification to $transfer_uid start
				$notificationInfo = array();
				$notificationInfo['subject_type'] = "user";
				$notificationInfo['subject_id'] = $uid;
				$notificationInfo['object_type'] = "user";
				$notificationInfo['object_id'] = $transfer_uid;
				$notificationInfo['notification_message'] = "$".$amount." has been added to your wallet by ".$uname;
				$notificationInfo['params'] = '{"user_id":"'.$uid.'", "user_avatar_thumb":"'.$userAvatarThumb.'"}';

				$notification->createNotification($notificationInfo);
				// Add notification to $transfer_uid end

				//Deduct amount from $uid account
				$deductFromUserWallet = $wallet->deductFromUserWallet($uid, $amount);
				if($deductFromUserWallet["result"] == true)
				{
					// $transfer_uid Avatar
					$transferUserAvatarInfo = $userAvatar->fetchUserAvatarByUserId($transfer_uid);
					$transferUserAvatarThumb = $transferUserAvatarInfo[0]['thumb'];
					// Add notification to $uid start
					$notificationInfo = array();
					$notificationInfo['subject_type'] = "user";
					$notificationInfo['subject_id'] = $transfer_uid;
					$notificationInfo['object_type'] = "user";
					$notificationInfo['object_id'] = $uid;
					$notificationInfo['notification_message'] = "$".$amount." has been deducted from your wallet";
					$notificationInfo['params'] = '{"user_id":"'.$transfer_uid.'", "user_avatar_thumb":"'.$transferUserAvatarThumb.'"}';

					$notification->createNotification($notificationInfo);
					// Add notification to $uid end

					$transaction = new Transaction($DB_con);
					$transactionInfo = array();
					$transactionInfo['userid'] = $uid;
					$transactionInfo['type'] = 'transfer_amount_success';
					$transactionInfo['desc'] = 'Amount successfully transfered.';
					$transactionInfo['amount'] = $amount;
					$transactionInfo['object_userid'] = $transfer_uid;
					$transaction->createTransactionLog($transactionInfo);
				}
				else
				{
					$transaction = new Transaction($DB_con);
					$transactionInfo = array();
					$transactionInfo['userid'] = $uid;
					$transactionInfo['type'] = 'transfer_amount_fail';
					$transactionInfo['desc'] = 'Amount added to object_user account but not deducted from subject_user account';
					$transactionInfo['amount'] = $amount;
					$transactionInfo['object_userid'] = $transfer_uid;
					$transaction->createTransactionLog($transactionInfo);
				}
			}
			else
			{
				$response["ERROR"]="Unable to transfer! Please try again.";
			}
		}
		else
		{
			$response["ERROR"]="Less Amount! Please add amount to your wallet.";
		}

		echo json_encode($response);

	}
	else
	{
		$error[] = "Wrong Details!";
		$response["ERROR"] = $error;
		echo json_encode($response);
	}	
}

?>