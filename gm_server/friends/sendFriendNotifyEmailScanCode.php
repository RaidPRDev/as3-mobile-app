<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once 'class.friends.php';
require_once '../notifications/class.notification.php';
include_once '../mailer/class.mailer.php';
include_once '../avatar/class.avatar.php';

if(isset($_GET['sendNotifyEmailFriendRequest']))
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
		$mailer = new MAILER($DB_con);
		$mailer->initBackgroundProcess();
		
		$friends = new FRIENDS($DB_con);
		
		$validationInfo = $friends->getFriendValidationInfo($uid, $friendUserId)[0];
		
		$userInfo = $user->getUserInfo($uid)["userInfo"][0];
		$friendUserInfo = $user->getUserInfo($friendUserId)["userInfo"][0];
				
		$initials = strtoupper(substr($uname, 0, 1));		
		
		echo json_encode($validationInfo); 
				
		// Send Email to friend
		$emailInfo = array();
		$emailInfo["uname"] = $uname;
		$emailInfo["initials"] = $initials;
		$emailInfo["newFriendId"] = $validationInfo['id'];
		$emailInfo["friendUserId"] = $friendUserInfo['id'];
		$emailInfo["friendUserName"] = $friendUserInfo['username'];
		$emailInfo["friendUserIdEmail"] = $friendUserInfo['useremail'];
		$emailInfo["authorizeid"] = $validationInfo['authorizeid'];
		$emailInfo["emailTo"] = $friendUserInfo['useremail'];
		$emailInfo["emailSubject"] = $emailInfo["uname"] . ' are now friends on GoalieMind!';
		$emailInfo["serverPath"] = $SERVER_PATH; 
		$emailInfo["emailHtmlBlock"] = getEmailBlockHtml($emailInfo);
		$emailInfo["emailNonHtmlBlock"] = getEmailBlockNonHtml($emailInfo);
		
		$mailer->startBackgroundProcess();
		
		$responseMailer = $mailer->sendEmailMessage($emailInfo);
		
		
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
	
	include_once 'mail/friendadded.php';	
	
	return ob_get_clean();
}
?>

