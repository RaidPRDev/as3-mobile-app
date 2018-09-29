<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once '../dbconfig.php';
include_once '../payments/class.payment.php';
require_once '../class.wallet.php';
require_once '../notifications/class.notification.php';

if(isset($_POST['login']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$customerid = trim($_POST['customerid']);
	$paymentid = trim($_POST['paymentid']);
	$cardid = trim($_POST['cardid']);
	$amountToAdd = trim($_POST['amount']);
	$currency = "usd";
	
	// Stripe works with cents.  Convert dollars to cents.
	//$amount = 24 * 100;  
	$amount = $amountToAdd * 100;  
	
	if($user->login($uname,$umail,$upass))
	{
		$payment = new PAYMENT($DB_con);
		
		$paymentInfo = array();
		$paymentInfo["uid"] = $uid;
		$paymentInfo["customerid"] = $customerid;
		$paymentInfo["paymentid"] = $paymentid;
		$paymentInfo["cardid"] = $cardid;
		$paymentInfo["currency"] = $currency;
		$paymentInfo["amount"] = $amount;
		$paymentInfo["email"] = $umail;
		
		$projectid = 0;  //As user not funding for project, but adding amount to their own wallet. 

		// Try to charge the customer card
		$customer = $payment->processPayment($paymentInfo, $projectid, $amountToAdd);

		// If Payment is successful
		// Insert into database
		if(!isset($customer["ERROR"]))
		{
			// Add User Processed Payment in table user_payments
			$paymentInfoDB = array();
			$paymentInfoDB["userid"] = $uid;
			$paymentInfoDB["paymentid"] = $paymentid;
			$paymentInfoDB["transactionid"] = $customer['chargeData']->balance_transaction;
			$paymentInfoDB["chargeid"] = $customer['chargeData']->id;
			$paymentInfoDB["amount"] = $amountToAdd;
			$paymentInfoDB["currency"] = $currency;
			$addProcessPaymentDB = $payment->addUserProcessPaymentInDB($paymentInfoDB);

			//If data inserted successfully into table user_payments
			if(!isset($addProcessPaymentDB["ERROR"]))
			{
				//Add amount to user wallet start
				
				$wallet = new WALLET($DB_con);
				$wallet->updateUserWallet($uid, $amountToAdd); //Add amount to user wallet
				// Logging the user wallet in db start
				$transaction = new Transaction($DB_con);
				$transactionInfo = array();
				$transactionInfo['userid'] = $uid;
				$transactionInfo['type'] = 'add_wallet_self';
				$transactionInfo['desc'] = 'Fund has been added to user wallet';
				$transactionInfo['amount'] = $amountToAdd;
				$transaction->createTransactionLog($transactionInfo);
				// Logging the project creator wallet in db end*/

				// Add notification start
				$notification = new NOTIFICATION($DB_con);
				$notificationInfo = array();
				$notificationInfo['subject_type'] = "user";
				$notificationInfo['subject_id'] = $uid;
				$notificationInfo['object_type'] = "user";
				$notificationInfo['object_id'] = $uid;
				$notificationInfo['notification_message'] = "You have added $".$amountToAdd." to your wallet";
				$notificationInfo['params'] = "";

				$notification->createNotification($notificationInfo);
				// Add notification end
				
				//Add amount to user wallet end
			}
			$response["OK"] = "Amount has been added to Wallet";
			$response["created"] = $customer['chargeData']->created;
			$response["balance_transaction"] = $customer['chargeData']->balance_transaction;
			$response["invoice"] = $customer['chargeData']->id;
		}
		else
		{
			$response["ERROR"] = "Unable to process your wallet !";
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