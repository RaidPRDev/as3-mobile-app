<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../dbconfig.php';
require_once '../users/userSettings/class.user.settings.php';

$umail = trim($_GET['e']);

if( filter_var($umail, FILTER_VALIDATE_EMAIL) )	
{
	echo "Valid Email";
}
else echo "Invalid Email";


if(isset($_GET['createReferCode']))
{
	$uid = trim($_GET['uid']);
	// $user_code = strtoupper(substr(md5($uid.time()),0,8));
	
	// echo "ID: " . $uid . "<br>";
	// echo "REFER_CODE: " . $user_code;
	
	//for ($i = 48; $i < 194; $i++)
	//{
		// $user->setUserCode($i);
		// echo "REFER_CODE UPDATED: " . $i;
		// Create User Default Settings
		$userSettingsInfo = [];
		$userSettingsInfo["userid"] = 16;
		$userSettingsInfo["enableFunding"] = 1;
		$userSettingsInfo["enableNotify"] = 1;
		$userSettingsInfo["enablePushNotify"] = 0;
		$userSettingsInfo["enableAddFriend"] = 1;
		$userSettingsInfo["maxFund"] = 1;
		
		$userSettings = new USER_SETTINGS($DB_con);
		$userSettingsData = $userSettings->addUserSettings($userSettingsInfo);
		
		echo "USER SETTINGS UPDATED: " . $userSettingsData["user_settings_id"] . "\n";
	//}
	
	// $user->setUserCode($uid);
	
	echo "REFER_CODE UPDATED";
}
?>