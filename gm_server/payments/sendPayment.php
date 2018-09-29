<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once '../dbconfig.php';
include_once 'class.payment.php';
require_once '../class.setting.php';
require_once '../notifications/class.notification.php';
require_once '../class.wallet.php';
require_once '../avatar/class.avatar.php';

if(isset($_POST['login']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$customerid = trim($_POST['customerid']);
	$paymentid = trim($_POST['paymentid']);
	//$tokenid = trim($_POST['tokenid']);
	$cardid = trim($_POST['cardid']);
	$fundamount = trim($_POST['amount']);
	$currency = "usd";
	$projectid = trim($_POST['projectid']);
	
	// Get a unique code use: uniqid();

	// Stripe works with cents.  Convert dollars to cents.
	//$amount = 24 * 100;  
	$amount = $fundamount * 100;  
	
	if($user->login($uname,$umail,$upass))
	{
		//Get User Info
		$referid = $_SESSION['referid'];
		$organisationreferid = $_SESSION['organisationreferid'];

		//Get User Avatar Info
		$userAvatar = new AVATAR($DB_con);
		$userAvatarInfo = $userAvatar->fetchUserAvatarByUserId($uid);
		$userAvatarThumb = $userAvatarInfo[0]['thumb'];

		//Get Project Info
		$projects = new PROJECT($DB_con);
		$projectInfo = $projects->getProjectInfoForFund($projectid);
		$projectuserid = $projectInfo['userid'];
		$projectusername = $user->getUserInfo($projectuserid)["userInfo"][0]["username"];
		$projectusertitle = $projectInfo['title'];
		$projectAType = $projectInfo['type'];
		$projectAvailableStars = $projectInfo['stars'];
		$projectStatus = $projectInfo['status'];
		
		// Project Owner Avatar
		$projectUserAvatarInfo = $userAvatar->fetchUserAvatarByUserId($projectuserid);
		$projectUserAvatarThumb = $projectUserAvatarInfo[0]['thumb'];
		
		// echo "projectStatus: ", $projectStatus;
		
		//Get the permit for project fund
		if ($projectStatus == 0)
		{
			$response["CLOSED"] = "This Project is Closed";
			echo json_encode($response);
			exit;
		}
		
		$payment = new PAYMENT($DB_con);
		
		$paymentInfo = array();
		$paymentInfo["uid"] = $uid;
		$paymentInfo["customerid"] = $customerid;
		$paymentInfo["paymentid"] = $paymentid;
		//$paymentInfo["tokenid"] = $tokenid;
		$paymentInfo["cardid"] = $cardid;
		$paymentInfo["currency"] = $currency;
		$paymentInfo["amount"] = $amount;
		$paymentInfo["email"] = $umail;
		
		// Try to charge the customer card
		$customer = $payment->processPayment($paymentInfo, $projectid, $fundamount);

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
			$paymentInfoDB["amount"] = $fundamount;
			$paymentInfoDB["currency"] = $currency;
			$addProcessPaymentDB = $payment->addUserProcessPaymentInDB($paymentInfoDB);

			//If data inserted successfully into table user_payments
			//Update the project status for user account 3
			//Insert the details of project fund into table project_funds
			if(!isset($addProcessPaymentDB["ERROR"]))
			{
				//update project status
				$updateProjectStatus = $projects->updateProjectStatus($projectid, $projectAType);

				//Retrieve Goaliemind_%, refer_% and organisation_refer_%
				$settings = new SETTING($DB_con);
				$goaliemindpercent = $settings->getGoaliemindpercent(); // Declared goaliemind_%
				$referpercent = $settings->getReferpercent(); // refer_% from goaliemind amt
				$organisationreferpercent = $settings->getOrganiserReferPercent(); // oraganisation_refer_% from goaliemind amt
				$goaliemind_amt = ($goaliemindpercent / 100) * $fundamount; // Goaliemind calculated amt
				
				//Calculate Goaliemind_amt, refer_amt and organisation_refer_amt 
				if($referid == 0)
				{
					$referred_amt = 0;
					$organisationrefer_amt = 0;
				}
				else if($referid != 0 && $organisationreferid == 0)
				{
					$referred_amt = ($referpercent / 100) * $goaliemind_amt;
					$organisationrefer_amt = 0;
				}
				else
				{
					$referred_amt = ($referpercent / 100) * $goaliemind_amt;
					$organisationrefer_amt = ($organisationreferpercent / 100) * $goaliemind_amt;
				}
				$goaliemind_received_amt = $goaliemind_amt-$referred_amt-$organisationrefer_amt; 
				
				// Add fund details in table project_funds
				$projectFundInfo = array();
				$projectFundInfo['user_payment_id'] = $addProcessPaymentDB["user_paymentid"];
				$projectFundInfo['amount'] = $fundamount;
				$projectFundInfo['projectid'] = $projectid;
				$projectFundInfo['userid'] = $uid;
				$projectFundInfo['referid'] = $referid;
				$projectFundInfo['organisationreferid'] = $organisationreferid;
				$projectFundInfo['goaliemind_amt'] = $goaliemind_received_amt;
				$projectFundInfo['referred_amt'] = $referred_amt;
				$projectFundInfo['organisationrefer_amt'] = $organisationrefer_amt;
				
				$projectfund = $projects->saveProjectFund($projectFundInfo); 

				//Add Stars into users & projects table
				$starForOneDollar = $settings->getStarForOneDollar();
				$starsToAdd = $fundamount * $starForOneDollar;
				$projects->addStarsToProject($projectid, $projectAvailableStars, $starsToAdd);
				$user->addStarsToUser($projectuserid, $starsToAdd);

				// Add notification to project owner
				$notification = new NOTIFICATION($DB_con);
				$notificationInfo = array();
				$notificationInfo['subject_type'] = "user";
				$notificationInfo['subject_id'] = $uid;
				$notificationInfo['object_type'] = "user";
				$notificationInfo['object_id'] = $projectuserid;
				$notificationInfo['notification_message'] = $uname." has funded to your " . $projectusertitle ." project.";
				$notificationInfo['params'] = '{"project_id":"'.$projectid.'", "user_id":"'.$uid.'", "user_avatar_thumb":"'.$userAvatarThumb.'"}';

				$notification->createNotification($notificationInfo);
				
				// Add notification to user who funded
				$notificationInfoFundUser = array();
				$notificationInfoFundUser['subject_type'] = "user";
				$notificationInfoFundUser['subject_id'] = $projectuserid;
				$notificationInfoFundUser['object_type'] = "user";
				$notificationInfoFundUser['object_id'] = $uid;
				$notificationInfoFundUser['notification_message'] = "You have funded " . $projectusername ."'s " . $projectusertitle . " project.";
				$notificationInfoFundUser['params'] = '{"project_id":"'.$projectid.'", "user_id":"'.$projectuserid.'", "user_avatar_thumb":"'.$projectUserAvatarThumb.'"}';

				$notification->createNotification($notificationInfoFundUser);

				// Logging the notification in db start
				$transaction = new Transaction($DB_con);
				$transactionInfo = array();
				$transactionInfo['userid'] = $uid;
				$transactionInfo['projectid'] = $projectid;
				$transactionInfo['type'] = 'notification';
				$transactionInfo['desc'] = $uname.' has funded to {"project_id":"'.$projectid.'"}';
				$transactionInfo['amount'] = $fundamount;
				$transaction->createTransactionLog($transactionInfo);
				// Logging the notification in db end

				//Add amount to project creator, referral user and Organisation referral user wallet start
				$wallet = new WALLET($DB_con);
				$wallet->updateUserWallet($projectuserid, $fundamount); //Project creator wallet
				// Logging the project creator wallet in db start
				$transactionInfo2 = array();
				$transactionInfo2['userid'] = $projectuserid;
				$transactionInfo2['projectid'] = $projectid;
				$transactionInfo2['type'] = 'project_creator_wallet';
				$transactionInfo2['desc'] = '$'.$fundamount.' has been to added project creator wallet';
				$transactionInfo2['amount'] = $fundamount;
				$transaction->createTransactionLog($transactionInfo2);
				// Logging the project creator wallet in db end
				if($referid != 0)
				{
					$wallet->updateUserWallet($referid, $referred_amt); //Referral user wallet
					// Logging the referral user wallet in db start
					$transactionInfo3 = array();
					$transactionInfo3['userid'] = $referid;
					$transactionInfo3['projectid'] = $projectid;
					$transactionInfo3['type'] = 'referral_user_wallet';
					$transactionInfo3['desc'] = '$'.$referred_amt.' has been to added referral user wallet';
					$transactionInfo3['amount'] = $referred_amt;
					$transaction->createTransactionLog($transactionInfo3);
					// Logging the referral user wallet in db end

					if($organisationreferid != 0)
					{
						$wallet->updateUserWallet($organisationreferid, $organisationrefer_amt); //Organisation Referral user wallet
						// Logging the Organisation referral user wallet in db start
						$transactionInfo4 = array();
						$transactionInfo4['userid'] = $organisationreferid;
						$transactionInfo4['projectid'] = $projectid;
						$transactionInfo4['type'] = 'organisation_user_wallet';
						$transactionInfo4['desc'] = '$'.$organisationrefer_amt.' has been to added organisation referral user wallet';
						$transactionInfo4['amount'] = $organisationrefer_amt;
						$transaction->createTransactionLog($transactionInfo4);
						// Logging the Organisation referral user wallet in db end
					}
				}

				// Logging the Goiliemind wallet in db start
				$transactionInfo5 = array();
				$transactionInfo5['userid'] = 0;
				$transactionInfo5['projectid'] = $projectid;
				$transactionInfo5['type'] = 'goaliemind_wallet';
				$transactionInfo5['desc'] = '$'.$goaliemind_received_amt.' has been to added Goaliemind wallet';
				$transactionInfo5['amount'] = $goaliemind_received_amt;
				$transaction->createTransactionLog($transactionInfo5);
				// Logging the Goiliemind wallet in db end
				//Add amount to project creator, referral user and Organisation referral user wallet end
			}
		}
		
		$customer["created"] = $customer['chargeData']->created;
		$customer["balance_transaction"] = $customer['chargeData']->balance_transaction;
		$customer["invoice"] = $customer['chargeData']->id;
		$customer["projectuserid"] = $projectuserid;
		$customer["starsToAdd"] = $starsToAdd;
		echo json_encode($customer);
	}
	else
	{
		if (!$error) $error[] = "Wrong Details!";
		$response["ERROR"] = $error;
		echo json_encode($response);
	}	
}

?>