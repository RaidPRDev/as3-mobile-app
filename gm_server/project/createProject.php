<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../dbconfig.php';
include_once '../class.wallet.php';
include_once '../notifications/class.notification.php';
include_once '../stripe/goaliemind/Transaction.php';

$ext = ".jpg";
// $ext = ".png";

if (isset($_GET['newproject']))
{
	/*
		var urlvars:URLVariables = new URLVariables(); 
		urlvars.newproject = true;
		urlvars.uname = userData.username;
		urlvars.upass = userData.password;
		urlvars.umail = userData.email;
		urlvars.atype = userData.accountType;
		urlvars.parentid = userData.parentUserid;
		urlvars.description = projectInfo.description;
		urlvars.tags = projectInfo.tags;
		urlvars.month = now.month;
		urlvars.year = now.fullYear;	
	*/
	$uname = trim($_GET['uname']);
	$upass = trim($_GET['upass']);
	$umail = trim($_GET['umail']);
	$atype = trim($_GET['atype']);
	$parentid = trim($_GET['parentid']);
	$title = trim($_GET['title']);
	$description = trim($_GET['description']);
	$tags = trim($_GET['tags']);
	$timestamp = trim($_GET['time']);
	$year = trim($_GET['year']);
	$month = trim($_GET['month']);
	$groupids = trim($_GET['groupids']);		
	$payPerPost = trim($_GET['payPerPost']);
	$pay_per_post = ($payPerPost == "true") ? '1' : '0';		
	
	if($user->login($uname,$umail,$upass))
	{
		$userid = $_SESSION['user_session'];
		
		$imageName = MD5($userid + "_" + $uname + "_" + $timestamp);
		$thumbName = $imageName . "_thumb" . $ext;
		$imageName .= $ext;
		
		// create new project item
		// createProject($userid, $description, $tags, $acctype, $timestamp)
		
		$project = new PROJECT($DB_con);
		$notification = new NOTIFICATION($DB_con);
		
		$project->createProject($_SESSION['user_session'], $title, $description, $tags, $atype, $timestamp, $month, $year, $imageName, $thumbName, $groupids, $pay_per_post);
		
		$newprojectid = $_SESSION['newprojectid'];
	
		// public function createProjectMedia($userid, $projectid, $imageName, $thumbName, $videoName, $month, $year, $timestamp)
		$project->createProjectMedia($_SESSION['user_session'], $newprojectid, $imageName, $thumbName, '', $month, $year, $timestamp);

		//If pay_per_post is true 
		if($pay_per_post == '1')
		{
			//disable project subscription if wallet amount is 0
			$project->disableProjectSubscription();
			$organisation_id = $userid; 
			$getProjectSubscribedUsersIds = $project->getProjectSubscribedUsersIds($organisation_id); 
			$subscribedUsersIds = $getProjectSubscribedUsersIds["subscribedUsersIds"];

			if(count($subscribedUsersIds) != 0)
			{
				//Deduct amount from wallet of subscribed users
				$wallet = new WALLET($DB_con);
				$amount = 1; // for now we set $amount=1
				$deductFromMultipleUserWallet = $wallet->deductFromMultipleUserWallet($subscribedUsersIds, $amount);

				//Assign subscribed users as project users
				$addUsersToProject = $project->addUsersToProject($newprojectid, $subscribedUsersIds);

				//Notification to users
				$notificationInfo = array();
				$notificationInfo['subject_type'] = "charge_to_view_project";
				$notificationInfo['subject_id'] = $newprojectid;
				$notificationInfo['object_type'] = "user";
				$notificationInfo['object_id'] = $subscribedUsersIds;
				$notificationInfo['notification_message'] = "$".$amount." have been deducted from your wallet to view the project";
				$notificationInfo['params'] = '{"projectid":"'.$newprojectid.'"}';

				$notification->createMultiNotification($notificationInfo);

				//Logging the transactions into DB
				$transaction = new transaction($DB_con);
				$transactionInfo = array();
				$transactionInfo["userid"] = $subscribedUsersIds;
				$transactionInfo["type"] = "user_wallet_deduct";
				$transactionInfo["desc"] = "$".$amount." have been deducted from users wallet";
				$transactionInfo["amount"] = $amount;
				$transactionInfo["projectid"] = $newprojectid;

				$transaction->createMultiTransactionLog($transactionInfo);
			}
		}

		//Retrieve Notification templates for project owner
		$getNotifyTempId = $notification->getNotifyTempId($screen = 'project_posted', $account = 'project_owner', $projectTagId = $tags);
		$getNotifyTempMsg = $notification->getNotifyTempMsg($screen = 'project_posted', $account = 'project_owner', $projectTagId = $tags);

		//Notification to project owner start
		if(count($getNotifyTempId)>0)
		{
			$notifyPrjOwner = array();
			$notifyPrjOwner['subject_type'] = "project_posted";
			$notifyPrjOwner['subject_id'] = $newprojectid;
			$notifyPrjOwner['object_type'] = "user";
			$notifyPrjOwner['object_id'] = $userid;
			$notifyPrjOwner['params'] = '{"projectid":"'.$newprojectid.'","tagid":"'.$tags.'"}';
			$notifyPrjOwner['msg'] = $getNotifyTempId;
			$createNotifyPrjOwner = $notification->createMultiNotificationToUser($notifyPrjOwner);
		}
		//Notification to project owner end
		
		$response["OK"] = "Project Created!";
		$response["userid"] = $userid;
		$response["projectid"] = $newprojectid;
		$response["month"] = $month;
		$response["year"] = $year;
		$response["imageName"] = $imageName;
		$response["thumbName"] = $thumbName;
		$response["notification"] = $getNotifyTempMsg;
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