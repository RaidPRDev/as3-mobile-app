<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once '../class.project.php';
require_once '../notifications/class.notification.php';
include_once '../mailer/class.mailer.php';
include_once '../avatar/class.avatar.php';

if(isset($_GET['projectPayNotify']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['umail']);
	$upass = trim($_GET['upass']);
	
	// We will send a payment email to Fund(er) and Owner of Project
	// We will need:  projectID, Funder userID, Project Owner userID
	/*
		urlVars.userid = data.userid;
		urlVars.projectuserid = data.projectuserid;
		urlVars.projectid = data.projectid;
		urlVars.amount = data.amount;
		urlVars.starsToAdd = data.starsToAdd;
	
	*/
	$projectId = trim($_GET['projectid']);
	$projectUserId = trim($_GET['projectuserid']);
	$fundAmount = trim($_GET['amount']);
	$starsAdded = trim($_GET['starsToAdd']);
	$created = trim($_GET['created']);
	$balance_transaction = trim($_GET['bt']);
	$invoice = trim($_GET['invoice']);
	
	if($user->login($uname,$umail,$upass))
	{
		$mailer = new MAILER($DB_con);
		$mailer->initBackgroundProcess();
		
		$userInfo = $user->getUserInfo($uid)["userInfo"][0];
		$projectUserInfo = $user->getUserInfo($projectUserId)["userInfo"][0];
				
		$userInitials = strtoupper(substr($uname, 0, 1));		
		$projectUserInitials = strtoupper(substr($projectUserInfo["username"], 0, 1));		
		
		// Get Project Info
		$project = new PROJECT($DB_con);
		$projectInfo = $project->getProjectDetails($projectId)[0];
		
		$response["OK"] = "Email Sent!";
		
		echo json_encode($response); 
		
		// Send Email to funder
		$emailInfo = array();
		$emailInfo["uname"] = $uname;
		$emailInfo["userInitials"] = $userInitials;
		$emailInfo["userEmail"] = $userInfo["useremail"];
		$emailInfo["projectUserInitials"] = $projectUserInitials;
		$emailInfo["projectUsername"] = $projectUserInfo["username"];
		$emailInfo["projectEmail"] = $projectUserInfo["useremail"];
		$emailInfo["projectTitle"] = $projectInfo["title"];
		$emailInfo["starsAdded"] = $starsAdded;
		$emailInfo["fundAmount"] = $fundAmount;
		$emailInfo["balance_transaction"] = $balance_transaction;
		$emailInfo["invoice"] = $invoice;
		$emailInfo["created"] = $created;
		$emailInfo["emailTo"] = $userInfo["useremail"];
		$emailInfo["emailSubject"] = "You have funded " . $emailInfo["projectUsername"] . "'s project!";
		$emailInfo["serverPath"] = $SERVER_PATH; 
		$emailInfo["emailHtmlBlock"] = getEmailBlockHtml($emailInfo);
		$emailInfo["emailNonHtmlBlock"] = getEmailBlockNonHtml($emailInfo);
		
		$mailer->startBackgroundProcess();
		
		$responseMailer = $mailer->sendEmailMessage($emailInfo);
		
		if (!is_null($responseMailer["OK"]))
		{
			$mailerOwner = new MAILER($DB_con);
			$mailerOwner->initBackgroundProcess();
			
			// Send Email to project owner 
			$emailInfoOwner = array();
			$emailInfoOwner["uname"] = $uname;
			$emailInfoOwner["userInitials"] = $userInitials;
			$emailInfoOwner["userEmail"] = $userInfo["useremail"];
			$emailInfoOwner["projectUserInitials"] = $projectUserInitials;
			$emailInfoOwner["projectUsername"] = $projectUserInfo["username"];
			$emailInfoOwner["projectEmail"] = $projectUserInfo["useremail"];
			$emailInfoOwner["projectTitle"] = $projectInfo["title"];
			$emailInfoOwner["starsAdded"] = $starsAdded;
			$emailInfoOwner["fundAmount"] = $fundAmount;
			$emailInfoOwner["balance_transaction"] = $balance_transaction;
			$emailInfoOwner["invoice"] = $invoice;
			$emailInfoOwner["created"] = $created;
			$emailInfoOwner["emailTo"] = $projectUserInfo["useremail"];
			$emailInfoOwner["emailSubject"] = "You received $" . $emailInfoOwner["fundAmount"] . " USD from " . $emailInfoOwner["uname"] . "!";
			$emailInfoOwner["serverPath"] = $SERVER_PATH; 
			$emailInfoOwner["emailHtmlBlock"] = getEmailOwnerBlockHtml($emailInfoOwner);
			$emailInfoOwner["emailNonHtmlBlock"] = getEmailOwnerBlockNonHtml($emailInfoOwner);
			
			$mailerOwner->startBackgroundProcess();
			$responseMailerOwner = $mailerOwner->sendEmailMessage($emailInfoOwner);
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

if(isset($_GET['preview']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$projectId = trim($_GET['projectid']);
	$projectUserId = trim($_GET['projectuserid']);
	$fundAmount = trim($_GET['amount']);
	$starsAdded = trim($_GET['starsToAdd']);
	$created = trim($_GET['created']);
	$balance_transaction = trim($_GET['bt']);
	$invoice = trim($_GET['invoice']);
	
	$mailer = new MAILER($DB_con);
			
	$userInfo = $user->getUserInfo($uid)["userInfo"][0];
	$projectUserInfo = $user->getUserInfo($projectUserId)["userInfo"][0];
	$project = new PROJECT($DB_con);
	$projectInfo = $project->getProjectDetails($projectId)[0];
			
	// var_dump($userInfo);		
	// var_dump($projectUserInfo);		
	// var_dump($projectInfo);		
		
	$userInitials = strtoupper(substr($uname, 0, 1));		
	$projectUserInitials = strtoupper(substr($projectUserInfo["username"], 0, 1));			
		
	// Send Email to user who funded
	$emailInfo = array();
	$emailInfo["uname"] = $uname;
	$emailInfo["userInitials"] = $userInitials;
	$emailInfo["userEmail"] = $userInfo["useremail"];
	$emailInfo["projectUserInitials"] = $projectUserInitials;
	$emailInfo["projectUsername"] = $projectUserInfo["username"];
	$emailInfo["projectEmail"] = $projectUserInfo["useremail"];
	$emailInfo["projectTitle"] = $projectInfo["title"];
	$emailInfo["starsAdded"] = $starsAdded;
	$emailInfo["fundAmount"] = $fundAmount;
	$emailInfo["balance_transaction"] = $balance_transaction;
	$emailInfo["invoice"] = $invoice;
	$emailInfo["created"] = $created;
	$emailInfo["emailTo"] = $projectUserInfo["useremail"];
	$emailInfo["emailSubject"] = "You have funded " . $emailInfo["projectUsername"] . "'s project!";
	$emailInfo["serverPath"] = $SERVER_PATH; 
	$emailInfo["emailHtmlBlock"] = getEmailBlockHtml($emailInfo);
	$emailInfo["emailNonHtmlBlock"] = getEmailBlockNonHtml($emailInfo);
	
	// Send Email to project owner 
	$emailInfoOwner = array();
	$emailInfoOwner["uname"] = $uname;
	$emailInfoOwner["userInitials"] = $userInitials;
	$emailInfoOwner["userEmail"] = $userInfo["useremail"];
	$emailInfoOwner["projectUserInitials"] = $projectUserInitials;
	$emailInfoOwner["projectUsername"] = $projectUserInfo["username"];
	$emailInfoOwner["projectEmail"] = $projectUserInfo["useremail"];
	$emailInfoOwner["projectTitle"] = $projectInfo["title"];
	$emailInfoOwner["starsAdded"] = $starsAdded;
	$emailInfoOwner["fundAmount"] = $fundAmount;
	$emailInfoOwner["balance_transaction"] = $balance_transaction;
	$emailInfoOwner["invoice"] = $invoice;
	$emailInfoOwner["created"] = $created;
	$emailInfoOwner["emailTo"] = $projectUserInfo["useremail"];
	$emailInfoOwner["emailSubject"] = "You received $" . $emailInfoOwner["fundAmount"] . " USD from " . $emailInfoOwner["uname"] . "!";
	$emailInfoOwner["serverPath"] = $SERVER_PATH; 
	$emailInfoOwner["emailHtmlBlock"] = getEmailOwnerBlockHtml($emailInfoOwner);
	$emailInfoOwner["emailNonHtmlBlock"] = getEmailOwnerBlockNonHtml($emailInfoOwner);
	
	// var_dump($emailInfo);		
	
	// getEmailBlockHtmlPreview($emailInfo);
}
?>
<?php function getEmailBlockNonHtml ($emailInfo) { ob_start(); ?>

<?php echo ($emailInfo['uname']) ?> funded your project!<br>

<?php
    return ob_get_clean();
} ?>

<?php 
function getEmailBlockHtml ($emailInfo) 
{ 
	ob_start(); 
	
	include_once 'mail/projectfunded.php';	
	
	return ob_get_clean();
}
?>

?>
<?php function getEmailOwnerBlockNonHtml ($emailInfo) { ob_start(); ?>

<?php echo ($emailInfo['projectUsername']) ?> funded your project!<br>

<?php
    return ob_get_clean();
} ?>

<?php 
function getEmailOwnerBlockHtml ($emailInfo) 
{ 
	ob_start(); 
	
	include_once 'mail/projectfundedtoowner.php';	
	
	return ob_get_clean();
}
?>

<!--
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>

    </body>
</html>
-->
