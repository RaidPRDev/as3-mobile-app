<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'dbconfig.php';

include_once 'avatar/class.avatar.php';
include_once 'avatar/class.avatar.data.php';
include_once 'payments/class.payment.php';
include_once 'friends/class.friends.php';
require_once 'class.setting.php';
require_once 'users/userSettings/class.user.settings.php';

if(isset($_GET['login']))
{
	
	$uname = trim($_GET['form_uname']);
	$umail = trim($_GET['form_umail']);
	$upass = trim($_GET['form_upass']);
		
	if($user->login($uname,$umail,$upass))
	{
		$response["OK"] = "User authenticated!";
		$response["user_session"] = $_SESSION['user_session'];
		$response["username"] = $_SESSION['username'];
		$response["useremail"] = $_SESSION['useremail'];
		$response["accountType"] = $_SESSION['accountType'];
		$response["parentid"] = $_SESSION['parentid'];
		$response["gender"] = $_SESSION['gender'];
		$response["birthDate"] = $_SESSION['birthDate'];
		$response["isAuthorized"] = $_SESSION['isAuthorized'];
		$response["authorizeKey"] = $_SESSION['authorizeKey'];
		$response["registerDate"] = $_SESSION['registerDate'];
		$response["signedInDate"] = $_SESSION['signedInDate'];
		$response["customerid"] = $_SESSION['customerid'];
		$response["paymentid"] = $_SESSION['paymentid'];
		$response["referid"] = $_SESSION['referid'];	
		$response["organisationreferid"] = $_SESSION['organisationreferid'];	
		$response["user_code"] = $_SESSION['user_code'];	
		$response["stars"] = $_SESSION['stars'];	
 		$response["wallet"] = $_SESSION['wallet'];	
 		$response["provider"] = $_SESSION['provider'];	
 		$response["provider_uid"] = $_SESSION['provider_uid'];	
		
		$uid = $response["user_session"];
		
		$payment = new PAYMENT($DB_con);
		
		// We need to check if MindGoalie Personal, if it is we need to get the payment id from the Parent.
		if ($response["accountType"] == $PERSONAL_MINDGOALIE)
		{
			$parentUser = $user->getUserInfo($response["parentid"])["userInfo"][0];
			$response["paymentid"] = $parentUser['paymentid'];
			$paymentsInfo = $payment->getPaymentList($parentUser['id']);	
			$getInfo = json_encode($paymentsInfo);
			$response["paymentsInfo"] = $getInfo;
			
			// TODO: Get Parent Settings for MindGoalie ( How much can they spend, etc... )
		}
		else if ($response["accountType"] == $GOALIEMIND_ACCOUNT)
		{
			$paymentsInfo = $payment->getPaymentList($uid);	
			$result = count($paymentsInfo);
			$getInfo = json_encode($paymentsInfo);
			$response["OK"] = "User authenticated!";
			$response["paymentsInfo"] = $getInfo;
		}
		else if ($response["accountType"] == $ORGANIZATION_MINDGOALIE)
		{
			$paymentsInfo = $payment->getPaymentList($uid);	
			$result = count($paymentsInfo);
			$getInfo = json_encode($paymentsInfo);
			$response["OK"] = "User authenticated!";
			$response["paymentsInfo"] = $getInfo;
		}

		// Create User Default Settings
		$userSettings = new USER_SETTINGS($DB_con);
		$userSettingsData = $userSettings->getUserSettingsList($uid);
		$response["userSettings"] = $userSettingsData["user_settings"];
		
		$avatar = new AVATAR($DB_con);
		$fetchUserAvatarByUserId = $avatar->fetchUserAvatarByUserId($uid);
		$response["userAvatarData"] = $fetchUserAvatarByUserId;

		$projects = new PROJECT($DB_con);
		$fetchUserTotalProjectsByUserId = $projects->fetchUserTotalProjectsByUserId($uid);
		$response["projectsTotal"] = $fetchUserTotalProjectsByUserId["projectsTotal"];

		$friends = new FRIENDS($DB_con);
		$fetchUserTotalFriendsByUserId = $friends->fetchUserTotalFriendsByUserId($uid);
		$response["friendsTotal"] = $fetchUserTotalFriendsByUserId["friendsTotal"];
		
		//Retrieve Settings value
		$settings = new SETTING($DB_con);
		$retrieveSettings = $settings->retrieveSettings();
		$response["SETTINGS"] = $retrieveSettings;
		
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