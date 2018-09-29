<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once 'class.friends.php';
require_once '../notifications/class.notification.php';
include_once '../mailer/class.mailer.php';
include_once '../avatar/class.avatar.php';

if(isset($_GET['sendFriendRequest']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	
	$friendUserId = trim($_GET['fuid']);
	$friendUserName = trim($_GET['funame']);
	$friendUserIdEmail = trim($_GET['fumail']);
	
	$friends = new FRIENDS($DB_con);
	
	// Check we already added this Friend and isValidated
	$validationInfo = $friends->getFriendValidationInfo($uid, $friendUserId)[0];
	
	if ($validationInfo["isAuthorized"] == 0)
	{
		$mailer = new MAILER($DB_con);
		$mailer->initBackgroundProcess();
		
		$initials = strtoupper(substr($uname, 0, 1));
		
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
		
		if ($isAuthorized == 1)
		{
			$notificationInfo['notification_message'] = "You are now friends with " . $friendUserName;
		}
		else $notificationInfo['notification_message'] = "You have sent a friend request to " . $friendUserName;
		
		$notificationInfo['params'] = '{"friend_id":"'.$friendUserId.'", "user_id":"'.$uid.'", "user_avatar_thumb":"'.$friendAvatarThumb.'"}';

		$notification->createNotification($notificationInfo);

		// Add Notification for Friend 
		$notificationInfoFriend = array();
		$notificationInfoFriend['subject_type'] = "user";
		$notificationInfoFriend['subject_id'] = $uid;
		$notificationInfoFriend['object_type'] = "user";
		$notificationInfoFriend['object_id'] = $friendUserId;
		
		if ($isAuthorized == 1)
		{
			$notificationInfoFriend['notification_message'] = "You are now friends with " . $uname;
		}
		else $notificationInfoFriend['notification_message'] = $uname." wants to be your goalie friend.";
		
		$notificationInfoFriend['params'] = '{"friend_id":"'.$uid.'", "user_id":"'.$friendUserId.'", "authorizeid":"'.$validationInfo['authorizeid'].'", "user_avatar_thumb":"'.$userAvatarThumb.'"}';

		$notification->createNotification($notificationInfoFriend);
		
		$response["friendUserId"] = $friendUserId;
		$response["friendUserName"] = $friendUserName;
		$response["friendAvatarThumb"] = $friendAvatarThumb;
		echo json_encode($response);
		
		$mailer->startBackgroundProcess();
		
		// Send Email to friend
		$emailInfo = array();
		$emailInfo["uname"] = $uname;
		$emailInfo["initials"] = $initials;
		$emailInfo["newFriendId"] = $friendUserId;
		$emailInfo["friendUserId"] = $friendUserId;
		$emailInfo["friendUserName"] = $friendUserName;
		$emailInfo["friendUserIdEmail"] = $friendUserIdEmail;
		$emailInfo["authorizeid"] = $validationInfo['authorizeid'];
		$emailInfo["emailTo"] = $friendUserIdEmail;
		$emailInfo["emailSubject"] = $emailInfo["uname"] . ' wants to be friends on GoalieMind!';
		$emailInfo["serverPath"] = $SERVER_PATH; 
		$emailInfo["emailHtmlBlock"] = getEmailBlockHtml($emailInfo);
		$emailInfo["emailNonHtmlBlock"] = getEmailBlockNonHtml($emailInfo);
		
		$responseMailer = $mailer->sendEmailMessage($emailInfo);
	}
	else
	{
		$response["DUPLICATE"] = $friendUserName . " has already accepted your friend request!";
		
		echo json_encode($response);
		exit;
	}
	
}
else
{
	$response["ERROR"] = "An error has occurred when adding a friend. Please try again.";
	echo json_encode($response);
}

// For previewing email only

if(isset($_GET['preview']))
{
	$mailer = new MAILER($DB_con);
	$mailer->initBackgroundProcess();
			
	$emailInfo = array();
	$emailInfo["uname"] = 'fania';
	$emailInfo["newFriendId"] = 126;
	$emailInfo["friendUserId"] = "13";
	$emailInfo["friendUserName"] = "Lisa";
	$emailInfo["friendUserIdEmail"] = "fania@raidpr.com";
	$emailInfo["serverPath"] = $SERVER_PATH; 
	$emailInfo["authorizeid"] = 'e0e206fe12201e0a27ac90c6d932a096';

	if ($_GET['preview'] == 0) echo getEmailBlockHtml($emailInfo); else echo getEmailBlockNonHtml($emailInfo);
}

?>
<?php function getEmailBlockNonHtml ($emailInfo) { ob_start(); ?>

<?php echo ($emailInfo['uname']) ?> wants to be friends on GoalieMind!<br>
<a href='<?php echo ($emailInfo["serverPath"] . 'friends/confirm/?v=' . $emailInfo['authorizeid']); ?>'>Confirm Request</a><br>

<?php
    return ob_get_clean();
} ?>

<?php 
function getEmailBlockHtml ($emailInfo) 
{ 
	ob_start(); 
	
	include_once 'mail/friendinvite.php';	
	
	return ob_get_clean();
}
?>

