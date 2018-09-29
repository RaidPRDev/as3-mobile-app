<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once 'class.friends.php';
require_once '../notifications/class.notification.php';
include_once '../mailer/class.mailer.php';
include_once '../avatar/class.avatar.php';

if(isset($_GET['acceptFriendRequest']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['upass']);
	$friendUserId = trim($_GET['fuid']);
	$authorizeid = trim($_GET['authorizeid']);
		
	if($user->login($uname,$umail,$upass))
	{
		
		$friends = new FRIENDS($DB_con);
				
		$response["hasBeenValidated"] = false;
		$isAuthorized = 1;
		
		$hasBeenValidated = $friends->hasBeenValidated($authorizeid);
		
		if ($hasBeenValidated["result"] == true)
		{
			$response["DUPLICATE"] = "You have already added this friend.";
			
			echo json_encode($response);
		}
		else
		{
			$sendValidation = $friends->validateFriend($isAuthorized, $authorizeid);
			if ($sendValidation["result"] == true)
			{
				$friendUserName = $user->getUserInfo($friendUserId)["userInfo"][0]["username"];
				$friendUserIdEmail = $user->getUserInfo($friendUserId)["userInfo"][0]["useremail"];
				$addFriend = $friends->addFriend($uid, $friendUserId, $friendUserName, $friendUserIdEmail, 1);
				
				$response["OK"] = "Friend has been validated.";
				$response["hasBeenValidated"] = true;
				
				// echo json_encode($response);
			}
		}
		
		if ($response["hasBeenValidated"] == true)
		{
			$userData = $user->getUserInfoRequest($uid);
			$friendUserName = $user->getUserInfo($friendUserId)["userInfo"][0]["username"];
			$friendUserIdEmail = $user->getUserInfo($friendUserId)["userInfo"][0]["useremail"];
			
			// Get User Avatar
			$avatar = new AVATAR($DB_con);
			$userAvatar = $avatar->fetchUserAvatarByUserId($uid);
			
			// Get Friend Avatar
			$friendAvatar = $avatar->fetchUserAvatarByUserId($friendUserId);
			$friendAvatarThumb = "";
				
			if (count($userAvatar) > 0)
			{
				$userAvatarThumb = $userAvatar[0]["thumb"];
			}
			else $userAvatarThumb = "user_110_thumb";
			
			if (count($friendAvatar) > 0)
			{
				$friendAvatarThumb = $friendAvatar[0]["thumb"];
			}
			else $friendAvatarThumb = "user_110_thumb";
				
			// Add Notification for User 
			$notification = new NOTIFICATION($DB_con);
			$notificationInfo = array();
			$notificationInfo['subject_type'] = "user";
			$notificationInfo['subject_id'] = $friendUserId;
			$notificationInfo['object_type'] = "user";
			$notificationInfo['object_id'] = $uid;
			
			$notificationInfo['notification_message'] = "You are now friends with " . $friendUserName;
			$notificationInfo['params'] = '{"friend_id":"'.$friendUserId.'", "user_id":"'.$uid.'", "user_avatar_thumb":"'.$friendAvatarThumb.'"}';

			$notification->createNotification($notificationInfo);

			// Add Notification for Friend 
			$notificationInfoFriend = array();
			$notificationInfoFriend['subject_type'] = "user";
			$notificationInfoFriend['subject_id'] = $uid;
			$notificationInfoFriend['object_type'] = "user";
			$notificationInfoFriend['object_id'] = $friendUserId;
			
			$notificationInfoFriend['notification_message'] = "You are now friends with " . $uname;
			$notificationInfoFriend['params'] = '{"friend_id":"'.$uid.'", "user_id":"'.$friendUserId.'", "user_avatar_thumb":"'.$userAvatarThumb.'"}';

			$notification->createNotification($notificationInfoFriend);
			
			$response["friendUserId"] = $friendUserId;
			$response["friendUserName"] = $friendUserName;
			$response["friendAvatarThumb"] = $friendAvatarThumb;
			echo json_encode($response);
		}
	}
	else
	{
		// Error when authenticating user
		$error[] = "Wrong Details!";
		$response["ERROR"] = $error;
		echo json_encode($response);
	}	
}
?>
