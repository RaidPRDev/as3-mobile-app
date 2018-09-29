<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once '../dbconfig.php';

include_once '../post/class.post.php';
include_once 'class.group.php';
include_once '../class.wallet.php';
include_once '../notifications/class.notification.php';
include_once '../stripe/goaliemind/Transaction.php';

if(isset($_POST['login']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$groupid = trim($_POST['groupid']);
	$content = trim($_POST['content']);
	$payperpost = trim($_POST['payperpost']);
	$postdate = trim($_POST['postdate']);

	$pay_per_post = ($payperpost == "true") ? '1' : '0';
		
	if($user->login($uname,$umail,$upass))
	{
		$post = new POST($DB_con);
		$group = new GROUP($DB_con);
		$wallet = new WALLET($DB_con);
		$notification = new NOTIFICATION($DB_con);

		//Checking for the existence of group & its users
		$getGroupDetails = $group->getGroupDetails($groupid);
		$checkForGroupOwner = $group->checkForGroupOwner($groupid, $uid);
		$checkForGroupUser = $group->checkForGroupUser($groupid, $uid);

		if(isset($getGroupDetails["ERROR"]) || isset($checkForGroupOwner["ERROR"]) || isset($checkForGroupUser["ERROR"]))
		{
			$error[] = "An error has occurred! Please try again.";
		}
		else if(count($getGroupDetails["groupDetails"]) == 0)
		{
			$error[] = "Invalid Group";
		}
		else if(count($checkForGroupOwner["groupDetails"]) == 0 && count($checkForGroupUser["groupUserDetails"]) == 0)
		{
			$error[] = "Invalid Group User";
		}
		else
		{
			$postInfo = array();
			$postInfo['subject_type'] = "user";
			$postInfo['subject_id'] = $uid;
			$postInfo['object_type'] = "group";
			$postInfo['object_id'] = $groupid;
			$postInfo['content'] = $content;
			$postInfo['pay_per_post'] = $pay_per_post;
			$postInfo['post_date'] = $postdate;
			$postInfo['created_by'] = $uid;

			$addPost = $post->addPost($postInfo);

			if($addPost["result"] == true)
			{
				if($pay_per_post == 1)
				{
					$group->disableGroupSubscription(); //disable group subscription if wallet amount is 0
					$getGroupSubscribedUsersIds = $group->getGroupSubscribedUsersIds($groupid);
					$subscribedUsersIds = $getGroupSubscribedUsersIds["subscribedUsersIds"];
					
					//Deduct amount from wallet of subscribed users
					$amount = 1; // for now we set $amount=1
					$deductFromMultipleUserWallet = $wallet->deductFromMultipleUserWallet($subscribedUsersIds, $amount);

					//Notification
					$notificationInfo = array();
					$notificationInfo['subject_type'] = "group_post";
					$notificationInfo['subject_id'] = $addPost["postid"];
					$notificationInfo['object_type'] = "user";
					$notificationInfo['object_id'] = $subscribedUsersIds;
					$notificationInfo['notification_message'] = "$".$amount." have been deducted from your wallet";
					$notificationInfo['params'] = "";

					$notification->createMultipleNotification($notificationInfo);
				}
				
				$response["OK"] = "Your message has been successfully added to a group.";
				$response["postid"] = $addPost["postid"];
				$response["message"] = $content;

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