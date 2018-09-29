<?php

require_once '../../dbconfig.php';

include_once '../class.friends.php';
require_once '../../notifications/class.notification.php';
include_once '../../avatar/class.avatar.php';

if(isset($_GET['v']))
{
	$validateid = trim($_GET['v']);
	
	$friends = new FRIENDS($DB_con);
		
	$hasBeenValidated = $friends->hasBeenValidated($validateid);
	
	if ($hasBeenValidated["result"] == true)
	{
		$response["OK"] = "Friend has already been validated.";
		$response["hasBeenValidated"] = true;
	}
	else
	{
		$validateFriend = $friends->validateFriend(1, $validateid);
	
		if ($validateFriend["result"] == true)
		{
			$response["OK"] = "Friend has been validated.";
		} 
		else $response["ERROR"] = "An error has occurred when adding a friend. Please try again.";
	}

	if ($response['OK'])
	{
		$requestFriendInfo = $friends->getFriendInfoRequest($validateid);
		
		// $response["isAuthorized"] = $result[0]["isAuthorized"];
		// $response["userid"] = $result[0]["userid"];
		// $response["friendid"] = $result[0]["friendid"];
		
		$userData = $user->getUserInfoRequest($requestFriendInfo["userid"]);
		$friendUserData = $user->getUserInfoRequest($requestFriendInfo["friendid"]);
		
		// var_dump($userData);
		// var_dump($friendUserData);
		
		$emailInfo["uname"] = $userData['username'];
		$emailInfo["friendUserName"] = $friendUserData['username'];
		
		if (array_key_exists('hasBeenValidated', $response)) 
		{
			$emailInfo["hasBeenValidated"] = $response['hasBeenValidated'];
		}
		else 
		{
			$emailInfo["hasBeenValidated"] = false;
			
			// Send Notifications
			// Get User Avatar
			$avatar = new AVATAR($DB_con);
			$userAvatar = $avatar->fetchUserAvatarByUserId($userData["userid"]);
			if (count($userAvatar) > 0)
			{
				$userAvatarThumb = $userAvatar[0]["thumb"];
			}
			else $userAvatarThumb = "user_110_thumb";
			
			// Get Friend Avatar
			$friendAvatar = $avatar->fetchUserAvatarByUserId($friendUserData['userid']);
			$friendAvatarThumb = "";
			
			if (count($friendAvatar) > 0)
			{
				$friendAvatarThumb = $friendAvatar[0]["thumb"];
			}
			else $friendAvatarThumb = "user_110_thumb";
			
			// Add Notification for User 
			$notification = new NOTIFICATION($DB_con);
			$notificationInfo = array();
			$notificationInfo['subject_type'] = "user";
			$notificationInfo['subject_id'] = $friendUserData['userid'];
			$notificationInfo['object_type'] = "user";
			$notificationInfo['object_id'] = $userData["userid"];
			$notificationInfo['notification_message'] = "You are now friends with " . $friendUserData['username'];
			$notificationInfo['params'] = '{"friend_id":"'.$friendUserData['userid'].'", "user_id":"'.$userData["userid"].'", "user_avatar_thumb":"'.$friendAvatarThumb.'"}';

			$notification->createNotification($notificationInfo);
			
			// Add Notification for Friend 
			$notificationInfoFriend = array();
			$notificationInfoFriend['subject_type'] = "user";
			$notificationInfoFriend['subject_id'] = $userData["userid"];
			$notificationInfoFriend['object_type'] = "user";
			$notificationInfoFriend['object_id'] = $friendUserData['userid'];
			$notificationInfoFriend['notification_message'] = "You are now friends with " . $userData["username"];
			$notificationInfoFriend['params'] = '{"friend_id":"'.$userData["userid"].'", "user_id":"'.$friendUserData["userid"].'", "user_avatar_thumb":"'.$userAvatarThumb.'"}';

			$notification->createNotification($notificationInfoFriend);		
			
		}
		
		echo getEmailBlockHtml($emailInfo);
	}
}

?>

<?php 
function getEmailBlockHtml ($emailInfo) 
{ 
	ob_start(); 
	
	include_once 'friendrequest.html.php';	
	
	return ob_get_clean();
}
?>