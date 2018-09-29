<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once '../dbconfig.php';
include_once '../mailer/class.mailer.php';

if(isset($_POST['cp']))
{
	$changePasswordID = null;
	
	$mailer = new MAILER($DB_con);
	$mailer->initBackgroundProcess();
	
	$textInputField = trim($_POST['t']);	// Field = user or email
	
	$userReq = $user->validateAndCheckEmailOrUsername($textInputField);
	
	$usingEmail = (filter_var($textInputField, FILTER_VALIDATE_EMAIL));
	
	// If its a child requesting, we should get back the ChildUserInfo Array with id and username
	// Only Parent MindGoalie can change Child passwords within the app
	if (count($userReq['childUserInfo']) > 0)
	{
		$response["ERROR"] = "MindGoalie Personal account passwords can be changed by signing into the parent's MindGoalie account.";
		echo json_encode($response);
		return;
	}
	
	if (is_null($userReq['userInfo']))
	{
		$response["ERROR"] = "The username or email provided, could not be found.";
		echo json_encode($response);
		return;
	}
	
	// If we are here, we are either a MindGoalie Organization or GoalieMind account
	
	// Generate password token info
	$time = time();
	$validate_p1 = MD5($userReq['userInfo']['useremail'] . $time);
	$validate_p2 = MD5($time);
	$validationid = $userReq['userInfo']['id'] . "_" . $validate_p1 . "_" . $validate_p2;
	
	// Check if Password Reset Request has been sent before
	$requestExists = $user->checkChangePasswordIdExistsByUserId($userReq['userInfo']['id']);
	
	$requestCount = count($requestExists['info']);
	// var_dump($requestExists['info'][0]);
	
	if ($requestCount > 0)
	{
		// It was been requested before, so we will just update the newly created validationid.
		$cPassInfo = $requestExists['info'][0];
		$changePasswordID = $cPassInfo['id'];
		$cPassUpdate = $user->updateChangePasswordValidation($cPassInfo['id'], $cPassInfo['userid'], $validationid, $time);
		// var_dump($cPassUpdate);
	}
	else
	{
		// If we are here, means this is the first time user has requested.
		$addNewRequest = $user->createChangePasswordValidation($userReq['userInfo']['id'], $validationid, $time);
		// var_dump($addNewRequest);
		
		// If created we will get back a id
		if ($addNewRequest['result'] == true) $changePasswordID = $addNewRequest['changepasswordid'];
		else
		{
			$response["OK"] = "Error occured on creating validation, please try again.";
			echo json_encode($response);
		}
	}
	
	// If we are here, we should have our changePasswordID set.
	
	if (is_null($changePasswordID))
	{
		$response["OK"] = "Error occured on request check, please try again.";
		echo json_encode($response);
	}
	else
	{
		$maskEmail = hideEmail($userReq['userInfo']['useremail']);
		
		if (!$usingEmail) $response["OK"] = "Change password request sent to " . $maskEmail . ".";
		else $response["OK"] = "Change password request sent.";
		
		$response["changePasswordID"] = $changePasswordID;
		$response["userInfo"] = $userReq['userInfo'];
		$response["childUserInfo"] = $userReq['childUserInfo'];
		echo json_encode($response);		
		
		$mailer->startBackgroundProcess();
	
		// Send Change Password Email
		$initials = strtoupper(substr($userReq['userInfo']['username'], 0, 1));
		
		$emailInfo = array();
		$emailInfo["uid"] = $userReq['userInfo']['id'];
		$emailInfo["uname"] = $userReq['userInfo']['username'];
		$emailInfo["initials"] = $initials;
		$emailInfo["passwordid"] = $changePasswordID;
		$emailInfo["validationid"] = $validationid;
		$emailInfo["emailTo"] = $userReq['userInfo']['useremail'];
		$emailInfo["emailSubject"] = 'Password Reset for GoalieMind';
		$emailInfo["serverPath"] = $SERVER_PATH; 
		$emailInfo["emailHtmlBlock"] = getEmailBlockHtml($emailInfo);
		$emailInfo["emailNonHtmlBlock"] = getEmailBlockNonHtml($emailInfo);
		
		$responseMailer = $mailer->sendEmailMessage($emailInfo);
		
	}
}
else
{
	$response["ERROR"] = "An error has occurred. Please try again.";
	echo json_encode($response);
}

function hideEmail($email)
{
	$mail_segments = explode("@", $email);
	
	// We need to check for very short first email segments ex: m@gmail.com
	if (strlen($mail_segments[0]) > 3)
	{
		$mail_segments[0] = substr($mail_segments[0], 0, 2) . str_repeat("*", strlen($mail_segments[0]) - 2) . substr($mail_segments[0], -3);
	}
	
	$pos = strpos($mail_segments[1], '.');
	$mail_segments[1] = substr($mail_segments[1], 0, 2) . str_repeat("*", strlen($mail_segments[1]) - $pos+1) . substr($mail_segments[1], $pos-2);
	
	return implode("@", $mail_segments);
}

?>
<?php function getEmailBlockNonHtml ($emailInfo) { ob_start(); ?>

We received a request to reset your password. To reset your password, click the link below:<br>
<a href='<?php echo ($emailInfo["serverPath"] . 'users/change.php?id='.$emailInfo["uid"].'&v=' . $emailInfo["validationid"]); ?>'>Change Password</a><br>

<?php
    return ob_get_clean();
} ?>

<?php 
function getEmailBlockHtml ($emailInfo) 
{ 
	ob_start(); 
	
	include_once 'mail/changepassword.php';	
	
	return ob_get_clean();
}
?>

