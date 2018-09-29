<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once '../class.project.php';
require_once '../notifications/class.notification.php';
include_once '../mailer/class.mailer.php';
include_once '../avatar/class.avatar.php';

if(isset($_GET['sendProjectNotifyEmail']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['upass']);
	$projectId = trim($_GET['projectId']);
	$initials = strtoupper(substr($uname, 0, 1));	
	
	if($user->login($uname,$umail,$upass))
	{
		$mailer = new MAILER($DB_con);
		$mailer->initBackgroundProcess();
		
		$project = new PROJECT($DB_con);
		$projectInfo = $project->getProjectDetails($projectId)[0];
		
		// Get User Avatar
		$avatar = new AVATAR($DB_con);
		$userAvatar = $avatar->fetchUserAvatarByUserId($uid);
		if (count($userAvatar) > 0) $userAvatarThumb = $userAvatar[0]["thumb"]; else $userAvatarThumb = "user_110_thumb";
		
		// Add Notification for User 
		$notification = new NOTIFICATION($DB_con);
		$notificationInfo = array();
		$notificationInfo['subject_type'] = "project";
		$notificationInfo['subject_id'] = $projectInfo["id"];
		$notificationInfo['object_type'] = "user";
		$notificationInfo['object_id'] = $uid;
		$notificationInfo['notification_message'] = $uname . " has created a new project: " . $projectInfo['title'];
		$notificationInfo['params'] = '{"project_id":"'.$projectInfo['id'].'", "user_id":"'.$uid.'", "user_avatar_thumb":"'.$userAvatarThumb.'"}';
		$notification->createNotification($notificationInfo);
		
		$response["OK"] = "Project Created Email Sent!";
		echo json_encode($response);
		
		// var_dump($projectInfo);
		
		// Send Email to friend
		$emailInfo = array();
		$emailInfo["uname"] = $uname;
		$emailInfo["initials"] = $initials;
		$emailInfo["projectId"] = $projectInfo['id'];
		$emailInfo["projectThumb"] = $projectInfo['image'];
		$emailInfo["projectTitle"] = $projectInfo['title'];
		$emailInfo["projectDescription"] = $projectInfo['description'];
		$emailInfo["projectTagName"] = $projectInfo['tagName'];
		$emailInfo["projectTagIcon"] = $projectInfo['tagIcon'];
		$emailInfo["projectTimestamp"] = $projectInfo['timestamp'];
		$emailInfo["projectMonth"] = $projectInfo['month'];
		$emailInfo["projectYear"] = $projectInfo['year'];
		$imagePath = $SERVER_PATH . "media/images/" . $emailInfo["projectMonth"] . "_" . $emailInfo["projectYear"] . "/";
		$emailInfo["fullImagePath"] = $imagePath . $emailInfo["projectThumb"];
		$tagIconPath = $SERVER_PATH . "project/tag_icons/";
		$emailInfo["fullTagIconPath"] = $tagIconPath . $emailInfo["projectTagIcon"];
		
		$emailInfo["emailTo"] = $umail;
		$emailInfo["emailSubject"] = 'A project has been created on GoalieMind!';
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

<p>You have created a new project!</p>
<img src="<?php echo $emailInfo["fullImagePath"]; ?>"/><br>
<b>Tag:</b> <?php echo $emailInfo["projectTagName"];  ?><br>
<b>Title:</b> <?php echo $emailInfo["projectTitle"];  ?><br>
<b>Description:</b> <?php echo $emailInfo["projectDescription"];  ?><br>
<b>Created:</b> <?php echo $emailInfo["timestamp"];  ?><br>

<?php
    return ob_get_clean();
} ?>

<?php 
function getEmailBlockHtml ($emailInfo) 
{ 
	ob_start(); 
	
	include_once 'mail/projectcreated.php';	
	
	return ob_get_clean();
}
?>

