<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once 'class.friends.php';
require_once '../notifications/class.notification.php';
include_once '../mailer/class.mailer.php';
include_once '../avatar/class.avatar.php';

if(isset($_GET['addFriendRequest']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['upass']);
	$friendUserId = trim($_GET['fuid']);
	$friendUserName = trim($_GET['funame']);
	
	if (isset($_GET['fumail'])) $friendUserIdEmail = trim($_GET['fumail']);
	else $friendUserIdEmail = null;
		
	if (isset($_GET['isValidated'])) $isValidated = trim($_GET['isValidated']);
	else $isValidated = false;
		
		
	if($user->login($uname,$umail,$upass))
	{
		$friends = new FRIENDS($DB_con);
		
		if (is_null($friendUserIdEmail)) $friendUserIdEmail = $user->getUserInfo($friendUserId)["userInfo"][0]["useremail"];
				
		$isAuthorized = ($isValidated == true) ? 1 : 0;
		
		// Check we already added this Friend and isValidated is true = we coming from scanning user code
		if ($friends->isFriendAdded($uid, $friendUserId))
		{
			$response["DUPLICATE"] = "You have already added this friend.";
			
			echo json_encode($response);
			exit;
		}
		else
		{
			$addFriend = $friends->addFriend($uid, $friendUserId, $friendUserName, $friendUserIdEmail, $isAuthorized);
		
			if ($addFriend["result"] == true)
			{
				$mailer = new MAILER($DB_con);
				$mailer->initBackgroundProcess();
				
				$response["data"] = $addFriend;
				
				if ($isAuthorized == 1)
				{
					$response["OK"] = $friendUserName . " is now your friend!";
				}
				else $response["OK"] = "A request has been sent to " . $friendUserName;
				
				
				$initials = strtoupper(substr($uname, 0, 1));
				
				// Get User Avatar
				$avatar = new AVATAR($DB_con);
				$userAvatar = $avatar->fetchUserAvatarByUserId($uid);
				
				// Get Friend Avatar
				$friendAvatar = $avatar->fetchUserAvatarByUserId($response['data']['friendUserId']);
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
				$notificationInfo['subject_id'] = $response['data']['friendUserId'];
				$notificationInfo['object_type'] = "user";
				$notificationInfo['object_id'] = $uid;
				
				if ($isAuthorized == 1)
				{
					$notificationInfo['notification_message'] = "You are now friends with " . $response['data']['friendUserName'];
				}
				else $notificationInfo['notification_message'] = "You have sent a friend request to " . $response['data']['friendUserName'];
				
				$notificationInfo['params'] = '{"friend_id":"'.$response['data']['friendUserId'].'", "user_id":"'.$uid.'", "user_avatar_thumb":"'.$friendAvatarThumb.'"}';

				$notification->createNotification($notificationInfo);

				// Add Notification for Friend 
				$notificationInfoFriend = array();
				$notificationInfoFriend['subject_type'] = "user";
				$notificationInfoFriend['subject_id'] = $uid;
				$notificationInfoFriend['object_type'] = "user";
				$notificationInfoFriend['object_id'] = $response['data']['friendUserId'];
				
				if ($isAuthorized == 1)
				{
					$notificationInfoFriend['notification_message'] = "You are now friends with " . $uname;
				}
				else $notificationInfoFriend['notification_message'] = $uname." wants to be your goalie friend.";
				
				$notificationInfoFriend['params'] = '{"friend_id":"'.$uid.'", "user_id":"'.$response['data']['friendUserId'].'", "authorizeid":"'.$response['data']['authorizeid'].'", "user_avatar_thumb":"'.$userAvatarThumb.'"}';

				$notification->createNotification($notificationInfoFriend);
				
				$response["friendUserId"] = $response['data']['friendUserId'];
				$response["friendUserName"] = $response['data']['friendUserName'];
				$response["friendAvatarThumb"] = $friendAvatarThumb;
				echo json_encode($response);
				
				$mailer->startBackgroundProcess();
				
				// Send Email to friend
				$emailInfo = array();
				$emailInfo["uname"] = $uname;
				$emailInfo["initials"] = $initials;
				$emailInfo["newFriendId"] = $response['data']['newfriendid'];
				$emailInfo["friendUserId"] = $response['data']['friendUserId'];
				$emailInfo["friendUserName"] = $response['data']['friendUserName'];
				$emailInfo["friendUserIdEmail"] = $response['data']['friendUserIdEmail'];
				$emailInfo["authorizeid"] = $response['data']['authorizeid'];
				$emailInfo["emailTo"] = $response['data']['friendUserIdEmail'];
				$emailInfo["emailSubject"] = $emailInfo["uname"] . ' wants to be friends on GoalieMind!';
				$emailInfo["serverPath"] = $SERVER_PATH; 
				$emailInfo["emailHtmlBlock"] = getEmailBlockHtml($emailInfo);
				$emailInfo["emailNonHtmlBlock"] = getEmailBlockNonHtml($emailInfo);
				
				$responseMailer = $mailer->sendEmailMessage($emailInfo);
			} 
			else 
			{
				$response["ERROR"] = "An error has occurred when adding a friend. Please try again.";
				echo json_encode($response);
			}
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

